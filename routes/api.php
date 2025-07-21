<?php 

use App\Http\Controllers\FirebaseAuthController;

Route::post('/reset-password', [FirebaseAuthController::class, 'resetPassword']);
