<?php

namespace App\Services;

use App\Models\CustomerAddress;

class CustomerAddressService
{
    public function createCustomerAddress($data)
    {
        return CustomerAddress::create([
            'customer_id' => $data['customer_id'],
            'type' => $data['type'],
            'ccruc' => $data['ccruc'],
            'people' => $data['people'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'address' => $data['address'],
        ]);
    }
    public function updateCustomerAddress($id, $data)
    {
        $customerAddress = CustomerAddress::findOrFail($id);
        $customerAddress->type = $data['type'];
        $customerAddress->ccruc = $data['ccruc'];
        $customerAddress->people = $data['people'];
        $customerAddress->phone = $data['phone'];
        $customerAddress->city = $data['city'];
        $customerAddress->address = $data['address'];
        $customerAddress->save();
        return $customerAddress;
    }
    public function getAddress($id)
    {
        return CustomerAddress::findOrFail($id);
    }
}
