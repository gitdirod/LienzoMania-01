<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as ImageIntervention;
use Illuminate\Support\Facades\File;


class LandingImage extends Model
{
    use HasFactory;

    const TYPE_MOBILE = 0;
    const TYPE_TABLET = 1;
    const TYPE_DESKTOP = 2;

    const SIZES = [
        self::TYPE_MOBILE => ['w' => 600, 'h' => 1000],
        self::TYPE_TABLET => ['w' => 1200, 'h' => 1200],
        self::TYPE_DESKTOP => ['w' => 3200, 'h' => 1200]
    ];

    protected $fillable = [
        'type',
        'name'
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
            $image_path = public_path('landings') . '/' . $name_image;
            $image_server->save($image_path);

            return $name_image;
        }
    }
    public function deleteImage()
    {
        $path_file = "landings/" . $this->name;
        if (File::exists($path_file)) {
            File::delete($path_file);
            return true;
        }
        return false;
    }
}
