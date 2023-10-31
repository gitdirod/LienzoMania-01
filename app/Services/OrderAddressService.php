<?php

namespace App\Services;

use App\Models\OrderAddress;

class OrderAddressService
{
    public function createOrderAddressFromAddress($order_id, $address)
    {
        return OrderAddress::create([
            'order_id' => $order_id,
            'type' => $address->type,
            'people' => $address->people,
            'ccruc' => $address->ccruc,
            'city' => $address->city,
            'address' => $address->address,
            'phone' => $address->phone->number,
        ]);
    }
}
