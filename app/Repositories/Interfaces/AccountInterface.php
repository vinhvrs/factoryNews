<?php
namespace App\Repositories\Interfaces;

use App\Models\Accounts;

interface AccountInterface {
    public function addAccount(array $account): Accounts;
    public function getAccountByUid($uid);
    public function setRole($uid, $role);
    public function getAccountByUsername($username);
    public function getAccountByEmail($email);
    public function getAllAccounts();
    public function updateAccount($uid, array $account): Accounts;
    public function deleteAccount($uid);
}


?>
