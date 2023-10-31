<?php

namespace App\Services;

use App\Models\OrderState;

class OrderStateService
{
    public function createOrderState($order_id, $state = OrderState::STATE_BODEGA)
    {
        return OrderState::create([
            'order_id' => $order_id,
            'state' => $state,
        ]);
    }

    public function updateOrderPayment($order_id, $state = OrderState::STATE_BODEGA)
    {
        $order_payment = OrderState::where('order_id', $order_id)
            ->first();
        if (!$order_payment) {
            throw new \Exception('Estado no encontrado');
        }
        $order_payment->state = $state;
        $order_payment->save();
        return $order_payment;
    }
}
