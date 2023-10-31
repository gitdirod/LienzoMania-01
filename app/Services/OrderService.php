<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function createOrder($user, $data)
    {
        return Order::create([
            'user_id' => $user->id,
            'total' => $data['total'],
            'subtotal' => $data['subtotal'],
            'envoice' => Order::DAFAULT_ENVOICE
        ]);
    }

    public function getIdsOrderProduct($order_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            throw new \Exception("Orden con ID $order_id no encontrada");
        }

        return $order->products->map(function ($pro) {
            return $pro['pivot']['product_id'];
        });
    }
}
