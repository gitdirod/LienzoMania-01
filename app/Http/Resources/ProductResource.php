<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type_product = $this->typeProduct;
        $group = $this->category->group;

        $data = [
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category->name,
                'image' => $this->category->image()->first(),
            ],
            'type_product' => [
                'id' => $this->type_product_id,
                'name' => $type_product->name,
                'image' => $type_product->image,
            ],
            'group' => [
                'id' => $group->id,
                'name' => $group->name
            ],
            'suggested' => [
                'group_id' => isset($this->suggested->suggestion_id) ? $this->suggested->suggestion_id : false,
                'id' => isset($this->suggested->id) ? $this->suggested->id : false,
            ],
            'id' => $this->id,
            'new' => $this->is_new(),
            'name' => $this->name,
            'code' => $this->code,
            'weight' => $this->weight,
            'size' => $this->size,
            'number_color' => $this->number_color,
            'price' => $this->price,
            'units' => $this->units,
            'description' => $this->description,
            'available' => $this->units > 0 ? $this->available : false,
            'images' => $this->images()->select('id', 'name')->get(),
            'like' => $this->likedByUser()
        ];

        // Añadir el campo "sold" si el usuario es administrador
        if ($request->user() && $request->user()->isAdmin()) {
            $data['sold'] = $this->sold;
            $data['purchased'] = $this->purchased;

            $data['purchase_order_products'] = $this->purchaseOrderProducts()->get();
            $data['sold_order_products'] = $this->soldOrderProducts()->get();
        }

        return $data;
    }
}
