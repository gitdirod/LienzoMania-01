<?php

namespace App\Services;

use App\Models\LandingImage;

class LandingImageService
{
    public function createLandingImage($data, $name_image)
    {
        return LandingImage::create([
            'type' => (int)$data['type'],
            'name' => $name_image
        ]);
    }

    public function updateLanding($id, $data, $image_name)
    {
        $update_landing_image = LandingImage::find($id);
        if (!$update_landing_image) {
            throw new \Exception("Landing imagen con ID $id no encontrado.");
        }
        $update_landing_image->name = $image_name;
        $update_landing_image->type = (int)$data['type'];
        $update_landing_image->save();
        return $update_landing_image;
    }
}
