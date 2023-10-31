<?php

namespace App\Models;

use App\Models\PurchaseOrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'envoice',
        'subtotal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function products()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    // public function products1()
    // {
    //     return $this->belongsToMany(Product::class, 'purchase_order_products')
    //         ->withPivot('quantity', 'subtotal', 'price')
    //         ->select('name', 'code');
    // }

    // public function insertProducts($products)
    // {
    //     foreach ($products as $product) {
    //         $pro = Product::find($product['id']);
    //         if (!$pro) {
    //             throw new \Exception("Producto con ID {$$product['id']} no encontrado.");
    //         }
    //         $price = number_format((float)$product['price'], 2, '.', '');
    //         $new = new PurchaseOrderProduct;
    //         $new->purchase_order_id = $this->id;
    //         $new->product_id = $pro->id;
    //         $new->quantity = (int)$product['quantity'];
    //         $new->price = $price;
    //         $new->subtotal = $price * $product['quantity'];
    //         $new->save();

    //         $pro->updateUnits();
    //         $pro->updatePrice();
    //     }
    // }
    // public function updateProducts($products)
    // {
    //     // Eliminar productos que no estan presentes
    //     $new_products_id_issets = collect($products)->pluck('id')->map(function ($id) {
    //         return (int)$id;
    //     });

    //     $old_products_id_issets = PurchaseOrderProduct::where('purchase_order_id', $this->id)->pluck('product_id');
    //     for ($i = 0; $i < count($old_products_id_issets); $i++) {
    //         if (!$new_products_id_issets->contains($old_products_id_issets[$i])) {
    //             $item = PurchaseOrderProduct::where([
    //                 'purchase_order_id' => $this->id,
    //                 'product_id' => $old_products_id_issets[$i]
    //             ])->first();
    //             $to_delete = PurchaseOrderProduct::find((int)$item['id']);
    //             $product_id = $to_delete->product_id;
    //             $to_delete->delete();

    //             $product_delete = Product::find($product_id);
    //             $product_delete->updateUnits();
    //             $product_delete->updatePrice();
    //         }
    //     }
    //     // FIN Eliminar productos que no estan presentes


    //     $Array_products = [];
    //     foreach ($products as $product) {



    //         $find_pro = PurchaseOrderProduct::where([
    //             'purchase_order_id' => $this->id,
    //             'product_id' => $product['id']
    //         ])->first();

    //         if (isset($find_pro)) {
    //             $pro = PurchaseOrderProduct::find((int)$find_pro['id']);
    //             $Array_products[] = ['pro' => $pro];
    //             $pro->quantity = (int)$product['quantity'];
    //             $price = number_format((float)$product['price'], 2, '.', '');
    //             $pro->price = $price;
    //             $pro->subtotal = $price * (int)$product['quantity'];
    //             $pro->save();
    //         } else {
    //             $new = new PurchaseOrderProduct;
    //             $price = number_format((float)$product['price'], 2, '.', '');
    //             $new->purchase_order_id = $this->id;
    //             $new->product_id = $product['id'];
    //             $new->quantity = (int)$product['quantity'];
    //             $new->price = $price;
    //             $new->subtotal = $price * (int)$product['quantity'];
    //             $new->save();
    //         }

    //         $product_update = Product::find($product['id']);
    //         $product_update->updateUnits();
    //         $product_update->updatePrice();
    //     }

    //     return $Array_products;
    // }
}
