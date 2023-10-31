<?php

namespace App\Services;

use App\Models\OrderPayment;

class OrderPaymentService
{
    public function createOrderPayment($order_id, $state = OrderPayment::STATE_POR_PAGAR)
    {
        return OrderPayment::create([
            'order_id' => $order_id,
            'state' => $state,
        ]);
    }
    public function updateOrderPayment($order_id, $state = OrderPayment::STATE_POR_PAGAR)
    {
        $order_payment = OrderPayment::where('order_id', $order_id)
            ->first();
        if (!$order_payment) {
            throw new \Exception('Estado de pago no encontrado');
        }
        $order_payment->state = $state;
        $order_payment->save();
        return $order_payment;
    }
}
