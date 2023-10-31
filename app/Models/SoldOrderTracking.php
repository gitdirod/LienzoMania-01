<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldOrderTracking extends Model
{
    use HasFactory;
    const STATE_BODEGA = "EN BODEGA";
    const STATE_TRAYECTO = "EN TRAYECTO";
    const STATE_ENTREGADO = "ENTREGADO";

    protected $fillable = [
        'state',
        'sold_order_id'
    ];
}
