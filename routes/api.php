<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsControllers\NewsController;
use App\Http\Controllers\ImageControllers\ImageController;
use App\Http\Controllers\AccountControllers\AccountController;
use App\Http\Controllers\AuthControllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AccountController::class, 'addAccount']);
});

Route::prefix('news')->group(function () {
    Route::get('/gets', [NewsController::class, 'getAllNews']);
    Route::get('/get', [NewsController::class, 'getNewsDetails']);
    Route::post('/add', [NewsController::class, 'addNews']);
    Route::put('/update', [NewsController::class, 'updateNews']);
    Route::delete('/delete', [NewsController::class, 'deleteNews']);
});

Route::prefix('images')->group(function () {
    Route::get('/get', [ImageController::class, 'getImage']);
    Route::post('/upload-temp-image', [ImageController::class, 'uploadTempImage']);
    Route::post('/temp-image-handle', [ImageController::class, 'tempImageHandle']);
    Route::delete('/delete', [ImageController::class, 'deleteImage']);
});

Route::prefix('accounts')->group(function () {
    Route::get('/gets', [AccountController::class, 'getAccounts']);
    Route::get('/get', [AccountController::class, 'getAccount']);
    Route::post('/add', [AccountController::class, 'addAccount']);
    Route::put('/change-role', [AccountController::class, 'changeRole']);
    Route::put('/change-info', [AccountController::class, 'updateAccount']);
    Route::delete('/delete', [AccountController::class, 'deleteAccount']);

});

?>