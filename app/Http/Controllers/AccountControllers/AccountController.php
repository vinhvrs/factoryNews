<?php
namespace App\Http\Controllers\AccountControllers;

use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    //============== GET ACCOUNTS ==============

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => 'sometimes|string',
            'email' => 'sometimes|string',
            'name' => 'sometimes|string',
            'field' => 'sometimes|string',
            'order' => 'sometimes|string|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100',
        ]);

        $filters = [
            'username' => $data['username'] ?? null,
            'email' => $data['email'] ?? null,
            'name' => $data['name'] ?? null,
        ];

        $rawFields = $data['field'] ?? '';
        $parts = array_filter(explode(',', $rawFields),
                            fn($f) => !empty($f));
        $allowed = ['id', 'username', 'email', 'name', 'role', 'created_at'];
        $select = array_intersect($allowed, $parts);

        //return response()->json($select);

        if (empty($select)) {
            $select = ['*'];
        }

        $perPage = $data['perPage'] ?? 10;

        $paginator = $this->accountRepository->findAll(
            $filters, 
            $select, 
            $perPage
        );

        if ($paginator->isEmpty()) {
            return response()->json(['message' => 'No accounts found'], 404);
        }

        return response()->json($paginator, 200);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $data = $request->validate([
            'field' => 'sometimes|string',
        ]);
        $fields = $data['field'] ?? '';
        $allowed = ['username', 'name', 'email', 'role'];
        $parts = array_filter(explode(',', $fields), fn($f) => !empty($f));
        $select = array_intersect($allowed, $parts);
        if (empty($select)) {
            $select = ['*'];
        }

        $account = $this->accountRepository->find($id, $select);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        return response()->json($account, 200);
    }

    //============== ADD ACCOUNT ==============

    public function store(Request $request): JsonResponse
    {
        $request->input('role', 'reader');
        $data = $request->validate([
            'username' => 'required|string|unique:accounts,username',
            'password' => 'required|string|min:4',
            'role' => 'sometimes|string|in:reader,journalist,admin',
            'email' => 'sometimes|string|unique:accounts,email',
            'name' => 'sometimes|string',
        ]);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $account = $this->accountRepository->create($data);

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

    public function update(Request $request): JsonResponse
    {
        $id = $request->route('id');

        if (!$id) {
            return response()->json(['message' => 'Account ID is required'], 400);
        }

        $data = $request->validate([
            'password' => 'sometimes|required|string|min:6',
            'email' => 'sometimes|required|email|unique:accounts,email',
            'name' => 'sometimes|nullable|string',
        ]);

        $data['id'] = $id;

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $account = $this->accountRepository->update($data);

        if (!$account) {
            return response()->json(['message' => 'Account update failed'], 500);
        }

        return response()->json($account, 200);
    }

    public function updateRole(Request $request)
    {
        $id = $request->route('id');

        $data = $request->validate([
            'role' => 'required|string|in:reader,journalist,admin'
        ]);

        $account = $this->accountRepository->updateRole($id, $data['role']);

        if (!$account) {
            return response()->json(['message' => 'Account not found or role update failed'], 404);
        }

        return response()->json($account, 200);
    }

    //============== DELETE ACCOUNT ==============

    public function destroy(Request $request)
    {
        $id = $request->route('id');
        $deleted = $this->accountRepository->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Account deletion failed'], 500);
        }
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }

}


?>