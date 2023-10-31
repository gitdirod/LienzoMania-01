<?php

namespace App\Models;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    const TYPE_MAIN = 'main';
    const TYPE_ENVOICE = 'envoice';
    const TYPE_SEND = 'send';

    protected $fillable = [
        'user_id',
        'type',
        'people',
        'ccruc',
        'city',
        'address',
        'phone_id'
    ];

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }
}
