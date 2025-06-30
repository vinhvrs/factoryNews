<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountControllers\AccountController;
use App\Http\Controllers\AuthControllers\AuthController;


Route::get('/', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');



Route::fallback(function () {
    return view('app');
});