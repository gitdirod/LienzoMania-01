<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\MemoryService;
use App\Http\Requests\StoreMemoryRequest;
use App\Http\Requests\UpdateMemoryRequest;
use App\Services\ImageService;

class MemoryController extends Controller
{
    use ApiResponse;
    protected $memoryService;

    public function __construct(MemoryService $memoryService)
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);
        $this->memoryService = $memoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Memory::all();
        return $this->successResponse('Memorias recuperadas correctamente.', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMemoryRequest $request, ImageService $imageService)
    {
        try {
            $data = $request->validated();
            $image_name = $imageService->insertImage(Memory::IMAGE_WIDTH, Memory::IMAGE_HIGTH, 'memories', $data['image']);
            $this->memoryService->createMemory($data, $image_name);
            return $this->successResponse('Memoria creada correctamente.', 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al guardar en base de datos', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado al guardar', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function show(Memory $memory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemoryRequest $request, Memory $memory, ImageService $imageService)
    {
        try {
            $data = $request->validated();
            $image_name = null;
            if (isset($data['image'])) {
                $imageService->deleteImage('memories', $memory->image);
                $image_name = $imageService->insertImage(Memory::IMAGE_WIDTH, Memory::IMAGE_HIGTH, 'memories', $data['image']);
            }
            $this->memoryService->updateMemory($memory->id, $data, $image_name);
            return $this->successResponse('Memoria actualizada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al actualizar en base de datos', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado al actualizar', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Memory $memory, ImageService $imageService)
    {
        try {
            if ($memory) {
                $imageService->deleteImage('memories', $memory->image);
            }
            $this->memoryService->destroyMemory($memory->id);
            $this->successResponse('Memoria Eliminada Correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->errorResponse('Error al eliminar memoria en base de datos', $e->getMessage());
        } catch (\Exception $e) {
            $this->errorResponse('Error inesperado al eliminar memoria', $e->getMessage());
        }
    }
}
