<?php

namespace App\Models;

use App\Models\SoldOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoldOrderProduct extends Model
{
    use HasFactory;

    public function soldOrder()
    {
        return $this->belongsTo(SoldOrder::class)->with('user');
    }
}
