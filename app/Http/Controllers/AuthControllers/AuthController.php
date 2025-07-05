<?php
namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Accounts;

class AuthController extends Controller{
    protected AccountRepository $accountRepository;
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function login(Request $request): JsonResponse{
        $credentials = $request->validate([
            "username" => "required|string",
            "password" => "required|string"
        ]);
        $user = $this->accountRepository->login($credentials['username'], 
                                                $credentials['password']);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 204);
        }

        return response()->json(['message' => 'Login successful', 'user' => $user], 200);
    }

    public function register(Request $request): JsonResponse{
        $data = $request->validate([
            'username' => 'required|string|unique:accounts,username',
            'password' => 'required|string|min:4',
            'email' => 'required|email|unique:accounts,email',
            'name' => 'nullable|string',
        ]);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $account = $this->accountRepository->create($data);

        if (!$account) {
            return response()->json(['message' => 'Account creation failed'], 500);
        }

        return response()->json(['message' => 'Account created successfully'], 201);
    }

    public function logout(): JsonResponse{
        return response()->json(['message' => 'Logout successful'], 200);
    }
}


?>