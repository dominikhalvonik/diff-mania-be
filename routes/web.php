<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoosterController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');


Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {


    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');

    Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUserAttributes'])->name('admin.edit_user_attributes');
    Route::put('/admin/users/{user}/update', [AdminController::class, 'updateUserAttributes'])->name('admin.update_user_attributes');
    Route::get('/admin/users/{user}/boosters/edit', [AdminController::class, 'editUserBoosters'])->name('admin.edit_user_boosters');
    Route::put('/admin/users/{user}/boosters/update', [AdminController::class, 'updateUserBoosters'])->name('admin.update_user_boosters');

    Route::get('/admin/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.ban_user');
    Route::post('/admin/users/{user}/ban', [AdminController::class, 'storeBan'])->name('admin.store_ban');
    Route::delete('/admin/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('admin.unban_user');
    Route::get('/admin/banned-users', [AdminController::class, 'bannedUsers'])->name('admin.banned_users');

    Route::post('/booster/{booster}/add', [BoosterController::class, 'addBooster'])->name('booster.add');
});
