<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\PlayerProgressPage;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUserData'])->name('user');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/main', [MainPageController::class, 'index'])->name('main');
    Route::get('/progress', [PlayerProgressPage::class, 'index'])->name('progress');
    Route::get('/level/{id}', [LevelController::class, 'getLevelDataWithImages'])->name('level');

    Route::post('level/{id}/finish', [LevelController::class, 'finishLevel'])->name('finish.level');
});
