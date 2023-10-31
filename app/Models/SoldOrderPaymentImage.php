<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldOrderPaymentImage extends Model
{
    use HasFactory;

    const IMAGE_WIDTH = 700;
    const IMAGE_HEIGTH = 700;
    const IMAGE_PATH = 'sold_order_payment_image/';

    protected $fillable = [
        'user_id',
        'sold_order_id',
        'name'
    ];
}
