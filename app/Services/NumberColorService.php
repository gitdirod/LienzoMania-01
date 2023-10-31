<?php

namespace App\Services;

use App\Models\NumberColor;

class NumberColorService
{

    public function createNumberColor($name)
    {
        return NumberColor::create(['name' => $name]);
    }
    public function deleteNumberColor($id)
    {
        $numberColor = NumberColor::findOrFail($id);
        $numberColor->delete();
    }
    public function updateNumberColor($id, $name)
    {
        // Encuentra el tamaño por su ID
        $numberColor = NumberColor::findOrFail($id);
        $numberColor->name = $name;
        $numberColor->save();

        return $numberColor;
    }
}
