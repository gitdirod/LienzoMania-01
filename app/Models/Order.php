<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderState;
use Illuminate\Support\Str;
use App\Models\OrderPayment;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Intervention\Image\Facades\Image as ImageIntervention;

class Order extends Model
{
    use HasFactory;
    const DAFAULT_ENVOICE = "No registra";
    protected $fillable = [
        'user_id',
        'total',
        'subtotal'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'subtotal', 'price')->with('image');
    }
    public function orderPayment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id')->latest();
    }
    public function orderState()
    {
        return $this->hasOne(OrderState::class, 'order_id')->latest();
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id')->latest();
    }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id')->latest();
    }
    public function addresses()
    {
        return $this->hasMany(OrderAddress::class, 'order_id')->latest();
    }


    public function getAddressesAttribute()
    {
        return $this->addresses()->get()->mapWithKeys(function ($address) {
            return [$address->type => $address];
        });
    }


    public function insertImages($images)
    {
        $user = Auth::user();
        foreach ($images as $image) {
            $name_image = Str::uuid() . "." . $image->extension();
            $image_server = ImageIntervention::make($image);
            if ($image_server->width() > $image_server->height()) {
                $image_server->widen(700);
            } elseif ($image_server->height() > $image_server->width()) {
                $image_server->heighten(700);
            } else {
                $image_server->resize(700, 700);
            }
            $image_path = public_path('payments') . '/' . $name_image;
            $image_server->save($image_path);

            $pay = Payment::create([
                'order_id' => $this->id,
                'user_id' => $user->id,
                'name' => $name_image
            ]);
            return $pay;
        }
    }

    public function insertPayment($value = "POR PAGAR")
    {
        OrderPayment::create([
            'order_id' => $this->id,
            'state' => $value,
        ]);
    }

    public function insertState($value = "EN BODEGA")
    {
        OrderState::create([
            'order_id' => $this->id,
            'state' => $value,
        ]);
    }

    public function updatePayment($value)
    {
        $find_payment = null;
        if (isset($this->payment->order_id)) {
            OrderPayment::where('order_id', $this->payment->order_id)->first();
        }

        if (isset($find_payment)) {
            $payment = OrderPayment::find($find_payment->id);
            if ($value == 1) {
                $payment->state = "PAGADO";
                $payment->save();
                return true;
            } elseif ($value == 0) {
                $payment->state = "POR PAGAR";
                $payment->save();
                return true;
            } else {
                return false;
            }
        } else {
            if ($value == 1) {
                $this->insertPayment("PAGADO");
                return true;
            } elseif ($value == 0) {
                $this->insertPayment("POR PAGAR");
                return true;
            } else {
                return false;
            }
        }
    }
    public function updateProductsUnits()
    {
        $products = $this->products()->get();
        foreach ($products as $pro) {
            $product = Product::find($pro->id);
            $product->updateUnits();
        }
    }

    public function updateState($value)
    {
        $find_state = null;
        if (isset($this->state->order_id)) {
            OrderState::where('order_id', $this->state->order_id)->first();
        }

        if (isset($find_state)) {
            $state = OrderState::find($find_state->id);
            if ($value == 0) {
                $state->state = "EN BODEGA";
                $state->save();
                return true;
            } elseif ($value == 1) {
                $state->state = "EN TRAYECTO";
                $state->save();
                return true;
            } elseif ($value == 2) {
                $state->state = "ENTREGADO";
                $state->save();
                return true;
            } else {
                return false;
            }
        } else {

            if ($value == 0) {
                $this->insertState("EN BODEGA");
                return true;
            } elseif ($value == 1) {
                $this->insertState("EN TRAYECTO");
                return true;
            } elseif ($value == 2) {
                $this->insertState("ENTREGADO");
                return true;
            } else {
                return false;
            }
        }
    }

    public function insertAddress($address_id)
    {
        $find_address_send = Address::where('id', $address_id)->first();
        if (isset($find_address_send)) {
            $add = Address::find($find_address_send->id);
            $address = new OrderAddress;
            $address->order_id = $this->id;
            $address->envoice = $add->envoice;
            $address->people = $add->people;
            $address->ccruc = $add->ccruc;
            $address->city = $add->city;
            $address->address = $add->address;
            $address->phone = $add->phone->number;
            $address->save();
            return $address;
        }
        return false;
    }
    public function insertProducts($products)
    {
        $Array_products = [];
        foreach ($products as $product) {
            $find_pro = Product::where('id', $product['id'])->first();
            if (isset($find_pro)) {
                $pro = Product::find($product['id']);

                $Array_products[] = [
                    'order_id' => $this->id,
                    'product_id' => $pro->id,
                    'quantity' => (int)$product['quantity'],
                    'price' => number_format((float)$pro->price, 2, '.', ''),
                    'subtotal' => number_format((float)($pro->price * $product['quantity']), 2, '.', ''),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
        }
        //Save on DB
        OrderProduct::insert($Array_products);
        return $Array_products;
    }

    public function checkQuantityProducts($products)
    {
        foreach ($products as $product) {
            $pro = Product::find($product['id']);

            if (!$pro) {
                return false;
            }

            if ((int) $pro->units < (int) $product['quantity']) {
                return false;
            }
        }
        return true;
    }
}
