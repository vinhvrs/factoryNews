<?php
namespace App\Repositories\Interfaces;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewsInterface{
    public function create(array $news): News;
    public function find($id): News|null;
    public function findAll($filter, $select, $perPage): LengthAwarePaginator;
    public function update($id, array $news): News|null;
    public function delete($id): bool;
}





?>