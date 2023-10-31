<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory;

    const TYPE_MAIN = 'main';
    const TYPE_ENVOICE = 'envoice';
    const TYPE_SEND = 'send';

    protected $fillable = [
        'user_id',
        'type',
        'number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
