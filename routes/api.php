<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ImageController;

Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginController::class, 'test']);
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::post('/diff-iamges', [ImageController::class, 'compareImages']);
});
