<?php

namespace App\Models;




use Carbon\Carbon;
use App\Models\Like;
use App\Models\Order;
use App\Models\Category;
use App\Models\Suggested;
use Illuminate\Support\Str;
use App\Models\OrderProduct;
use App\Models\ProductImage;
use App\Models\SoldOrderProduct;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Intervention\Image\Facades\Image as ImageIntervention;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'type_product_id',
        'units',
        'description',
        'available',
        'code',
        'weight',
        'size',
        'number_color'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function getLastPurchaseOrderProduct()
    {
        return PurchaseOrderProduct::where('product_id', $this->id)
            ->latest('created_at')
            ->first();
    }

    public function getPrice()
    {
        $lastPurchaseProduct = $this->getLastPurchaseOrderProduct();
        return $lastPurchaseProduct ? $lastPurchaseProduct->price : 0;
    }


    public function suggested()
    {
        return $this->hasOne(Suggested::class, 'product_id');
    }
    public function typeProduct()
    {
        return $this->belongsTo(TypeProduct::class, 'type_product_id');
    }
    public function is_new()
    {
        $date_created = $this->created_at;
        $date = Carbon::now();
        $diff = $date_created->diffInDays($date);
        return $diff > 15 ? false : true;
    }

    // One To Many
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function image()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->latestOfMany();
    }

    public function purchaseOrderProducts()
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'product_id')->with('purchaseOrder');
    }
    public function soldOrderProducts()
    {
        return $this->hasMany(SoldOrderProduct::class, 'product_id')->with('soldOrder');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'product_id');
    }

    public function likedByUser()
    {
        // Check if there is an authenticated user
        if (Auth::check()) {
            // Use the current user's ID to check if they have liked this product
            return $this->hasMany(Like::class, 'product_id')
                ->where('user_id', Auth::user()->id)
                ->exists();
        }

        // If there's no authenticated user, return false
        return false;
    }


    public function updateProduct(array $toUpdate)
    {
        $this->name = $toUpdate["name"];
        $this->code = $toUpdate["code"];
        $this->category_id = $toUpdate["category"];
        $this->type_product_id = $toUpdate["type"];
        $this->description = $toUpdate["description"];
        $this->available = $toUpdate["available"];

        $this->size = $toUpdate["size"];
        $this->weight = $toUpdate["weight"];
        $this->number_color = $toUpdate["number_color"];
        $this->save();
    }

    public function remaining_products()
    {
        $sold = $this->orderProducts()->get();
        $suma = 0;
        if (is_Object($sold)) {
            foreach ($sold as $sol) {
                $order = Order::find($sol->order_id);
                $order_payment = $order->payment()->first();
                if ($order_payment->state === 'PAGADO') {
                    $suma += $sol->quantity;
                }
            }
        }
        return $this->units - $suma;
    }

    public function updateUnits()
    {
        // Actualiza total ingresado "Compras"
        $purchased_list = PurchaseOrderProduct::where('product_id', $this->id)->pluck('quantity')->toArray();
        $purchased_total = array_sum($purchased_list);
        $this->purchased = $purchased_total;
        // $this->save();

        // Actualiza total egresado "ventas"
        $sold = $this->orderProducts()->get();
        $salida = 0;
        if (is_Object($sold)) {
            foreach ($sold as $sol) {
                $order = Order::find($sol->order_id);
                $order_payment = $order->orderPayment()->first();
                if ($order_payment->state === 'PAGADO') {
                    $salida += $sol->quantity;
                }
            }
        }
        $this->sold = $salida;

        // Actualiza total disponible "inventario"
        $this->units = $purchased_total - $salida;
        $this->save();
    }
    public function updatePrice()
    {
        $purchased_list = PurchaseOrderProduct::where('product_id', $this->id)
            ->orderBy('purchase_order_id', 'desc')
            ->get();

        $last_price = $purchased_list->first();
        $this->price = $last_price->price;
        $this->save();
    }

    public function insertImages(array $datos)
    {
        foreach ($datos['images'] as $image) {
            $name_image = Str::uuid() . "." . $image->extension();
            $image_server = ImageIntervention::make($image);
            if ($image_server->width() > $image_server->height()) {
                $image_server->widen(700);
            } elseif ($image_server->height() > $image_server->width()) {
                $image_server->heighten(700);
            } else {
                $image_server->resize(700, 700);
            }
            $image_path = public_path('products') . '/' . $name_image;
            $image_server->save($image_path);

            ProductImage::create([
                'product_id' => $this->id,
                'name' => $name_image,
            ]);
        }
    }
    public function deleteImages($datos)
    {
        if (isset($datos)) {
            foreach ($datos as $to_delete) {
                $find_img = ProductImage::where('id', $to_delete['id'])->first();
                if (isset($find_img)) {
                    $path_file = "products/" . $find_img->name;
                    if (File::exists($path_file)) {
                        File::delete($path_file);
                    }
                    $find_img->delete();
                }
            }
        }
    }
}
