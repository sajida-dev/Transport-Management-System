<?php

use App\Http\Controllers\FirebaseAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/reset-password', [FirebaseAuthController::class, 'resetPassword']);
