<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\OrderProduct;

class OrderProductService
{
    public function createOrderProduct($products, $order_id)
    {
        $Array_products = [];
        foreach ($products as $pro) {
            $Array_products[] = [
                'order_id' => $order_id,
                'product_id' => $pro['id'],
                'quantity' => $pro['quantity'],
                'price' => $pro['price'],
                'subtotal' => $pro['subtotal'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        OrderProduct::insert($Array_products);
        return $Array_products;
    }
}
