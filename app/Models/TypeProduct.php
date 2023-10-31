<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'type_product_id');
    }

    public function isShowed()
    {
        $products = $this->products()->get();
        $thereProducts = $products->filter(function ($product) {
            return isset($product['units']) && $product['units'] > 0 && $product['available'] == true;
        });

        $isShow = $thereProducts->isNotEmpty();
        return $isShow ? true : false;
    }
}
