<?php

namespace App\Services;

use App\Models\Phone;

class PhoneService
{
    public function createPhone($phone, $user)
    {
        return Phone::create([
            "user_id" => $user->id,
            "type" => $phone['type'],
            "number" => $phone["number"],
        ]);
    }
    public function updatePhone($id, $data)
    {
        $phone = Phone::findOrFail($id);
        $phone->type = $data['type'];
        $phone->number = $data['number'];
        $phone->save();
        return $phone;
    }
}
