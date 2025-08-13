
<?php

use App\Http\Controllers\Admin\DriverController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverDocumentController;
use App\Http\Controllers\DriverAssignmentController;
use App\Http\Controllers\DriverLogController;
use App\Http\Controllers\DriverTripController;

//
// DRIVER MANAGEMENT
//

// Driver CRUD
Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
Route::get('/drivers/{driver}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('drivers.update');
Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');

//
// KYC DOCUMENT MANAGEMENT
//

Route::get('/drivers/{driver}/documents', [DriverDocumentController::class, 'index'])->name('drivers.documents.index');
Route::post('/drivers/{driver}/documents', [DriverDocumentController::class, 'store'])->name('drivers.documents.store');
Route::delete('/drivers/{driver}/documents/{document}', [DriverDocumentController::class, 'destroy'])->name('drivers.documents.destroy');

//
//  DRIVER-TRUCK ASSIGNMENTS
//

Route::get('/drivers/{driver}/assign', [DriverAssignmentController::class, 'create'])->name('drivers.assign.create');
Route::post('/drivers/{driver}/assign', [DriverAssignmentController::class, 'store'])->name('drivers.assign.store');
Route::delete('/assignments/{assignment}', [DriverAssignmentController::class, 'destroy'])->name('drivers.assign.destroy');

//
//  LOGS & TRIPS
//

Route::get('/drivers/{driver}/logs', [DriverLogController::class, 'index'])->name('drivers.logs.index');
Route::get('/drivers/{driver}/active-trips', [DriverTripController::class, 'active'])->name('drivers.trips.active');
Route::get('/drivers/{driver}/trip-history', [DriverTripController::class, 'history'])->name('drivers.trips.history');
