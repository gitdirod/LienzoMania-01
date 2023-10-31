<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductPrice;
use App\Traits\ApiResponse;

Cache::flush();

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicIndex()
    {
        $products = new ProductCollection(Product::orderBy('id', 'DESC')->get());
        return $this->successResponse('Productos recuperados', $products);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProductCollection(Product::orderBy('id', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $user = $request->user();
        if ($user->role != "admin") {
            return [
                'message' => "Usuario NO autorizado",
                'state' => false
            ];
        }
        // Validar el registro
        $datos = $request->validated();

        $product = Product::create([
            "name" => $datos["name"],
            "code" => $datos["code"],
            "category_id" => $datos["category"],
            "type_product_id" => $datos["type"],
            "description" => $datos["description"],
            "available" => $datos["available"],
            "units" => 0,
            "sold" => 0,
            "purchased" => 0,
            "weight" => $datos["weight"],
            "size" => $datos["size"],
            "number_color" => $datos["number_color"],
            "user" => $user
        ]);
        // ProductPrice::create([
        //     'product_id' => $product->id,
        //     'price' => $datos["price"],
        // ]);
        $product->insertImages($datos);
        return [
            'message' => "Producto creado",
            'state' => true
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $category = Category::find($product->category_id);
        $group = $category->group()->get();
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'description' => $product->description,
            'units' => $product->units,
            'code' => $product->code,
            'weight' => $product->weight,
            'size' => $product->size,
            'new' => "dd",
            'number_color' => $product->number_color,
            'available' => $product->available,
            'category' => $product->category()->get(),
            'type' => $product->type()->get(),
            'category_id' => $category->id,
            'group' => $group,
            'images' => $product->images()->select('id', 'name')->get()
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $user = $request->user();
        if ($user->role != "admin") {
            return [
                'message' => "Usuario NO autorizado",
                'state' => false
            ];
        }

        $datos = $request->validated();


        if (isset($datos['images'])) {
            $product->insertImages($datos);
        }

        $imgs_stored = $product->images()->get();
        if (isset($datos['deleted'])) {

            if (count($imgs_stored) > count($datos['deleted'])) {

                $product->deleteImages($datos['deleted']);
            } else {
                return response()->json(array(
                    'message' => "El producto debe tener al menos una imagen.",
                    'errors' => [
                        'images' => "El producto debe tener al menos una imagen."
                    ]
                ), 422);
            }
        }
        $product->updateProduct($datos);
        return [
            'message' => "Producto actualizado",
            'state' => true
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
