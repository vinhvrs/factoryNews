<?php
namespace App\Repositories\Interfaces;

use App\Models\Accounts;

interface AccountInterface {
    public function addAccount(array $account): Accounts;
    public function getAccountById($id);
    public function setRole($id, $role);
    public function getAccountByName($name);
    public function getAccountByEmail($email);
    public function getAccounts();
    public function updateAccount(array $account): Accounts;
    public function deleteAccount($id);
}


?>
