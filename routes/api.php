<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\PlayerProgressPage;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUserData']);
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/main', [MainPageController::class, 'index']);
    Route::get('/progress', [PlayerProgressPage::class, 'index']);
});
