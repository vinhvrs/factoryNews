<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsControllers\NewsController;
use App\Http\Controllers\ImageControllers\ImageController;
use App\Http\Controllers\AccountControllers\AccountController;
use App\Http\Controllers\AuthControllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/user', [AuthController::class, 'getUser']);
//     Route::post('/logout', [AuthController::class, 'logout']);
// });

Route::prefix('accounts')->group(function () {
    Route::get('/', [AccountController::class, 'index']);
    Route::post('/', [AccountController::class, 'store']);

    Route::prefix('/{id}')->group(function () {
        Route::get('/', [AccountController::class, 'show']);
        Route::put('/', [AccountController::class, 'update']);
        Route::put('/role', [AccountController::class, 'updateRole']);
        Route::delete('/', [AccountController::class, 'destroy']);
    });
});

Route::prefix('news')->group(function(){
    Route::get('/', [NewsController::class, 'index']);
    Route::post('/', [NewsController::class, 'store']);

    Route::prefix('/{id}')->group(function () {
        Route::get('/', [NewsController::class, 'show']);
        Route::put('/', [NewsController::class, 'update']);
        Route::delete('/', [NewsController::class, 'delete']);
    });
});

Route::prefix('images')->group(function () {
    Route::get('/', [ImageController::class, 'index']);
    Route::post('/', [ImageController::class, 'uploadImage']);
    Route::put('/', [ImageController::class, 'saveImage']);

    Route::prefix('/{id}')->group(function (){
        Route::get('/', [ImageController::class, 'index']);
        Route::delete('/', [ImageController::class, 'destroy']);
    });
    
});

?>