<?php

namespace App\Services;

use App\Models\PurchaseOrder;

class PurchaseOrderService
{
    public function createPurchaseOrder($data, $user)
    {
        return PurchaseOrder::create([
            'user_id' => $user->id,
            'envoice' => $data['envoice'],
            'subtotal' => $data['subtotal'],
        ]);
    }

    public function updatePurchaseOrder($data, $purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrder->envoice = $data['envoice'];
        $purchaseOrder->subtotal = $data['subtotal'];
        $purchaseOrder->save();
        return $purchaseOrder;
    }

    public function getIdProductsAndIdsPurchassedOrderProduct($purchase_order_id)
    {
        $purchase_order = PurchaseOrder::findOrFail($purchase_order_id);
        return $purchase_order->products->map(function ($pro) {
            $product['id'] = $pro['id'];
            $product['product_id'] = $pro['product_id'];
            return $product;
        });
    }
}
