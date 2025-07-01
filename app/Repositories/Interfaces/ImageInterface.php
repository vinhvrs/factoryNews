<?php
namespace App\Repositories\Interfaces;
interface ImageInterface {
    public function addImage(array $image);
    public function getImage($id);
    public function deleteImage($id);
}

?>