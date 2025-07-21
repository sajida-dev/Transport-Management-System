<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\FirebaseAuthMiddleware; // Assuming this is the intended auth middleware

// Public/Guest Routes
Route::get('/login', [AuthController::class, 'loginDisplay'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit']);
Route::any('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Authenticated Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard');

    // Bookings (Truck Bookings)
    Route::get('/bookings/{selection}', [DashboardController::class, 'bookings'])->name('bookings');
    Route::post('/bookings/{selection}', [DashboardController::class, 'bookings_submit'])->name('bookings.submit');
    Route::get('/bookings/{selection}/{booking_id}/detail', [DashboardController::class, 'bookingDetail'])->name('bookings.detail');

    // Profile
    Route::get('/profile', [DashboardController::class, 'profile_page'])->name('profile');

    // KYC Management
    Route::get('/kyc', [DashboardController::class, 'kyc'])->name('kyc');
    Route::get('/kyc/{id}', [DashboardController::class, 'kycDetail'])->name('kyc.detail');
    Route::post('/kyc/{id}/approve', [DashboardController::class, 'approveKyc'])->name('kyc.approve');
    Route::post('/kyc/{id}/reject', [DashboardController::class, 'rejectKyc'])->name('kyc.reject');

    // Load Bookings
    Route::get('/load-bookings/{selection}', [DashboardController::class, 'load_bookings'])->name('load_bookings');
    Route::get('/load-bookings/{selection}/{id}/detail', [DashboardController::class, 'loadBookingDetail'])->name('load_bookings.show');
    Route::post('/load-bookings/{selection}/{booking_id}', [DashboardController::class, 'loadApplicationsSubmit'])->name('load_bookings.submit');
    
    // Load Management
    Route::get('/loads/{selection?}', [DashboardController::class, 'loads'])->name('loads');
    Route::post('/loads/{selection}', [DashboardController::class, 'loadApprovalsSubmit'])->name('loads.submit');
    
    // Load Approvals
    Route::get('/load-approvals/{selection?}', [DashboardController::class, 'loadApprovals'])->name('loadApprovals');
    Route::get('/load-approvals/{selection}/{id}/detail', [DashboardController::class, 'loadDetail'])->name('loadApprovals.detail');
    Route::post('/load-approvals/{selection}/{load_id}/submit', [DashboardController::class, 'loadApprovalsSubmit'])->name('loadApprovals.submit');

    // Trucks
    Route::get('/trucks/{selection?}', [DashboardController::class, 'trucks'])->name('trucks');
    Route::get('/trucks/{id}/detail', [DashboardController::class, 'truckDetail'])->name('trucks.detail');
});


