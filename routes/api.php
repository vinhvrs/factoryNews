<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsControllers\NewsController;
use App\Http\Controllers\ImageControllers\ImageController;
use App\Http\Controllers\AccountControllers\AccountController;
use App\Http\Controllers\AuthControllers\AuthController;

Route::get('/accounts/getAll', [AccountController::class, 'getAllAccounts']);
Route::get('/accounts/get-user-by-name', [AccountController::class, 'getAccountByUsername']);
Route::post('/accounts/add', [AccountController::class, 'addAccount']);
Route::get('/accounts/get-user/{uid}', [AccountController::class, 'getAccount']);
Route::get('/accounts/get-user-by-email/{email}', [AccountController::class, 'getAccountByEmail']);
Route::put('/accounts/get-user/change-info/{uid}', [AccountController::class, 'updateAccount']);
Route::put('/accounts/get-user/change-role/{uid}', [AccountController::class, 'changeRole']);
Route::delete('/accounts/get-user/{uid}/delete', [AccountController::class, 'deleteAccount']);

Route::get('/news/getAll', [NewsController::class, 'getAllNews']);
Route::post('/news/add', [NewsController::class, 'addNews']);
Route::get('/news/get/{newsId}', [NewsController::class, 'getNews']);
Route::put('/news/update/{newsId}', [NewsController::class, 'updateNews']);
Route::delete('/news/delete/{newsId}', [NewsController::class, 'deleteNews']);
Route::get('/news/get-by-title', [NewsController::class, 'getNewsByTitle']);
Route::get('/news/get-by-date', [NewsController::class, 'getNewsByDate']);
Route::get('/news/get-by-author', [NewsController::class, 'getNewsByAuthor']);

Route::post('/images/upload-temp-image', [ImageController::class, 'uploadTempImage']);
Route::post('/images/temp-image-handle', [ImageController::class, 'tempImageHandle']);
Route::post('/images/upload-images/{newsId}', [ImageController::class, 'uploadImages']);
Route::get('/images/get/{imageId}', [ImageController::class, 'getImage']);
Route::get('/images/get-by-news/{newsId}', [ImageController::class, 'getImagesId']);
Route::delete('/images/delete/{imageId}', [ImageController::class, 'deleteImage']);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AccountController::class, 'addAccount']);

?>