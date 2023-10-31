<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'ccruc',
        'phone',
        'email'
    ];

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function getAddressesAttribute()
    {
        return $this->addresses()->get()->mapWithKeys(function ($address) {
            return [$address->type => $address];
        });
    }
}
