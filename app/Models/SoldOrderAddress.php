<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldOrderAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'sold_order_id',
        'type',
        'people',
        'ccruc',
        'city',
        'address',
        'phone',

    ];
    const TYPE_ENVOICE = 'envoice';
    const TYPE_SEND = 'send';
}
