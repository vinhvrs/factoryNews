<?php
namespace App\Repositories\Interfaces;
interface ImageInterface {
    public function create(array $image);
    public function get($id, array $select);
    public function getAll(array $filters, array $select, int $perPage);
    public function delete($id);
}

?>