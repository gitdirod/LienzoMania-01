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

            if (isset($data['image'])) {
                $memory->deleteImage();
                $memory->image = $memory->saveImage($data['images'], 500, 500);
            }
            $memory->name = $data['name'];
            $memory->description = $data['description'];
            $memory->save();

            return $this->successResponse('Memoria actualizada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Memory $memory)
    {
        if ($request->user()->role != "admin") {
            return [
                'state' => false,
                'message' => 'Usuario no autorizado'
            ];
        }
        $memory->deleteImage();
        $memory->delete();
        return [
            'state' => true,
            'message' => 'Memoria eliminada'
        ];
    }
}
