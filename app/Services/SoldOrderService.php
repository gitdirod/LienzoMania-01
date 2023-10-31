<?php

namespace App\Services;

use App\Models\SoldOrder;

class SoldOrderService
{
    public function createOrder($user, $data)
    {
        return SoldOrder::create([
            'user_id' => $user->id,
            'total' => $data['total'],
            'subtotal' => $data['subtotal'],
            'envoice' => SoldOrder::DAFAULT_ENVOICE
        ]);
    }

    public function updateEnvoice($data)
    {
        $soldOrder = SoldOrder::findOrFail($data['id']);
        $soldOrder->envoice = isset($data['envoice']) ? $data['envoice'] : SoldOrder::DAFAULT_ENVOICE;
        $soldOrder->save();
        return $soldOrder;
    }

    public function getIdsSoldOrderProduct($sold_order_id)
    {
        $sold_order = SoldOrder::findOrFail($sold_order_id);

        return $sold_order->products->map(function ($pro) {
            return $pro['pivot']['product_id'];
        });
    }
}
