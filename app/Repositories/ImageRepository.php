<?php
namespace App\Repositories;

use App\Models\Images;
use App\Repositories\Interfaces\ImageInterface as ImageInterface;

class ImageRepository implements ImageInterface {
    protected $image;

    public function __construct(){
        $this->image = new Images();
    }

    public function addImage(array $image)
    {
        return $this->image->create([
            'imageId' => uniqid(),
            'newsId' => $image['newsId'],
            'imagePath' => $image['imagePath'],
            'imageName' => $image['imageName'] ?? null,
            'imageAlt' => $image['imageAlt'] ?? null
        ]);
        
    }

    public function getNameByImageId($imageId)
    {
        $image = $this->image->find($imageId);
        return $image ? $image->imageName : null;
    }

    public function getImagePathById($imageId)
    {
        $image = $this->image->find($imageId);
        return $image ? $image->imagePath : null;
    }

    public function getImageById($imageId)
    {
        return $this->image->find($imageId);
    }

    public function getImagesByNewsId($newsId)
    {
        return $this->image->where('newsId', $newsId)->get();
    }

    public function deleteImage($imageId)
    {
        return $this->image->destroy($imageId);
    }
}


?>