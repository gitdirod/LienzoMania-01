<?php

namespace App\Services;

use App\Models\Size;

class SizeService
{

    public function createSize($name)
    {
        return Size::create(['name' => $name]);
    }

    public function updateSize($id, $name)
    {
        $size = Size::findOrFail($id);
        $size->name = $name;
        $size->save();
        return $size;
    }

    public function deleteSize($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
    }
}
