@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="w-full px-4 py-8">
    <div class="w-full">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                            <p class="mt-2 text-sm text-gray-600">View user information, roles, and activity logs</p>
                        </div>
                    </div>
            <div class="flex justify-end items-center">
                <div class="flex space-x-2">
                     <a href="{{ route('admin.backoffice-users.index') }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back
                        </a>
                        @can('user.edit')
                    <a href="{{ route('admin.backoffice-users.edit', $user->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-edit mr-2"></i>
                        Edit 
                    </a>
                    @endcan
                    @can('user.assign_roles')
                    <button type="button" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            onclick="document.getElementById('assignRolesModal').classList.remove('hidden')">
                        <i class="fas fa-user-tag mr-2"></i>
                        Assign Roles
                    </button>
                    @endcan
                    @can('user.logs.view')
                    <a href="{{ route('admin.backoffice-users.activity-logs', $user->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-history mr-2"></i>
                        Activity Logs
                    </a>
                    @endcan
                    @can('user.session.view')
                    <a href="{{ route('admin.backoffice-users.session-logs', $user->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-clock mr-2"></i>
                        Session Logs
                    </a>
                    @endcan
                    @can('user.toggle-status')
                    <form action="{{ route('admin.backoffice-users.toggle-status', $user->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i>
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    @endcan
                    @can('user.delete')
                    <form action="{{ route('admin.backoffice-users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                @endcan
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Full Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">First Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->first_name ?: 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Last Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->last_name ?: 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->phone_number ?: 'Not provided' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Status</label>
                    <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Last Login</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i:s') : 'Never' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i:s') }}</p>
                </div>
            </div>
        </div>

        <!-- User Roles -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Assigned Roles</h3>
            
            @if($user->roles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($user->roles as $role)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $role->display_name }}</h4>
                                @if($role->description)
                                    <p class="text-sm text-gray-500">{{ $role->description }}</p>
                                @endif
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No roles assigned to this user.</p>
            @endif
        </div>

        <!-- Tabs for Activity and Session Logs -->
        <div class="bg-white shadow rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="switchTab('activity')" id="tab-activity" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button active">
                        Activity Logs
                    </button>
                    <button onclick="switchTab('sessions')" id="tab-sessions" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-button">
                        Session Logs
                    </button>
                </nav>
            </div>

            <!-- Activity Logs Tab -->
            <div id="tab-content-activity" class="tab-content active p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Logs</h3>
                
                @if($activityLogs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($activityLogs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $log->log_name ?: 'General' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $log->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No activity logs found for this user.</p>
                @endif
            </div>

            <!-- Session Logs Tab -->
            <div id="tab-content-sessions" class="tab-content hidden p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Session Logs</h3>
                
                @if($sessionLogs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sessionLogs as $session)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $session->ip_address ?: 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($session->user_agent, 50) ?: 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->format('M d, Y H:i:s') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No session logs found for this user.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assign Roles Modal -->
<div id="assignRolesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Assign Roles to {{ $user->full_name }}</h2>
            <button type="button" onclick="document.getElementById('assignRolesModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="{{ route('admin.backoffice-users.assign-roles', $user->id) }}" method="POST" id="assignRolesForm">
            @csrf
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Select one or more roles to assign to this user. Roles control what actions and data the user can access.</p>
                <div class="flex flex-wrap gap-2" id="assignRolesCheckboxes">
                    @foreach($allRoles as $role)
                        <label class="cursor-pointer flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="hidden" @if($user->roles->contains($role->id)) checked @endif>
                            <span class="px-3 py-1 rounded-full border @if($user->roles->contains($role->id)) bg-indigo-600 text-white border-indigo-600 @else border-gray-400 text-gray-700 @endif mr-2">
                                {{ $role->display_name }}
                            </span>
                        </label>
                    @endforeach
                </div>
                <!-- Hidden input to ensure empty array is sent when no roles are selected -->
                <input type="hidden" name="roles[]" value="">
            </div>
            <div class="flex justify-end space-x-2">
                {{-- cancel button --}}
                <button type="button" onclick="document.getElementById('assignRolesModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
                {{-- submit button  --}}
                <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                        <i class="fas fa-user-tag mr-2"></i>
                        Assign Roles
                    </button>
            </div>
        </form>
    </div>
</div>
<!-- Role Management Script -->
<script src="{{ asset('js/role-management.js') }}"></script>
<script>


function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
    document.getElementById(`tab-content-${tabName}`).classList.add('active');
    
    // Add active class to selected tab button
    document.getElementById(`tab-${tabName}`).classList.add('active', 'border-indigo-500', 'text-indigo-600');
    document.getElementById(`tab-${tabName}`).classList.remove('border-transparent', 'text-gray-500');
}

// Initialize role manager for show view
document.addEventListener('DOMContentLoaded', function() {
    const userRoles = @json($user->roles);
    const allRoles = @json($allRoles);
    const userId = {{ $user->id }};
    
    // Initialize role manager
    window.roleManager.init(userId, userRoles, allRoles);
    
    // Set up assign roles modal
    const assignRolesModal = document.getElementById('assignRolesModal');
    if (assignRolesModal) {
        // Override the button click to use role manager
        const assignRolesButton = document.querySelector('button[onclick*="assignRolesModal"]');
        if (assignRolesButton) {
            assignRolesButton.onclick = function() {
                // Initialize role manager with current data
                window.roleManager.init(userId, userRoles, allRoles);
                
                // Create role checkboxes
                const container = document.querySelector('#assignRolesModal .flex.flex-wrap.gap-2');
                if (container) {
                    window.roleManager.createRoleCheckboxes('assignRolesCheckboxes', function(roleId, isChecked, currentRoles) {
                        console.log(`Role ${roleId} ${isChecked ? 'added' : 'removed'}. Current roles:`, currentRoles);
                    });
                }
                
                // Show modal
                assignRolesModal.classList.remove('hidden');
            };
        }
        
        // Set up form submission
        const form = document.getElementById('assignRolesForm');
        if (form) {
            window.roleManager.handleFormSubmission(form, form.action, document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        }
    }
});
</script>
@endsection 