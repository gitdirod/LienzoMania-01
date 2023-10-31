<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderProduct extends Model
{
    protected $fillable = [
        'product_id',
        'purchase_order_id',
        'quantity',
        'price',
        'subtotal'
    ];
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function getUserAttribute()
    {
        $user = User::find($this->purchase->user_id);
        return collect($user);
    }
}
