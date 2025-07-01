<?php
namespace App\Repositories;

use App\Models\Accounts;
use App\Repositories\Interfaces\AccountInterface as AccountInterface;
use Ramsey\Uuid\Uuid;

class AccountRepository implements AccountInterface{
    public int $limit = 10;

    public function __construct(){
        $this->accounts = [];
    }

    public function addAccount(array $account): Accounts{
        return Accounts::create([
            'id' => $account['id'] ?? Uuid::uuid4()->toString(),
            'username' => $account['username'],
            'password' => $account['password'],
            'role' => $account['role'] ?? 'reader',
            'email' => $account['email'],
            'name' => $account['name']
        ]);
    }

    public function checkAccount($username, $password){
        return Accounts::where('username', $username)
            ->where('password', $password)
            ->first();
    }

    public function getAccountById($id){
        return Accounts::where('id', $id)
            ->first();
    }

    public function setRole($id, $role){
        $account = Accounts::find($id);
        if ($account) {
            $account->role = $role;
            $account->save();
            return $account;
        }
        return null;
    }

    public function getAccountByUsername($username){
        return Accounts::where('username', $username)
            ->first();
    }

    public function getAccountByName($username){
        return Accounts::where('username', 'LIKE', "%{$username}%")
            ->orderBy('username', 'asc')
            ->paginate($this->limit)
            ->withQueryString();
    }

    public function getAccountName($id){
        $account = Accounts::find($id);
        return $account ? $account->name : null;
    }

    public function getAccountByEmail($email){
        return Accounts::where('email', 'LIKE', "%{$email}%")
            ->limit($this->limit)
            ->orderBy('email', 'asc')
            ->first();
    }

    public function getAccounts(){
        return Accounts::query()
            ->orderBy('username', 'asc')
            ->paginate($this->limit)
            ->withQueryString();
    }

    public function updateAccount(array $account): Accounts{
        $existingAccount = Accounts::find($account['id']);
        if ($existingAccount) {
            $existingAccount->username = $account['username'] ?? $existingAccount->username;
            $existingAccount->password = $account['password'] ?? $existingAccount->password;
            $existingAccount->role = $account['role'] ?? $existingAccount->role;
            $existingAccount->email = $account['email'] ?? $existingAccount->email;
            $existingAccount->name = $account['name'] ?? $existingAccount->name;
            $existingAccount->save();
            return $existingAccount;
        }
        return null;
    }

    public function deleteAccount($id){
        $account = Accounts::find($id);
        if ($account) {
            $account->delete();
            return true;
        }
        return false;
    }
}

?>