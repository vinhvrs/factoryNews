<?php
namespace App\Repositories;

use App\Models\Accounts;
use App\Repositories\Interfaces\AccountInterface as AccountInterface;
use Ramsey\Uuid\Uuid;

class AccountRepository implements AccountInterface{
    public function __construct(){
        $this->accounts = [];
    }

    public function addAccount(array $account): Accounts{
        return Accounts::create([
            'uid' => $account['uid'] ?? Uuid::uuid4()->toString(),
            'username' => $account['username'],
            'password' => $account['password'],
            'role' => $account['role'],
            'email' => $account['email'],
            'name' => $account['name']
        ]);
    }

    public function checkAccount($username, $password){
        return Accounts::where('username', $username)
            ->where('password', $password)
            ->first();
    }

    public function getAccountByUid($uid){
        return Accounts::find($uid);
    }

    public function setRole($uid, $role){
        $account = Accounts::find($uid);
        if ($account) {
            $account->role = $role;
            $account->save();
            return $account;
        }
        return null;
    }

    public function getAccountByUsername($username){
        return Accounts::where('username', 'LIKE', "%{$username}%")->first();
    }

    public function getAccountByEmail($email){
        return Accounts::where('email', 'LIKE', "%{$email}%")->first();
    }

    public function getAllAccounts(){
        return Accounts::all();
    }

    public function updateAccount($uid, array $account): Accounts{
        $existingAccount = Accounts::find($uid);
        if ($existingAccount) {
            $existingAccount->username = $account['username'];
            $existingAccount->password = $account['password'];
            $existingAccount->role = $account['role'];
            $existingAccount->email = $account['email'];
            $existingAccount->name = $account['name'];
            $existingAccount->save();
            return $existingAccount;
        }
        return null;
    }

    public function deleteAccount($uid){
        $account = Accounts::find($uid);
        if ($account) {
            $account->delete();
            return true;
        }
        return false;
    }






}





?>