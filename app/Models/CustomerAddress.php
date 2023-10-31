<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    const TYPE_ENVOICE = 'envoice';
    const TYPE_SEND = 'send';

    protected $fillable = [
        'customer_id',
        'type',
        'ccruc',
        'people',
        'phone',
        'city',
        'address'
    ];
}
