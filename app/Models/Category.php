<?php

namespace App\Models;

use App\Models\Group;
use App\Models\CategoryImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'group_id',
        'suggested'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function images()
    {
        return $this->hasMany(CategoryImage::class, 'category_id');
    }
    // public function image()
    // {
    //     return $this->hasOne(CategoryImage::class, 'category_id')->first();
    // }
    public function image()
    {
        return $this->hasOne(CategoryImage::class, 'category_id')->withDefault(function ($model) {
            return $model->first();
        });
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
