<?php

namespace App\Services;

use App\Models\SoldOrderTracking;

class SoldOrderTrackingService
{
    public function createSoldOrderTracking($sold_order_id, $state = SoldOrderTracking::STATE_BODEGA)
    {
        return SoldOrderTracking::create([
            'sold_order_id' => $sold_order_id,
            'state' => $state,
        ]);
    }

    public function updateSoldOrderTracking($sold_order_id, $state = SoldOrderTracking::STATE_BODEGA)
    {
        $order_payment = SoldOrderTracking::where('sold_order_id', $sold_order_id)
            ->first();
        if (!$order_payment) {
            throw new \Exception('Estado no encontrado');
        }
        $order_payment->state = $state;
        $order_payment->save();
        return $order_payment;
    }
}
