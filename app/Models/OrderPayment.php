<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPayment extends Model
{
    use HasFactory;

    const STATE_POR_PAGAR = "POR PAGAR";
    const STATE_PAGADO = "PAGADO";

    protected $fillable = [
        'state',
        'order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        $order = $this->order()->first();
        $user = User::find($order->user_id);
        return $user;
    }
}
