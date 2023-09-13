<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Models\LandingImage;
use Illuminate\Http\Request;
use App\Services\LandingImageService;
use App\Http\Requests\StoreLandingRequest;
use App\Services\ImageService;

class LandingImageController extends Controller
{
    use ApiResponse;
    protected $landingImageService;

    public function __construct(LandingImageService $landingImageService)
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);
        $this->landingImageService = $landingImageService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'mobile' => LandingImage::where('type', LandingImage::TYPE_MOBILE)->first(),
            'tablet' => LandingImage::where('type', LandingImage::TYPE_TABLET)->first(),
            'desktop' => LandingImage::where('type', LandingImage::TYPE_DESKTOP)->first(),
        ];

        return $this->successResponse('Imagenes recuperadas correctamente.', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLandingRequest $request, ImageService $imageService)
    {
        $datos =  $request->validated();
        $size = LandingImage::SIZES[$datos['type']] ?? null;
        if (!$size) {
            return $this->errorResponse('Error al crear landing.', 'Dimensiones incorrectas');
        }

        $landing = LandingImage::where("type", $datos['type'])->first();

        if ($landing) {
            $imageService->deleteImage('landings', $landing->name);
            $name_image = $imageService->insertImage($size['w'], $size['h'], 'landings', $datos['image']);
            $this->landingImageService->updateLanding($landing->id, $datos, $name_image);
            return $this->successResponse('Landing actualizado correctamente.');
        }

        $name_image = $imageService->insertImage($size['w'], $size['h'], 'landings', $datos['image']);
        $new_landing = $this->landingImageService->createLandingImage($datos, $name_image);
        return $this->successResponse('Landing creado correctamente.', $new_landing, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LandingImage  $landingImage
     * @return \Illuminate\Http\Response
     */
    public function show(LandingImage $landingImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LandingImage  $landingImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LandingImage $landingImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LandingImage  $landingImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(LandingImage $landingImage)
    {
        //
    }
}



// $new_landing = new LandingImage;

            // $new_landing->name = $new_landing->saveImage($datos['images'], $w, $h);
            // $new_landing->type = $datos['type'];
            // $new_landing->save();