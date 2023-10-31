<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Services\LikeService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShowLikeRequest;
use App\Http\Resources\LikeCollection;
use App\Http\Requests\CreateLikeRequest;

class LikeController extends Controller
{
    use ApiResponse;
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->middleware('auth');
        $this->likeService = $likeService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $likes = $user->likes->map(function ($like) {
            $like['product'] = $like->product;
            return $like;
        });
        return $this->successResponse('Likes recuperados correctamente.', $likes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLikeRequest $request)
    {
        try {
            $datos = $request->validated();
            $response = $this->likeService->toggleLike($request->validated(), Auth::user());
            return $this->successResponse($response);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse("Producto con ID {$datos['product_id']} no encontrado");
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error al guardar en la base de datos', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(ShowLikeRequest $request)
    {
        $datos = $request->validated();

        $user = User::find($datos['user_id']);
        $product = Product::find($datos['product_id']);
        if (!empty($product) && !empty($user)) {
            $like = $user->likes->firstWhere('product_id', $product->id);
            if (!empty($like)) {
                return ['like' => true];
            }
        }
        return ['like' => false];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Like $like)
    {
        //
    }
}
