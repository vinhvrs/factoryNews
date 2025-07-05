<?php
namespace App\Repositories\Interfaces;
interface ImageInterface {
    public function create(array $image);
    public function get($id, $name, $path);
    public function delete($id);
}

?>