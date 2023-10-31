<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function createCustomer($data)
    {
        return Customer::create([
            'name' => $data['name'],
            'ccruc' => $data['ccruc'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);
    }

    public function updateCustomer($id, $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->name = $data['name'];
        $customer->ccruc = $data['ccruc'];
        $customer->phone = $data['phone'];
        $customer->email = $data['email'];
        $customer->save();
        return $customer;
    }
}
