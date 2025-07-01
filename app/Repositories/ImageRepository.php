<?php
namespace App\Repositories;

use App\Models\Images;
use App\Repositories\Interfaces\ImageInterface as ImageInterface;

class ImageRepository implements ImageInterface {
    protected $limit = 10;

    public function __construct(){
        $this->image = new Images();
    }

    public function addImage(array $image): Images
    {
        return Images::create([
            'id' => uniqid(),
            'path' => $image['path'],
            'name' => $image['name'],
            'alt' => $image['alt'] ?? $image['name'] ?? null
        ]);
    }

    public function getImageName($id)
    {
        $image = Images::find($id);
        return $image ? $image->name : null;
    }

    public function getImage($id)
    {
        return Images::find($id);
    }

    public function deleteImage($id)
    {
        return Images::destroy($id);
    }
}


?>