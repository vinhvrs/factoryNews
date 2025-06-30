<?php
namespace App\Repositories\Interfaces;
interface ImageInterface {
    public function addImage(array $image);
    public function getImageById($imageId);
    public function getImagesByNewsId($newsId);
    public function deleteImage($imageId);
}

?>