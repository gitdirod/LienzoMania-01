<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoldOrder extends Model
{
    use HasFactory;
    const DAFAULT_ENVOICE = "No registra";
    protected $fillable = [
        'user_id',
        'total',
        'subtotal',
        'envoice'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sold_order_products')
            ->withPivot('quantity', 'subtotal', 'price');
    }

    public function soldOrderPayment()
    {
        return $this->hasOne(SoldOrderPayment::class, 'sold_order_id')->latest();
    }

    public function soldOrderTracking()
    {
        return $this->hasOne(soldOrderTracking::class, 'sold_order_id')->latest();
    }
    public function soldOrderPaymentImages()
    {
        return $this->hasMany(SoldOrderPaymentImage::class, 'sold_order_id')->latest();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'sold_order_id')->latest();
    }

    public function addresses()
    {
        return $this->hasMany(SoldOrderAddress::class, 'sold_order_id')->latest();
    }

    public function getAddressesAttribute()
    {
        return $this->addresses()->get()->mapWithKeys(function ($address) {
            return [$address->type => $address];
        });
    }
}
