<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as ImageIntervention;
use Illuminate\Support\Facades\File;

class Memory extends Model
{
    use HasFactory;

    const IMAGE_WIDTH = 500;
    const IMAGE_HIGTH = 500;

    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    public function saveImage($images, $width, $high)
    {

        foreach ($images as $image) {

            $name_image = Str::uuid() . "." . $image->extension();
            $image_server = ImageIntervention::make($image);

            if ($image_server->width() > $image_server->height()) {
                $image_server->widen($width);
            } elseif ($image_server->height() > $image_server->width()) {
                $image_server->heighten($high);
            } else {
                $image_server->resize($width, $high);
            }
            $image_path = public_path('memories') . '/' . $name_image;
            $image_server->save($image_path);

            return $name_image;
        }
    }
    public function deleteImage()
    {
        $path_file = "memories/" . $this->image;
        if (File::exists($path_file)) {
            File::delete($path_file);
            return true;
        }
        return false;
    }
}
