<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
        $group = $this->group()->get();
        return [
            'group_name' => $group[0]['name'],
            'group_id' => $this->group_id,
            'id' => $this->id,
            'name'  => $this->name,
            'suggested' => $this->isShowed() ? $this->suggested : false,
            'images' => $this->images()->select('id', 'name')->get(),
            'show' => $this->isShowed(),
        ];
    }
}
