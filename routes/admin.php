<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group with admin authentication.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'home'])->name('admin.dashboard');
    
    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/load-owners', [UserController::class, 'loadOwners'])->name('admin.users.load_owners');
    Route::get('/users/transporters', [UserController::class, 'transporters'])->name('admin.users.transporters');
    Route::get('/users/{uid}', [UserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{uid}/edit', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{uid}/verify', [UserController::class, 'verify'])->name('admin.users.verify');
    Route::post('/users/{uid}/suspend', [UserController::class, 'suspend'])->name('admin.users.suspend');
    Route::post('/users/{uid}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
    Route::delete('/users/{uid}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});