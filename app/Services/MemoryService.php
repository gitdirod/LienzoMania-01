<?php

namespace App\Services;

use App\Models\Memory;

class MemoryService
{
    public function createMemory($data, $name_image)
    {
        return Memory::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $name_image
        ]);
    }

    public function updateMemory($id, $data, $image_name = null)
    {
        $memory_update = Memory::findOrFail($id);
        if ($image_name) {
            $memory_update->image = $image_name;
        }
        $memory_update->name = $data['name'];
        $memory_update->description = $data['description'];
        $memory_update->save();
        return $memory_update;
    }

    public function destroyMemory($id)
    {
        $memory_destroy = Memory::findOrFail($id);
        $memory_destroy->delete();
    }
}
