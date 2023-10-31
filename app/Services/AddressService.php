<?php

namespace App\Services;

use App\Models\Address;

class AddressService
{
    public function createAddress($user, $data)
    {
        if (!$user) {
            throw new \Exception('Datos faltantes.');
        }
        return Address::create([
            "user_id" => $user->id,
            "type" => $data["type"],
            "people" => $data["people"],
            "ccruc" => $data["ccruc"],
            "city" => $data["city"],
            "address" => $data["address"],
            "phone" => $data["phone"],
        ]);
    }
    public function findAddress($user, $data)
    {
        $address_update = Address::where('user_id', $user->id)
            ->where('type', $data['type'])
            ->first();
        return isset($address_update) ? $address_update : null;
    }
    public function updateAddress($address_update, $data)
    {
        if (!$address_update) {
            throw new \Exception('Dirección no valida');
        }
        $address_update->people = $data["people"];
        $address_update->ccruc = $data["ccruc"];
        $address_update->city = $data["city"];
        $address_update->address = $data["address"];
        $address_update->phone = $data["phone"];
        $address_update->save();
        return $address_update;
    }
    public function getAddress($id)
    {
        return Address::findOrFail($id);
    }
}
