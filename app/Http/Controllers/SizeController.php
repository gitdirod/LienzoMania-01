<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSizeRequest;
use App\Http\Requests\UpdateSizeRequest;
use App\Services\SizeService;

class SizeController extends Controller
{
    use ApiResponse;
    protected $sizeService;

    public function __construct(SizeService $sizeService)
    {
        // Se aplica el middleware de autenticación a todos los métodos excepto 'index' y 'show'
        $this->middleware('auth')->except(['index', 'show']);

        // Asegura que el usuario puede realizar acciones de administrador solo para los métodos 'store' y 'update'
        $this->middleware('can:admin')->only(['store', 'update']);

        $this->sizeService = $sizeService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::all();
        return $this->successResponse('Sizes retrieved successfully.', $sizes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSizeRequest $request)
    {
        try {
            $data = $request->validated();
            $new_size = $this->sizeService->createSize($data['name']);

            return $this->successResponse('Tamaño creado correctamente.', $new_size, 201);
        } catch (\Exception $e) {
            return $this->errorResponse("Error inesperado al crear el tamaño", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSizeRequest $request, Size $size)
    {
        try {
            $data = $request->validated();

            $update_size = $this->sizeService->updateSize($size->id, $data['name']);

            return $this->successResponse('Tamaño actualizado.', $update_size);
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado actualizando tamaño', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        try {
            $this->sizeService->deleteSize($size->id);
            return $this->successResponse('Tamaño eliminado');
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado al eliminar tamaño', $e->getMessage());
        }
    }
}
