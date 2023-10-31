<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldOrderPayment extends Model
{
    use HasFactory;

    const STATE_POR_PAGAR = "POR PAGAR";
    const STATE_PAGADO = "PAGADO";

    protected $fillable = [
        'sold_order_id',
        'state'
    ];

    public function soldOrder()
    {
        return $this->belongsTo(SoldOrder::class);
    }
    public function user()
    {
        return $this->soldOrder->user;
        // $soldOrder = $this->soldOrder()->first();
        // $user = User::find($soldOrder->user_id);
        // return $user;
    }
}
