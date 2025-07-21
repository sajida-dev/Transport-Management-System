<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseAuthService;
use Exception;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Kreait\Firebase\Factory;

class UserController extends Controller
{
    protected $firestore;
    protected $firebaseAuth;

    public function __construct()
    {
        try {
            $this->firestore = new FirestoreClient([
                'projectId' => env('FIREBASE_PROJECT_ID', 'loadmasta-eb28e'),
            ]);
            
            // Initialize Firebase Auth
            $factory = (new Factory)->withServiceAccount(base_path('google-services.json'));
            $this->firebaseAuth = $factory->createAuth();
        } catch (Exception $e) {
            report($e);
            $this->firestore = null;
            $this->firebaseAuth = null;
        }
    }

    /**
     * Display the specified user's details.
     *
     * @param  string  $uid
     * @return \Illuminate\View\View
     */
    public function show($uid)
    {
        try {
            if (!$this->firestore) {
                return redirect()->route('admin.dashboard')->with('error', 'Firebase connection error.');
            }

            // Get user data from Firestore
            $user = $this->firestore->collection('users')->document($uid)->snapshot();
            
            if (!$user->exists()) {
                return redirect()->route('admin.dashboard')->with('error', 'User not found.');
            }
            
            $userData = $user->data();
            $userData['uid'] = $uid;
            
            // Get additional data based on user type
            $additionalData = [];
            
            if (isset($userData['user_type'])) {
                if ($userData['user_type'] === 'Driver') {
                    // Get transporter's trucks
                    $trucks = $this->firestore->collection('trucks')
                        ->where('user_id', '==', $uid)
                        ->documents();
                        
                    $additionalData['trucks'] = [];
                    foreach ($trucks as $truck) {
                        $truckData = $truck->data();
                        $truckData['id'] = $truck->id();
                        $additionalData['trucks'][] = $truckData;
                    }
                } elseif ($userData['user_type'] === 'Customer') {
                    // Get load owner's loads
                    $loads = $this->firestore->collection('loads')
                        ->where('user_id', '==', $uid)
                        ->documents();
                        
                    $additionalData['loads'] = [];
                    foreach ($loads as $load) {
                        $loadData = $load->data();
                        $loadData['id'] = $load->id();
                        $additionalData['loads'][] = $loadData;
                    }
                }
            }
            
            return view('admin.users.show', [
                'user' => $userData,
                'additionalData' => $additionalData
            ]);
        } catch (Exception $e) {
            report($e);
            return redirect()->route('admin.dashboard')
                ->with('error', 'Error fetching user details: ' . $e->getMessage());
        }
    }

