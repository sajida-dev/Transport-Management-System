<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseAuthService;

class FirebaseAuthController extends Controller
{
    //     protected $firebaseAuth;
    //     private $validApiToken = "caf3ad55-8476-4746-b78b-ca54afd586d7"; // Define the valid API token
    // 
    //     public function __construct(FirebaseAuthService $firebaseAuth)
    //     {
    //         $this->firebaseAuth = $firebaseAuth;
    //     }
    // 
    //     public function resetPassword(Request $request)
    //     {
    //         // Verify API token first
    //         $apiToken = $request->header('api_token');
    //         
    //         if (!$apiToken || $apiToken !== $this->validApiToken) {
    //             return response()->json([
    //                 'error' => 'Unauthorized: Invalid or missing API token'
    //             ], 401);
    //         }
    // 
    //         // If token is valid, proceed with validation and reset
    //         $request->validate([
    //             'email' => 'required|email',
    //             'newPassword' => 'required|min:6',
    //         ]);
    // 
    //         try {
    //             $response = $this->firebaseAuth->resetPassword($request->email, $request->newPassword);
    //             
    //             if (isset($response['error'])) {
    //                 return response()->json(['error' => $response['error']], 500);
    //             }
    // 
    //             return response()->json(['message' => $response['message']], 200);
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'error' => 'An error occurred: ' . $e->getMessage()
    //             ], 500);
    //         }
    //     }
}
