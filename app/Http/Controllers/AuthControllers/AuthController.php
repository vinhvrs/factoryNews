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
        $user = $this->accountRepository->getAccountByUsername($credentials['username']);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if (password_verify($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(): JsonResponse{
        return response()->json(['message' => 'Logout successful'], 200);
    }
}


?>