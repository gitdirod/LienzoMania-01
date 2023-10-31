<?php

namespace App\Services;

use App\Models\SoldOrderPayment;

class SoldOrderPaymentService
{
    public function createSoldOrderPayment($sold_order_id, $state = SoldOrderPayment::STATE_POR_PAGAR)
    {
        return SoldOrderPayment::create([
            'sold_order_id' => $sold_order_id,
            'state' => $state,
        ]);
    }
    public function updateSoldOrderPayment($sold_order_id, $state = SoldOrderPayment::STATE_POR_PAGAR)
    {
        $order_payment = SoldOrderPayment::where('sold_order_id', $sold_order_id)
            ->first();
        if (!$order_payment) {
            throw new \Exception('Estado de pago no encontrado');
        }
        $order_payment->state = $state;
        $order_payment->save();
        return $order_payment;
    }
}
