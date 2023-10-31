<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Like;
use App\Models\Phone;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements MustVerifyEmail
// class User extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }
    public function phones()
    {
        return $this->hasMany(Phone::class, 'user_id');
    }
    public function address()
    {
        return $this->hasMany(Address::class, 'user_id');
    }
    public function getAddressesAttribute()
    {
        return $this->address()->get()->mapWithKeys(function ($address) {
            $phone = Phone::find($address->phone_id);
            unset($phone['user_id']);
            $address['phone'] = $phone;
            unset($address['phone_id']);
            unset($address['user_id']);
            return [$address->type => $address];
        });
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
