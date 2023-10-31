<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Category;
use App\Models\TypeProduct;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use App\Models\SoldOrderPaymentImage;
use Intervention\Image\Facades\Image as ImageIntervention;
use App\Models\CategoryImage; // Si estás utilizando este modelo para guardar imágenes

class ImageService
{



    public function insertImages($w, $h, $path, $category, array $datos)
    {
        foreach ($datos['images'] as $image) {
            $name_image = Str::uuid() . "." . $image->extension();
            $image_server = ImageIntervention::make($image);
            $image_server->resize($w, $h);
            $image_path = public_path($path) . '/' . $name_image;
            $image_server->save($image_path);

            CategoryImage::create([
                'category_id' => $category->id,
                'name' => $name_image,
            ]);
        }
    }

    public function deleteImages($path, Collection $datos)
    {
        if (count($datos)) {
            foreach ($datos as $item) {

                $path_file = $path . '/' .  $item->name;
                if (File::exists($path_file)) {
                    File::delete($path_file);
                }
                $toDelete = CategoryImage::find($item->id);
                $toDelete->delete();
            }
        }
    }

    public function handleCategoryImages(Category $category, array $datos)
    {
        $imgs_stored = $category->images()->select('name', 'id')->get();
        $this->deleteImages("categories", $imgs_stored);
        $this->insertImages(300, 300, 'categories', $category, $datos);
    }

    public function insertImage($w, $h, $path, $image)
    {
        $name_image = Str::uuid() . "." . $image->extension();
        $image_server = ImageIntervention::make($image);

        if ($image_server->width() > $image_server->height()) {
            $image_server->widen($w);
        } elseif ($image_server->height() > $image_server->width()) {
            $image_server->heighten($h);
        } else {
            $image_server->resize($w, $h);
        }

        $image_path = public_path($path) . '/' . $name_image;
        $image_server->save($image_path);

        return $name_image;
    }
    public function insertImagePayment($image, $order_id, $user_id, $w = SoldOrderPaymentImage::IMAGE_WIDTH, $h = SoldOrderPaymentImage::IMAGE_HEIGTH)
    {
        $name_image = Str::uuid() . "." . $image->extension();
        $image_server = ImageIntervention::make($image);

        if ($image_server->width() > $image_server->height()) {
            $image_server->widen($w);
        } elseif ($image_server->height() > $image_server->width()) {
            $image_server->heighten($h);
        } else {
            $image_server->resize($w, $h);
        }

        $image_path = public_path(SoldOrderPaymentImage::IMAGE_PATH) . $name_image;
        $image_server->save($image_path);

        SoldOrderPaymentImage::create([
            'user_id' => $user_id,
            'sold_order_id' => $order_id,
            'name' => $name_image
        ]);

        return $name_image;
    }

    public function deleteImage($path, $img)
    {
        if (isset($img)) {
            $path_file = $path . "/" . $img;
            if (File::exists($path_file)) {
                File::delete($path_file);
            }
        }
    }
    public function handleTypeProductImage(TypeProduct $typeProduct, $img)
    {
        $imgs_stored = $typeProduct->image;
        $this->deleteImage("iconos", $imgs_stored);
        $image_name = $this->insertImage(100, 100, 'iconos', $img);
        return $image_name;
    }
}
