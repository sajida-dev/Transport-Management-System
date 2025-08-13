<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Log;

class FirebaseAuthService
{
    //     protected $auth;
    // 
    //     public function __construct()
    //     {
    //         $factory = (new Factory)->withServiceAccount(base_path('google-services.json'));
    //         $this->auth = $factory->createAuth();
    //     }
    // 
    //     // Method to reset password
    //     public function resetPassword($email, $newPassword)
    //     {
    //         try {
    //             $user = $this->auth->getUserByEmail($email);
    //             $this->auth->changeUserPassword($user->uid, $newPassword);
    //             return ['message' => 'Password reset successfully'];
    //         } catch (\Exception $e) {
    //             Log::error('Password reset error: ' . $e->getMessage());
    //             return ['error' => $e->getMessage()];
    //         }
    //     }
}
