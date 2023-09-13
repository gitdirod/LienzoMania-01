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
        // Encuentra el tamaño por su ID
        $size = Size::find($id);

        if (!$size) {
            // Puedes lanzar una excepción si no se encuentra el tamaño, 
            // o manejarlo de otra forma según tus necesidades.
            throw new \Exception("Size con ID $id no encotrado");
        }

        // Actualiza el nombre del tamaño y guarda los cambios
        $size->name = $name;
        $size->save();

        return $size;
    }

    public function deleteSize($id)
    {
        $size = Size::find($id);
        if (!$size) {
            throw new \Exception("Size con ID $id no encotrado");
        }
        $size->delete();
    }
}
