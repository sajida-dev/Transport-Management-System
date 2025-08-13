@extends('admin.layouts.app')

@section('title', 'Back Office User Management')

@section('content')
<div class="w-full px-1 py-8">
    <div class="w-full">
        <!-- Page Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Back Office User Management</h1>
                <p class="mt-2 text-sm text-gray-700">Manage team members, assign roles, and view activity logs</p>
            </div>
            <div class="mt-4 sm:mt-0">
                @can('user.create')
                <a href="{{ route('admin.backoffice-users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    Add User
                </a>
                @endcan
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-2xl text-indigo-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $users->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-shield text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $activeUsersCount }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Roles</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $roles->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Online Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $users->where('last_login_at', '>=', now()->subMinutes(5))->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md w-full">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Back Office Users</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage team members and their roles</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-indigo-600">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if($user->full_name !== $user->name)
                                                <div class="text-sm text-gray-500">{{ $user->full_name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    @if($user->phone_number)
                                        <div class="text-sm text-gray-500">{{ $user->phone_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $role->display_name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500">No roles assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->last_login_at)
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $user->last_login_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">Never</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @can('user.view')
                                        <a href="{{ route('admin.backoffice-users.show', $user->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-indigo-600 hover:text-indigo-900" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endcan
                                        @can('user.edit')
                                        <a href="{{ route('admin.backoffice-users.edit', $user->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-yellow-600 hover:text-yellow-900" 
                                           title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('user.logs.view')
                                        <a href="{{ route('admin.backoffice-users.activity-logs', $user->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-blue-600 hover:text-blue-900" 
                                           title="Activity Logs">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        @endcan
                                        @can('user.session.view')
                                        <a href="{{ route('admin.backoffice-users.session-logs', $user->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-purple-600 hover:text-purple-900" 
                                           title="Session Logs">
                                            <i class="fas fa-clock"></i>
                                        </a>
                                        @endcan
                                        @can('user.assign_roles')
                                        <button onclick="openAssignRolesModal({{ $user->id }})" 
                                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-green-600 hover:text-green-900" 
                                                title="Assign Roles">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        @endcan
                                       
                                        <!-- Toggle status and delete actions -->
                                        @if($user->id !== auth()->id())
                                         @can('user.toggle_status')
                                            <form action="{{ route('admin.backoffice-users.toggle-status', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-blue-600 hover:text-blue-900" 
                                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                </button>
                                            </form>
                                            @endcan
                                            @can('user.delete')
                                            <form action="{{ route('admin.backoffice-users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-red-600 hover:text-red-900" 
                                                        title="Delete User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Assign Roles Modal (one global, populated dynamically) -->
<div id="assignRolesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Assign Roles</h2>
            <button type="button" onclick="closeAssignRolesModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form id="assignRolesForm" method="POST">
            @csrf
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Select one or more roles to assign to this user. Roles control what actions and data the user can access.</p>
                <div class="flex flex-wrap gap-2" id="assignRolesCheckboxes">
                    <!-- Populated by JS -->
                </div>
                <!-- Hidden input to ensure empty array is sent when no roles are selected -->
                <input type="hidden" name="roles[]" value="">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAssignRolesModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Assign Roles</button>
            </div>
        </form>
    </div>
</div>
<!-- Role Management Script -->
<script src="/js/role-management.js"></script>
<script>

    // Initialize role manager with available roles
    const allRolesData = @json($allRoles ?? []);
    
    // Override the global openAssignRolesModal function
    window.openAssignRolesModal = function(userId) {
        // Show loading state
        document.getElementById('assignRolesCheckboxes').innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
        document.getElementById('assignRolesModal').classList.remove('hidden');
        
        // Fetch user roles via AJAX
        fetch(`/admin/backoffice-users/${userId}?modal=true`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received user data:', data);
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to load user data');
                }
                
                const userRoles = data.roles || [];
                const allRoles = data.allRoles || allRolesData;
                
                if (!allRoles || allRoles.length === 0) {
                    throw new Error('No roles available');
                }
                
                // Initialize role manager
                window.roleManager.init(userId, userRoles, allRoles);
                
                // Create role checkboxes
                window.roleManager.createRoleCheckboxes('assignRolesCheckboxes', function(roleId, isChecked, currentRoles) {
                    console.log(`Role ${roleId} ${isChecked ? 'added' : 'removed'}. Current roles:`, currentRoles);
                });
                
                // Set up form submission
                const form = document.getElementById('assignRolesForm');
                form.action = `/admin/backoffice-users/${userId}/assign-roles`;
                
                // Handle form submission with role manager
                window.roleManager.handleFormSubmission(form, form.action, document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            })
            .catch(error => {
                console.error('Error fetching user roles:', error);
                document.getElementById('assignRolesCheckboxes').innerHTML = '<div class="text-center py-4 text-red-600">Error loading roles. Please try again.</div>';
                window.roleManager.showNotification("Error loading user roles. Please try again.", 'error');
            });
    };
    
    // Override the global closeAssignRolesModal function
    window.closeAssignRolesModal = function() {
        document.getElementById('assignRolesModal').classList.add('hidden');
    };
</script>
@endsection