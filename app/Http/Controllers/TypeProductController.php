<?php

namespace App\Http\Controllers;

use App\Models\TypeProduct;
use App\Traits\ApiResponse;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use App\Services\TypeProductService;
use App\Http\Resources\TypeProductCollection;
use App\Http\Requests\StoreTypeProductRequest;
use App\Http\Requests\UpdateTypeProductRequest;


class TypeProductController extends Controller
{
    use ApiResponse;
    protected $typeProductService;

    public function __construct(TypeProductService $typeProductService)
    {
        // Se aplica el middleware de autenticación a todos los métodos excepto 'index' y 'show'
        $this->middleware('auth')->except(['index', 'show']);

        // Asegura que el usuario puede realizar acciones de administrador solo para los métodos 'store' y 'update'
        $this->middleware('can:admin')->only(['store', 'update']);
        $this->typeProductService = $typeProductService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $typeProducts = new TypeProductCollection(TypeProduct::all());
        return $this->successResponse('Tipos de producto recuperado correctamente.', $typeProducts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeProductRequest $request, TypeProduct $typeProduct, ImageService $imageService)
    {
        try {
            $datos = $request->validated();
            $imageName = $imageService->insertImage(100, 100, 'iconos', $datos['image']);
            $this->typeProductService->createTypeProduct($datos, $imageName);
            return $this->successResponse('Tipo de producto creado.', $typeProduct, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al crear tipo de producto en la base de datos', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Error inesperado al crear tipo de producto', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeProduct  $typeProduct
     * @return \Illuminate\Http\Response
     */
    public function show(TypeProduct $typeProduct)
    {
        return [
            'id' => $typeProduct->id,
            'name'  => $typeProduct->name,
        ];
    }

    /**
     * Update the specified resource in storage.
     *s
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeProduct  $typeProduct
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateTypeProductRequest $request, TypeProduct $typeProduct, ImageService $imageService)
    {
        DB::beginTransaction();
        $name_image = null;
        try {
            $datos = $request->validated();

            // Si hay imagen, la procesamos y actualizamos la imagen y su nombre
            if (isset($datos['image'])) {
                $name_image = $imageService->handleTypeProductImage($typeProduct, $datos['image']);
            }

            $this->typeProductService->updateTypeProduct($typeProduct->id, $datos, $name_image);

            DB::commit();

            return $this->successResponse('Tipo actualizado');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar tipo de producto en la base de datos', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error inesperado al actualizar tipo de producto en la base de datos', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeProduct  $typeProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeProduct $typeProduct)
    {
        //
    }
}
