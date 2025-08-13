<?php

use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\TransporterController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\BackOfficeUserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\KYCController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\LoadOwnerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransporterTruckController;
use App\Http\Controllers\TripLogController;

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

    // Driver management routes
    Route::resource('drivers', DriverController::class, ['as' => 'admin']);
    Route::put('/drivers/{driver}/status', [DriverController::class, 'updateStatus'])->name('admin.drivers.update-status');
    Route::put('/drivers/{driver}/assign-truck', [DriverController::class, 'assignToTruck'])->name('admin.drivers.assign-truck');

    // Transporter management routes
    Route::resource('transporters', TransporterController::class, ['as' => 'admin']);
    Route::post('/transporters/{transporter}/suspend', [TransporterController::class, 'suspend'])->name('admin.transporters.suspend');
    Route::post('/transporters/{transporter}/reactivate', [TransporterController::class, 'reactivate'])->name('admin.transporters.reactivate');
    Route::resource('transporters.trucks', TransporterTruckController::class)->names('admin.transporters.trucks');
    Route::get('/transporters/trucks/all', [TransporterTruckController::class, 'allTrucks'])->name('admin.transporters.trucks');


    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'clearAll'])->name('admin.notifications.clear-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('admin.notifications.unread-count');
});


// backoffice user management routes

Route::prefix('backoffice-users')
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('admin.backoffice-users.')
    ->group(function () {
        // Resource routes for standard CRUD: index, create, store, show, edit, update, destroy
        Route::resource('', BackOfficeUserController::class)->parameters(['' => 'id']);

        // Custom routes outside of resource
        Route::post('/{id}/assign-roles', [BackOfficeUserController::class, 'assignRoles'])->name('assign-roles');
        Route::post('/{id}/remove-role', [BackOfficeUserController::class, 'removeRole'])->name('remove-role');
        Route::get('/{id}/activity-logs', [BackOfficeUserController::class, 'activityLogs'])->name('activity-logs');
        Route::get('/{id}/session-logs', [BackOfficeUserController::class, 'sessionLogs'])->name('session-logs');
        Route::post('/{id}/toggle-status', [BackOfficeUserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-assign-roles', [BackOfficeUserController::class, 'bulkAssignRoles'])->name('bulk-assign-roles');
    });



// Role management routes
Route::prefix('roles')
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('admin.roles.')
    ->group(function () {
        // Resource for standard CRUD
        Route::resource('', RoleController::class)->parameters(['' => 'id']);

        // Keep custom routes
        Route::post('/{id}/assign-roles', [RoleController::class, 'assignRoles'])->name('assign-roles');
        Route::post('/{id}/remove-role', [RoleController::class, 'removeRole'])->name('remove-role');
        Route::get('/{id}/activity-logs', [RoleController::class, 'activityLogs'])->name('activity-logs');
        Route::get('/{id}/session-logs', [RoleController::class, 'sessionLogs'])->name('session-logs');
        Route::post('/{id}/toggle-status', [RoleController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-assign-roles', [RoleController::class, 'bulkAssignRoles'])->name('bulk-assign-roles');
    });

// Permission management routes
Route::prefix('permissions')
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('admin.permissions.')
    ->group(function () {
        // Resource for standard CRUD
        Route::resource('', PermissionController::class)->parameters(['' => 'id']);

        // Keep custom routes
        Route::post('/{id}/assign-roles', [PermissionController::class, 'assignRoles'])->name('assign-roles');
        Route::post('/{id}/remove-role', [PermissionController::class, 'removeRole'])->name('remove-role');
        Route::get('/{id}/activity-logs', [PermissionController::class, 'activityLogs'])->name('activity-logs');
        Route::get('/{id}/session-logs', [PermissionController::class, 'sessionLogs'])->name('session-logs');
        Route::post('/{id}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-assign-roles', [PermissionController::class, 'bulkAssignRoles'])->name('bulk-assign-roles');
    });

// Load management routes
Route::middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    Route::resource('load-owners', LoadOwnerController::class)->names('admin.load_owners');
    Route::patch('load-owners/{load_owner}/toggle-status', [LoadOwnerController::class, 'toggleStatus'])->name('admin.load_owners.toggleStatus');


    Route::resource('loads', LoadController::class)->names('admin.loads');
    // status
    Route::post('/loads/{load}/status', [LoadController::class, 'updateStatus'])->name('admin.loads.update-status');
    Route::post('/loads/{load}/assign-driver', [LoadController::class, 'assignDriver'])->name('admin.loads.assign-driver');
    Route::post('/loads/{load}/assign', [LoadController::class, 'assign'])->name('admin.loads.assign');
    Route::get('/loads/match/{load}', [LoadController::class, 'matchSuggestions'])->name('admin.loads.match-suggestions');
});

Route::prefix('bookings')->group(function () {
    Route::resource('/', BookingController::class)->names('admin.bookings');
    Route::post('/{booking}/assign', [BookingController::class, 'assign'])->name('admin.bookings.assign');
    Route::get('/{booking}/timeline', [BookingController::class, 'timeline'])->name('admin.bookings.timeline');
    Route::post('/{booking}/upload-pod', [BookingController::class, 'uploadProof'])->name('admin.bookings.upload-pod');
    Route::get('/today', [BookingController::class, 'today'])->name('admin.bookings.today');
});

Route::prefix('reports')->group(function () {
    Route::get('/operations', [ReportController::class, 'operations'])->name('admin.reports.operations');
    Route::get('/finance', [ReportController::class, 'finance'])->name('admin.reports.finance');
    Route::get('/export/{type}', [ReportController::class, 'export'])->name('admin.reports.export');
});


Route::prefix('kyc')->group(function () {
    Route::get('/', [KYCController::class, 'index'])->name('admin.kyc.index');
    Route::get('/{id}', [KYCController::class, 'show'])->name('admin.kyc.show');
    Route::post('/{id}/approve', [KYCController::class, 'approve'])->name('admin.kyc.approve');
    Route::post('/{id}/reject', [KYCController::class, 'reject'])->name('admin.kyc.reject');
    Route::get('/expiries', [KYCController::class, 'expiringDocuments'])->name('admin.kyc.expiries');
});



Route::prefix('finance')->group(function () {
    Route::get('/invoices', [FinanceController::class, 'invoices'])->name('admin.finance.invoices');
    Route::get('/invoices/{id}', [FinanceController::class, 'invoiceDetail'])->name('admin.finance.invoice-detail');
    Route::get('/payments', [FinanceController::class, 'payments'])->name('admin.finance.payments');
    Route::get('/export', [FinanceController::class, 'export'])->name('admin.finance.export');
});


Route::prefix('trips')->group(function () {
    Route::get('/', [TripLogController::class, 'index'])->name('admin.trips.index');
    Route::get('/{id}', [TripLogController::class, 'show'])->name('admin.trips.show');
    Route::post('/log', [TripLogController::class, 'store'])->name('admin.trips.store'); // via GPS API
    // live trips
    Route::get('/live/{id}', [TripLogController::class, 'live'])->name('admin.trips.active');
    // trip history
    Route::get('/history', [TripLogController::class, 'history'])->name('admin.trips.history');
    Route::get('/history/{id}', [TripLogController::class, 'historyDetail'])->name('admin.trips.history-detail');
});


Route::prefix('activity-logs')->group(
    function () {
        Route::get('', [ActivityLogController::class, 'index'])->name('admin.logs.index');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('admin.logs.export');
    }
);
