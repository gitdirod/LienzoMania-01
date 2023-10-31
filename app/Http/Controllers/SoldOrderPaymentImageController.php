<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use App\Models\SoldOrderPaymentImage;
use App\Services\SoldOrderPaymentImageService;
use App\Http\Requests\StoreSoldOrderPaymentImageRequest;
use Illuminate\Support\Facades\File;

class SoldOrderPaymentImageController extends Controller
{
    use ApiResponse;
    protected $SoldOrderPaymentImageService;
    protected $imageService;

    public function __construct(SoldOrderPaymentImageService $SoldOrderPaymentImageService, ImageService $imageService)
    {
        $this->middleware('auth');
        $this->middleware('can:admin')->only(['destroy']);

        $this->SoldOrderPaymentImageService = $SoldOrderPaymentImageService;
        $this->imageService = $imageService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSoldOrderPaymentImageRequest $request)
    {
        $user = Auth::user();
        $datos = $request->validated();
        $this->imageService->insertImagePayment($datos['image'], $datos['sold_order_id'], $user->id);
        return $this->successResponse('Imagen ingresada correctamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SoldOrderPaymentImage  $soldOrderPaymentImage
     * @return \Illuminate\Http\Response
     */
    public function show(SoldOrderPaymentImage $soldOrderPaymentImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoldOrderPaymentImage  $soldOrderPaymentImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SoldOrderPaymentImage $soldOrderPaymentImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoldOrderPaymentImage  $soldOrderPaymentImage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soldOrderPaymentImage = SoldOrderPaymentImage::findOrFail($id);

        $path_file = SoldOrderPaymentImage::IMAGE_PATH . $soldOrderPaymentImage->name;

        if (File::exists($path_file)) {
            File::delete($path_file);
        }
        $soldOrderPaymentImage->delete();
        return $this->successResponse('Comprobante eliminado', 200);
    }
}
