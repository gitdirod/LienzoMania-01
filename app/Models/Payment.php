<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as ImageIntervention;

class Payment extends Model
{
    use HasFactory;
    const IMAGE_WIDTH = 700;
    const IMAGE_HEIGTH = 700;

    protected $fillable = [
        'user_id',
        'order_id',
        'name'
    ];

    public function insertImages(array $datos)
    {
        $user = Auth::user();
        foreach ($datos['images'] as $image) {
            $name_image = Str::uuid() . "." . $image->extension();
            $image_server = ImageIntervention::make($image);
            if ($image_server->width() > $image_server->height()) {
                $image_server->widen(700);
            } elseif ($image_server->height() > $image_server->width()) {
                $image_server->heighten(700);
            } else {
                $image_server->resize(700, 700);
            }
            $image_path = public_path('payments') . '/' . $name_image;
            $image_server->save($image_path);

            $pay = Payment::create([
                'order_id' => $datos['order_id'],
                'user_id' => $user->id,
                'name' => $name_image
            ]);
            return $pay;
        }
    }
}
