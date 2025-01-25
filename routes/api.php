<?php

use App\Http\Controllers\DailyRewardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\UserProgressPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/images/count', [ImageController::class, 'count'])->name('images.count');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/load_user_data', [UserController::class, 'loadUserData'])->name('load_user');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/main', [MainPageController::class, 'index'])->name('main');
    Route::get('/progress', [UserProgressPageController::class, 'index'])->name('progress');
    Route::get('/level/{level}', [LevelController::class, 'getLevelDataWithImages'])->name('level');

    Route::post('/level/{level}/win', [LevelController::class, 'winLevel'])->name('win.level');
    Route::post('/level/{level}/loss', [LevelController::class, 'lossLevel'])->name('loss.level');

    Route::post('/daily_reward/{day}/claim', [DailyRewardController::class, 'claimReward'])->name('daily_reward.claim');
});
