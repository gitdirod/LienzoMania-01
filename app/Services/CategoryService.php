<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function createCategory($data)
    {
        return Category::create([
            'name' => $data['name'],
            'group_id' => $data['group_id'],
            'suggested' => (bool)($data['suggested'] ?? false),
        ]);
    }


    public function updateCategory($id, $data)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new \Exception("Categoría con ID $id no encontrado");
        }

        $category->name = $data['name'];
        $category->group_id = $data['group_id'];
        $category->suggested = (bool)($data['suggested'] ?? false);
        $category->save();

        return $category;
    }
}
