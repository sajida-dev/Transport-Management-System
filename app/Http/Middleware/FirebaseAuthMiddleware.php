// <?php
// 
// namespace App\Http\Middleware;
// 
// use Closure;
// use Illuminate\Http\Request;
// use Kreait\Firebase\Factory;
// use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\Log;
// 
// class FirebaseAuthMiddleware
// {
//     protected $firebaseAuth;
// 
//     public function __construct()
//     {
//         // Path to the service account key file
//         $serviceAccountPath = base_path('google-services.json');
//         
//         // Initialize the Firebase Factory with the service account
//         $firebaseFactory = (new Factory)
//             ->withServiceAccount($serviceAccountPath);
// 
//         // Get the Auth instance from the factory
//         $this->firebaseAuth = $firebaseFactory->createAuth();
//     }
// 
//     public function handle(Request $request, Closure $next)
//     {
//          // Check if the request includes an Authorization header with a token
//          if (!$request->hasHeader('Authorization')) {
//             // No Authorization header found, return an error response
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }
// 
//         // Extract the token from the Authorization header
//         $token = $request->bearerToken();
// 
//         // Verify the token using Firebase Authentication
//         try {
//             $auth = app(Auth::class);
//             $verifiedToken = $auth->verifyIdToken($token);
//         } catch (\Exception $e) {
//             // Token verification failed, return an error response
//             return response()->json(['error' => 'Invalid token'], 401);
//         }
// 
//         // Token verification succeeded, extract user ID from the token
//         $userId = $verifiedToken->getClaim('sub');
// 
//         // Use the user ID to perform further actions or retrieve user data
//         // For example, fetch user data from the database or perform authorized actions
// 
//         // Return a success response indicating that the user is authenticated
//         return response()->json(['message' => 'User authenticated', 'user_id' => $userId]);
//     }
// }
