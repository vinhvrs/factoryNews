<?php
namespace App\Repositories;

use App\Models\Accounts;
use App\Repositories\Interfaces\AccountInterface as AccountInterface;
use Ramsey\Uuid\Uuid;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountRepository implements AccountInterface{
    public int $limit = 10;

    public function __construct(){
        $this->accounts = [];
    }

    public function create(array $account): Accounts{
        return Accounts::create([
            'id' => $account['id'] ?? Uuid::uuid4()->toString(),
            'username' => $account['username'],
            'password' => $account['password'],
            'role' => $account['role'] ?? 'reader',
            'email' => $account['email'],
            'name' => $account['name']
        ]);
    }

    public function login($username, $password): Accounts|bool{
        $account = Accounts::where('username', $username)
            ->select(['id', 'username', 'role', 'name', 'email', 'password'])
            ->first();

        if ($account && password_verify($password, $account->password)) {
            $account->password = null;
            $account->email = null;
            return $account;
        }

        return false;
    }

    public function find($id, array $select): Accounts|null{
        $query = Accounts::query();

        $query->select($select)
            ->where('id', $id);
        return $query->first();
    }

    public function updateRole($id, $role): Accounts|null{
        $account = Accounts::find($id);
        if ($account) {
            $account->role = $role;
            $account->save();
            return $account;
        }
        return null;
    }

    public function findAll(array $filters, array $select, int $perPage): LengthAwarePaginator{
        $query = Accounts::query();

        foreach ($filters as $column => $value){
            if (!empty($value)) {
                $query->where($column, 'LIKE', "%{$value}%");
            }
        }

        $query->select($select)
            ->orderBy('username', 'asc');
        return $query->paginate($perPage)
            ->withQueryString();
    }

    public function update(array $account): Accounts{
        $existingAccount = Accounts::find($account['id']);
        if ($existingAccount) {
            $existingAccount->username = $account['username'] ?? $existingAccount->username;
            $existingAccount->password = $account['password'] ?? $existingAccount->password;
            $existingAccount->role = $account['role'] ?? $existingAccount->role;
            $existingAccount->email = $account['email'] ?? $existingAccount->email;
            $existingAccount->name = $account['name'] ?? $existingAccount->name;
            $existingAccount->save();
        }
        return $existingAccount;
    }

    public function delete($id): bool{
        $account = Accounts::find($id);
        if ($account) {
            $account->delete();
            return true;
        }
        return false;
    }
}

?>