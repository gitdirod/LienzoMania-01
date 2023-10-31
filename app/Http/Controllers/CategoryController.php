<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponse;
use App\Services\ImageService;
use App\Services\CategoryService;
use App\Http\Resources\CategoryCollection;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use ApiResponse;
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        // Se aplica el middleware de autenticación a todos los métodos excepto 'index' y 'show'
        $this->middleware('auth')->except(['index', 'show']);

        // Asegura que el usuario puede realizar acciones de administrador solo para los métodos 'store' y 'update'
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request, ImageService $imageService)
    {
        try {
            $datos = $request->validated();
            $category = $this->categoryService->createCategory($datos);
            $imageService->insertImages(300, 300, 'categories', $category, $datos);
            return $this->successResponse("Categoría creada", $category, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse("Error al crear categoría en la base de datos", $e->getMessage(), 500);
        } catch (\Exception $e) {
            return $this->errorResponse("Error inesperado al crear categoría", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $group = $category->group()->get();
        return [
            'id' => $category->id,
            'name'  => $category->name,
            'image' => $category->image,
            'images' => $category->images()->select('id', 'name')->get(),
            'group_id' => $category->group_id,
            'group_name' => $group[0]['name'],
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category, ImageService $imageService)
    {
        try {
            $datos = $request->validated();
            if (isset($datos['images'])) {
                $imageService->handleCategoryImages($category, $datos);
            }
            $this->categoryService->updateCategory($category->id, $datos);
            return $this->successResponse("Categoría actualizada");
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al actualizar categoría en la base de datos', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado actualizando categoría', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