    /**
     * Update user details.
     *
     * @param  Request  $request
     * @param  string  $uid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $uid)
    {
        try {
            if (!$this->firestore) {
                return redirect()->back()->with('error', 'Firebase connection error.');
            }

            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone_number' => 'required|string|max:20',
                'address' => 'nullable|string|max:500',
                'city_town' => 'nullable|string|max:255',
                'province' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'nrc' => 'nullable|string|max:50',
                'gender' => 'nullable|string|in:Male,Female,Other'
            ]);

            $userRef = $this->firestore->collection('users')->document($uid);
            $user = $userRef->snapshot();

            if (!$user->exists()) {
                return redirect()->back()->with('error', 'User not found.');
            }

            // Format update data with path and value keys for Firestore
            $updateData = [
                ['path' => 'first_name', 'value' => $request->first_name],
                ['path' => 'last_name', 'value' => $request->last_name],
                ['path' => 'email', 'value' => $request->email],
                ['path' => 'phone_number', 'value' => $request->phone_number],
                ['path' => 'updated_at', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
            ];

            // Add optional fields only if they have values
            if ($request->filled('address')) {
                $updateData[] = ['path' => 'address', 'value' => $request->address];
            }
            if ($request->filled('city_town')) {
                $updateData[] = ['path' => 'city_town', 'value' => $request->city_town];
            }
            if ($request->filled('province')) {
                $updateData[] = ['path' => 'province', 'value' => $request->province];
            }
            if ($request->filled('country')) {
                $updateData[] = ['path' => 'country', 'value' => $request->country];
            }
            if ($request->filled('nrc')) {
                $updateData[] = ['path' => 'nrc', 'value' => $request->nrc];
            }
            if ($request->filled('gender')) {
                $updateData[] = ['path' => 'gender', 'value' => $request->gender];
            }

            $userRef->update($updateData);

            \Log::info('User updated successfully', [
                'user_id' => $uid,
                'updated_by' => auth()->user()->email ?? 'admin',
                'updated_fields' => array_column($updateData, 'path')
            ]);

            return redirect()->route('admin.users.show', $uid)->with('success', 'User details updated successfully.');

        } catch (Exception $e) {
            \Log::error('Error updating user', [
                'user_id' => $uid,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    /**
     * Verify user account.
     *
     * @param  string  $uid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($uid)
    {
        try {
            if (!$this->firestore) {
                return redirect()->back()->with('error', 'Firebase connection error.');
            }

            $userRef = $this->firestore->collection('users')->document($uid);
            $user = $userRef->snapshot();

            if (!$user->exists()) {
                return redirect()->back()->with('error', 'User not found.');
            }

            $userData = $user->data();

            // Format update data with path and value keys for Firestore
            $updateData = [
                ['path' => 'driver_verified', 'value' => true],
                ['path' => 'verified_at', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())],
                ['path' => 'verified_by', 'value' => auth()->user()->email ?? 'admin'],
                ['path' => 'updated_at', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
            ];

            $userRef->update($updateData);

            \Log::info('User verified successfully', [
                'user_id' => $uid,
                'user_name' => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
                'verified_by' => auth()->user()->email ?? 'admin'
            ]);

            return redirect()->back()->with('success', 'User has been verified successfully.');

        } catch (Exception $e) {
            \Log::error('Error verifying user', [
                'user_id' => $uid,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error verifying user: ' . $e->getMessage());
        }
    }

    /**
     * Suspend user account.
     *
     * @param  Request  $request
     * @param  string  $uid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspend(Request $request, $uid)
    {
        try {
            if (!$this->firestore) {
                return redirect()->back()->with('error', 'Firebase connection error.');
            }

            $request->validate([
                'ban_reason' => 'required|string|max:500'
            ]);

            $userRef = $this->firestore->collection('users')->document($uid);
            $user = $userRef->snapshot();

            if (!$user->exists()) {
                return redirect()->back()->with('error', 'User not found.');
            }

            $userData = $user->data();

            // Format update data with path and value keys for Firestore
            $updateData = [
                ['path' => 'isBanned', 'value' => true],
                ['path' => 'banReason', 'value' => $request->ban_reason],
                ['path' => 'banned_at', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())],
                ['path' => 'banned_by', 'value' => auth()->user()->email ?? 'admin'],
                ['path' => 'updated_at', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
            ];

            $userRef->update($updateData);

            \Log::info('User suspended successfully', [
                'user_id' => $uid,
                'user_name' => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
                'ban_reason' => $request->ban_reason,
                'banned_by' => auth()->user()->email ?? 'admin'
            ]);

            return redirect()->back()->with('success', 'User has been suspended successfully.');

        } catch (Exception $e) {
            \Log::error('Error suspending user', [
                'user_id' => $uid,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error suspending user: ' . $e->getMessage());
        }
    }

    /**
     * Unban user account.
     *
     * @param  string  $uid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unban($uid)
    {
        try {
            if (!$this->firestore) {
                return redirect()->back()->with('error', 'Firebase connection error.');
            }

            $userRef = $this->firestore->collection('users')->document($uid);
            $user = $userRef->snapshot();

            if (!$user->exists()) {
                return redirect()->back()->with('error', 'User not found.');
            }

            $userData = $user->data();

            // Check if user is actually banned
            if (!($userData['isBanned'] ?? false)) {
                return redirect()->back()->with('warning', 'User is not currently banned.');
            }

            // Format update data with path and value keys for Firestore
            $updateData = [
                ['path' => 'isBanned', 'value' => false],
                ['path' => 'banReason', 'value' => null],
                ['path' => 'unbanned_at', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())],
                ['path' => 'unbanned_by', 'value' => auth()->user()->email ?? 'admin'],
                ['path' => 'updated_at', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
            ];

            $userRef->update($updateData);

            \Log::info('User unbanned successfully', [
                'user_id' => $uid,
                'user_name' => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
                'unbanned_by' => auth()->user()->email ?? 'admin'
            ]);

            return redirect()->back()->with('success', 'User has been unbanned successfully.');

        } catch (Exception $e) {
            \Log::error('Error unbanning user', [
                'user_id' => $uid,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error unbanning user: ' . $e->getMessage());
        }
    }

    /**
     * Display a list of load owners.
     */
    public function loadOwners(Request $request): View
    {
        try {
            $perPage = 10; // Number of items per page
            $currentPage = $request->query('page', 1);
            $startAt = ($currentPage - 1) * $perPage;

            $usersRef = $this->firestore->collection('users');
            $loadOwnersQuery = $usersRef->where('user_type', '==', 'Customer');
            
            // Get total count for pagination
            $total = $loadOwnersQuery->documents()->size();
            
            // Get paginated results
            $loadOwnersQuery = $loadOwnersQuery->offset($startAt)->limit($perPage);
            $loadOwners = $loadOwnersQuery->documents();

            $loadOwnersData = [];
            foreach ($loadOwners as $doc) {
                $data = $doc->data();
                $data['uid'] = $doc->id(); // Changed from 'id' to 'uid' to match your view
                $loadOwnersData[] = (object) $data;
            }

            // Create a custom paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $loadOwnersData,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.users.load_owners', [
                'loadOwners' => $paginator
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.users.load_owners', [
                'loadOwners' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1),
                'error' => 'Failed to fetch load owners data.'
            ]);
        }
    }

    /**
     * Display a list of transporters.
     */
    public function transporters(Request $request): View
    {
        try {
            $perPage = 10; // Number of items per page
            $currentPage = $request->query('page', 1);
            $startAt = ($currentPage - 1) * $perPage;

            $usersRef = $this->firestore->collection('users');
            $transportersQuery = $usersRef->where('user_type', '==', 'Driver');
            
            // Get total count for pagination
            $total = $transportersQuery->documents()->size();
            
            // Get paginated results
            $transportersQuery = $transportersQuery->offset($startAt)->limit($perPage);
            $transporters = $transportersQuery->documents();

            $transportersData = [];
            foreach ($transporters as $doc) {
                $data = $doc->data();
                $data['uid'] = $doc->id(); // Changed from 'id' to 'uid' to match your view
                $transportersData[] = (object) $data;
            }

            // Create a custom paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $transportersData,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.users.transporters', [
                'transporters' => $paginator
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.users.transporters', [
                'transporters' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1),
                'error' => 'Failed to fetch transporters data.'
            ]);
        }
    }

    /**
     * Display a list of all users.
     */
    public function index(Request $request): View
    {
        try {
            $perPage = 15; // Number of items per page
            $currentPage = $request->query('page', 1);
            $startAt = ($currentPage - 1) * $perPage;

            $usersRef = $this->firestore->collection('users');
            
            // Get total count for pagination
            $total = $usersRef->documents()->size();
            
            // Get paginated results
            $usersQuery = $usersRef->offset($startAt)->limit($perPage);
            $users = $usersQuery->documents();

            $usersData = [];
            foreach ($users as $doc) {
                $data = $doc->data();
                $data['uid'] = $doc->id();
                $usersData[] = (object) $data;
            }

            // Create a custom paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $usersData,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.users.index', [
                'users' => $paginator
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.users.index', [
                'users' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1),
                'error' => 'Failed to fetch users data.'
            ]);
        }
    }

    /**
     * Delete user from both Firebase Auth and Firestore.
     *
     * @param  string  $uid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($uid)
    {
        try {
            if (!$this->firestore || !$this->firebaseAuth) {
                return response()->json([
                    'success' => false,
                    'message' => 'Firebase connection error.'
                ], 500);
            }

            // Get user data before deletion for logging
            $userDoc = $this->firestore->collection('users')->document($uid)->snapshot();
            $userData = $userDoc->exists() ? $userDoc->data() : null;
            $userName = $userData ? ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? '') : 'Unknown User';

            // Step 1: Delete user from Firebase Auth
            try {
                $this->firebaseAuth->deleteUser($uid);
                \Log::info('User deleted from Firebase Auth', [
                    'user_id' => $uid,
                    'user_name' => $userName,
                    'deleted_by' => auth()->user()->email ?? 'admin'
                ]);
            } catch (Exception $authError) {
                \Log::warning('Failed to delete user from Firebase Auth (user may not exist in Auth)', [
                    'user_id' => $uid,
                    'error' => $authError->getMessage()
                ]);
                // Continue with Firestore deletion even if Auth deletion fails
            }

            // Step 2: Delete user from Firestore users collection
            if ($userDoc->exists()) {
                $this->firestore->collection('users')->document($uid)->delete();
                \Log::info('User deleted from Firestore users collection', [
                    'user_id' => $uid,
                    'user_name' => $userName
                ]);
            }

            // Step 3: Delete related data based on user type
            if ($userData && isset($userData['user_type'])) {
                if ($userData['user_type'] === 'Driver') {
                    // Delete user's trucks
                    $trucks = $this->firestore->collection('trucks')
                        ->where('user_id', '==', $uid)
                        ->documents();
                    
                    foreach ($trucks as $truck) {
                        $truck->reference()->delete();
                    }
                    
                    \Log::info('Deleted trucks for user', ['user_id' => $uid]);
                } elseif ($userData['user_type'] === 'Customer') {
                    // Delete user's loads
                    $loads = $this->firestore->collection('loads')
                        ->where('user_id', '==', $uid)
                        ->documents();
                    
                    foreach ($loads as $load) {
                        $load->reference()->delete();
                    }
                    
                    \Log::info('Deleted loads for user', ['user_id' => $uid]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully from all systems.'
            ]);

        } catch (Exception $e) {
            \Log::error('Error deleting user', [
                'user_id' => $uid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }
}