<?php
namespace App\Http\Controllers\AccountControllers;

use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;
use App\Models\Accounts;


class AccountController extends Controller{

    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getAllAccounts()
    {
        $accounts = $this->accountRepository->getAllAccounts();
        if ($accounts->isEmpty()) {
            return response()->json(['message' => 'No accounts found'], 404);
        }
        return response()->json($accounts, 200);
    }


    public function addAccount(Request $request){
        $data = $request->validate([
            'username' => 'required|string|unique:accounts,username',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|in:reader,journalist,admin',
            'email'    => 'required|email|unique:accounts,email',
            'name'     => 'nullable|string',
        ]);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $account = $this->accountRepository->addAccount($data);

        if (!$account) {
            return response()->json(['message' => 'Account creation failed'], 500);
        }
        $account = [
            'uid' => $account->uid,
            'username' => $account->username,
            'role' => $account->role,
            'email' => $account->email,
            'name' => $account->name
        ];
        return response()->json($account, 201);
    }

    public function getAccount($uid){
        $account = $this->accountRepository->getAccountByUid($uid);
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }
        return response()->json($account, 200);
    }

    public function getAccountByUsername(Request $request){
        $username = $request->input('username');
        $account = $this->accountRepository->getAccountByUsername($username);
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }
        return response()->json($account, 200);
    }

    public function getAccountByEmail($email){
        $account = $this->accountRepository->getAccountByEmail(($email));
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }
        return response()->json($account, 200);
    }

    public function changeRole(Request $request, $uid)
    {
        $account = $this->accountRepository->getAccountByUid($uid);
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $data = $request->validate([
            'role' => 'required|string|in:reader,journalist,admin',
        ]);

        $account = $this->accountRepository->updateAccount($uid, [
            'uid' => $account->uid,
            'username' => $account->username,
            'password' => $account->password,
            'role' => $data['role'],
            'email' => $account->email,
            'name' => $account->name
        ]);

        if (!$account) {
            return response()->json(['message' => 'Role update failed'], status: 500);
        }

        return response()->json($account, 200);
    }

    public function updateAccount(Request $request, $uid)
    {
        $account = $this->accountRepository->getAccountByUid($uid);
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }   

        $data = $request->validate([
            'password' => 'sometimes|required|string|min:6',
            'role'     => 'sometimes|required|string|in:reader,journalist,admin',
            'email'    => 'sometimes|required|email|unique:accounts,email',
            'name'     => 'sometimes|nullable|string',
        ]);

        $data['username'] = $account->username; // Keep the existing username
        $data['uid'] = $account->uid; // Keep the existing UID

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $account = $this->accountRepository->updateAccount($uid, $data);

        if (!$account) {
            return response()->json(['message' => 'Account update failed'], 500);
        }

        return response()->json($account, 200);
    }

    public function deleteAccount($uid)
    {
        $deleted = $this->accountRepository->deleteAccount($uid);
        if (!$deleted) {
            return response()->json(['message' => 'Account deletion failed'], 500);
        }
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }

}


?>