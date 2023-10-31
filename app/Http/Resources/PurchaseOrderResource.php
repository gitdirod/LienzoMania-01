<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $products = $this->products->map(function ($product) {
            $pro = Product::find($product->product_id);
            return [
                'id' => $product->id,
                'purchase_id' => $product->purchase_id,
                'product_id' => $product->product_id,
                'name' => $pro->name,
                'code' => $pro->code,
                'quantity' => $product->quantity,
                'price' => $product->price,
                'subtotal' => $product->subtotal,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });
        $user = $this->user;
        unset($user['phone']);
        unset($user['role']);

        return [
            'id' => $this->id,
            "user" => $user,
            "envoice" => $this->envoice,
            "subtotal" => $this->subtotal,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "products" => $products,
        ];
    }
}
