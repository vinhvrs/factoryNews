<?php
namespace App\Repositories\Interfaces;

use App\Models\Accounts;
use Illuminate\Pagination\LengthAwarePaginator;

interface AccountInterface {
    public function create(array $account): Accounts;
    public function find($id, array $select): Accounts|null;
    public function findAll(array $filters, array $select, int $perPage): LengthAwarePaginator;

    public function update(array $account): Accounts;
    public function delete($id): bool;
}


?>
