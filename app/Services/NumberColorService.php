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
        $numberColor = NumberColor::find($id);
        if (!$numberColor) {
            throw new \Exception("NumberColor con ID $id no encotrado");
        }
        $numberColor->delete();
    }
    public function updateNumberColor($id, $name)
    {
        // Encuentra el tamaño por su ID
        $numberColor = NumberColor::find($id);

        if (!$numberColor) {
            // Puedes lanzar una excepción si no se encuentra el numero de color, 
            // o manejarlo de otra forma según tus necesidades.
            throw new \Exception("NumberColor con ID $id no encotrado");
        }

        // Actualiza el nombre del numero de color y guarda los cambios
        $numberColor->name = $name;
        $numberColor->save();

        return $numberColor;
    }
}
