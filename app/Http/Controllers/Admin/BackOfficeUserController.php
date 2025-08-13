<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackOfficeUserController extends Controller
{
    /**
     * Display a listing of back office users
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::active()->get();
        $allRoles = Role::active()->get();
        $activeUsersCount = User::where('is_active', true)->count();
        return view('admin.backoffice-users.index', compact('users', 'roles', 'allRoles', 'activeUsersCount'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::active()->get();
        return view('admin.backoffice-users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'nullable|string|max:20',
                'password' => 'required|string|min:6|confirmed',
                'roles' => 'nullable|array',
                'roles.*' => 'exists:roles,id',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }

            DB::commit();

            return redirect()->route('admin.backoffice-users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An error occurred while creating the user. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);

            // Get activity logs using Eloquent
            $activityLogs = DB::table('activity_logs')
                ->where('user_id', $id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            // Get session logs using Eloquent
            $sessionLogs = DB::table('sessions')
                ->where('user_id', $id)
                ->orderBy('last_activity', 'desc')
                ->limit(50)
                ->get();

            $allRoles = Role::active()->get();

            // Check if this is an AJAX request for assign roles modal
            if (request()->ajax() && request()->has('modal')) {
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'roles' => $user->roles,
                    'allRoles' => $allRoles,
                    'message' => 'User data loaded successfully'
                ]);
            }

            return view('admin.backoffice-users.show', compact('user', 'activityLogs', 'sessionLogs', 'allRoles'));
        } catch (\Exception $e) {
            Log::error('Error fetching user details: ' . $e->getMessage(), ['exception' => $e]);

            // If it's an AJAX request, return JSON error
            if (request()->ajax() && request()->has('modal')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching user details: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.backoffice-users.index')
                ->with('error', 'Error fetching user details: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            $roles = Role::active()->get();
            return view('admin.backoffice-users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            Log::error('Error fetching user for edit: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.backoffice-users.index')
                ->with('error', 'Error fetching user details: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone_number' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
                'roles' => 'nullable|array',
                'roles.*' => 'exists:roles,id',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $user = User::findOrFail($id);
            $updateData = [
                'name' => $request->name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Handle roles assignment
            $submittedRoles = $request->input('roles', []);

            // If roles is null or not an array, convert to empty array
            if (!is_array($submittedRoles)) {
                $submittedRoles = [];
            }

            // Filter out empty values and ensure we have valid role IDs
            $submittedRoles = array_filter($submittedRoles, function ($roleId) {
                return !empty($roleId) && is_numeric($roleId);
            });

            // Log the role update operation
            Log::info('User update - role assignment', [
                'user_id' => $id,
                'submitted_roles' => $submittedRoles,
                'current_roles' => $user->roles->pluck('id')->toArray()
            ]);

            // Sync the roles
            // $user->roles()->sync($submittedRoles);
            $user->roles()->detach();
            $user->roles()->attach($submittedRoles);


            DB::commit();

            return redirect()->route('admin.backoffice-users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An error occurred while updating the user. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Check if user is trying to delete themselves
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            $user->roles()->detach();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.backoffice-users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User deletion failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the user. Please try again.');
        }
    }

    /**
     * Assign roles to a user
     */
    public function assignRoles(Request $request, $id)
    {
        // dd($request);
        try {
            $validator = Validator::make($request->all(), [
                'roles' => 'nullable|array',
                'roles.*' => 'exists:roles,id',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator);
            }

            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Get the submitted roles array, ensure it's always an array
            $submittedRoles = $request->input('roles', []);

            // If roles is null or not an array, convert to empty array
            if (!is_array($submittedRoles)) {
                $submittedRoles = [];
            }

            // Filter out empty values and ensure we have valid role IDs
            $submittedRoles = array_filter($submittedRoles, function ($roleId) {
                return !empty($roleId) && is_numeric($roleId);
            });

            // Log the operation for debugging
            Log::info('Role assignment operation', [
                'user_id' => $id,
                'user_email' => $user->email,
                'submitted_roles' => $submittedRoles,
                'current_roles' => $user->roles->pluck('id')->toArray(),
                'request_all' => $request->all()
            ]);

            $user->roles()->detach();
            $user->roles()->attach($submittedRoles);

            // Refresh the user to get updated roles
            $user->refresh();

            DB::commit();

            $message = empty($submittedRoles) ? 'All roles removed successfully.' : 'Roles assigned successfully.';

            Log::info('Role assignment completed', [
                'user_id' => $id,
                'final_roles' => $user->roles->pluck('id')->toArray(),
                'message' => $message
            ]);

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'user_id' => $id,
                    'roles' => $user->roles,
                    'updated_roles' => $submittedRoles
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role assignment failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id,
                'request_data' => $request->all()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while assigning roles. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'An error occurred while assigning roles. Please try again.');
        }
    }

    /**
     * Remove a specific role from a user
     */
    public function removeRole(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->roles()->detach($request->role_id);

            DB::commit();

            return redirect()->back()->with('success', 'Role removed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role removal failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An error occurred while removing the role. Please try again.');
        }
    }

    /**
     * View login activity logs for a user
     */
    public function activityLogs($id)
    {
        try {
            $user = User::findOrFail($id);

            $activityLogs = DB::table('activity_logs')
                ->where('user_id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.backoffice-users.activity-logs', compact('user', 'activityLogs'));
        } catch (\Exception $e) {
            Log::error('Error fetching activity logs: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Error fetching activity logs: ' . $e->getMessage());
        }
    }

    /**
     * View session logs for a user
     */
    // public function sessionLogs($id)
    // {
    //     try {
    //         $user = User::findOrFail($id);

    //         $sessionLogs = DB::table('sessions')
    //             ->where('user_id', $id)
    //             ->orderBy('last_activity', 'desc')
    //             ->paginate(20);

    //         return view('admin.backoffice-users.session-logs', compact('user', 'sessionLogs'));
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching session logs: ' . $e->getMessage(), ['exception' => $e]);
    //         return redirect()->back()
    //             ->with('error', 'Error fetching session logs: ' . $e->getMessage());
    //     }
    // }

    public function sessionLogs($id)
    {
        try {
            $user = User::findOrFail($id);

            $sessions = DB::table('sessions')
                ->where('user_id', $id)
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) {
                    return (object) [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                        'is_current_device' => $session->id === session()->getId(),
                    ];
                });

            // Optional: paginate manually if needed
            // Or you can convert the collection to LengthAwarePaginator for pagination

            return view('admin.backoffice-users.session-logs', compact('user', 'sessions'));
        } catch (\Exception $e) {
            Log::error('Error fetching session logs: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Error fetching session logs: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Check if user is trying to deactivate themselves
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot deactivate your own account.');
            }

            $user->update(['is_active' => !$user->is_active]);

            DB::commit();

            $status = $user->is_active ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "User {$status} successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User status toggle failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An error occurred while updating user status. Please try again.');
        }
    }

    /**
     * Bulk assign roles to multiple users
     */
    public function bulkAssignRoles(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            DB::beginTransaction();

            $users = User::whereIn('id', $request->user_ids)->get();

            foreach ($users as $user) {
                $user->roles()->sync($request->roles);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Roles assigned successfully to ' . count($users) . ' users.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk role assignment failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An error occurred while assigning roles. Please try again.');
        }
    }
}
