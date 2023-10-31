<?php

namespace App\Services;

use App\Models\SoldOrderAddress;

class SoldOrderAddressService
{
    public function createOrderAddressFromAddress($sold_order_id, $address)
    {
        return SoldOrderAddress::create([
            'sold_order_id' => $sold_order_id,
            'type' => $address->type,
            'people' => $address->people,
            'ccruc' => $address->ccruc,
            'city' => $address->city,
            'address' => $address->address,
            'phone' => $address->phone
        ]);
    }
}
