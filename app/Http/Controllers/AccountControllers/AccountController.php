<?php
namespace App\Http\Controllers\AccountControllers;

use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Accounts;
use PHPUnit\Util\Json;


class AccountController extends Controller{

    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    //============== GET ACCOUNTS ==============

    public function getAccounts(): JsonResponse{
        $accounts = $this->accountRepository->getAccounts();
        if ($accounts->isEmpty()) {
            return response()->json(['message' => 'No accounts found'], 404);
        }
        return response()->json($accounts, 200);
    }

    public function getAccountName(Request $request): JsonResponse{
        $id = $request->input('id');
        if (!$id) {
            return response()->json(['message' => 'Account ID is required'], 400);
        }
        $name = $this->accountRepository->getAccountName($id);
        if (!$name) {
            return response()->json(['message' => 'Account not found'], 404);
        }
        return response()->json(['name' => $name], 200);
    }

    public function getAccount(Request $request): JsonResponse{
        $id = $request->query('id');
        $email = $request->query('email');
        $name = $request->query('name');

        if ($id) {
            $account = $this->accountRepository->getAccountById($id);

            if (!$account) {
                return response()->json(['message' => 'Account not found'], 404);
            }

            return response()->json($account, 200);
        } 
            
        if ($email) {
            $account = $this->accountRepository->getAccountByEmail($email);
            if (!$account) {
                return response()->json(['message' => 'Account not found'], 404);
            }
            return response()->json($account, 200);
        } 
        
        if ($name) {
            $account = $this->accountRepository->getAccountByName($name);
            if (!$account) {
                return response()->json(['message' => 'Account not found'], 404);
            }
            return response()->json($account, 200);
        } 

        return response()->json(['message' => 'Account not found'], 404);
    }

    //============== ADD ACCOUNT ==============

    public function addAccount(Request $request): JsonResponse{
        $request->input('role', 'reader');
        $data = $request->validate([
            'username' => 'required|string|unique:accounts,username',
            'password' => 'required|string|min:4',
            'role'     => 'nullable|string|in:reader,journalist,admin' ?? 'reader',
            'email'    => 'required|email|unique:accounts,email',
            'name'     => 'nullable|string',
        ]);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $account = $this->accountRepository->addAccount($data);

        if (!$account) {
            return response()->json(['message' => 'Account creation failed'], 500);
        }
        $account = [
            'id' => $account->id,
            'username' => $account->username,
            'role' => $account->role,
            'email' => $account->email,
            'name' => $account->name
        ];
        return response()->json($account, 201);
    }

    //============== UPDATE ACCOUNT ==============

    public function updateAccount(Request $request): JsonResponse{
        $data = $request->validate([
            'id'       => 'required|string|exists:accounts,id',
            'password' => 'sometimes|required|string|min:6',
            'email'    => 'sometimes|required|email|unique:accounts,email',
            'name'     => 'sometimes|nullable|string',
        ]);

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $account = $this->accountRepository->updateAccount($data);

        if (!$account) {
            return response()->json(['message' => 'Account update failed'], 500);
        }

        return response()->json($account, 200);
    }

    public function changeRole(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|exists:accounts,id',
            'role' => 'required|string|in:reader,journalist,admin'
        ]);

        $id = $data['id'];

        $account = $this->accountRepository->setRole($id, $data['role']);

        if (!$account) {
            return response()->json(['message' => 'Account not found or role update failed'], 404);
        }

        return response()->json($account, 200);
    }

    //============== DELETE ACCOUNT ==============

    public function deleteAccount(Request $request)
    {
        $id = $request->input('id');
        $deleted = $this->accountRepository->deleteAccount($id);
        if (!$deleted) {
            return response()->json(['message' => 'Account deletion failed'], 500);
        }
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }

}


?>