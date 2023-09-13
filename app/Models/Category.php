<?php

namespace App\Models;

use App\Models\Group;
use Illuminate\Support\Str;
use App\Models\CategoryImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Intervention\Image\Facades\Image as ImageIntervention;

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
