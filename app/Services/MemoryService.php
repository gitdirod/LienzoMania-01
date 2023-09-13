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
}
