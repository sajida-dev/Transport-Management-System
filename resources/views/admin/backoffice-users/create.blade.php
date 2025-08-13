@extends('admin.layouts.app')

@section('title', 'Create Back Office User')

@section('content')
<div class="w-full px-4 py-8">
    <div class="w-full">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create New Back Office User</h1>
            <p class="mt-2 text-sm text-gray-600">Add a new team member with appropriate roles and permissions</p>
        </div>

        <!-- Create User Form -->
        <div class="bg-white shadow rounded-lg p-6 w-full">
            <form action="{{ route('admin.backoffice-users.store') }}" method="POST" id="createUserForm">
                @csrf
                
                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                                value="{{ old('name') }}" required>
                            <p class="mt-1 text-xs text-gray-500">Enter the user's full name as it should appear in the system</p>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}" required>
                            <p class="mt-1 text-xs text-gray-500">This will be used for login and notifications</p>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input type="text" name="phone_number" id="phone_number" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('phone_number') border-red-500 @enderror"
                                value="{{ old('phone_number') }}">
                            <p class="mt-1 text-xs text-gray-500">Optional: Include country code (e.g., +1234567890)</p>
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name
                            </label>
                            <input type="text" name="first_name" id="first_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('first_name') border-red-500 @enderror"
                                value="{{ old('first_name') }}">
                            <p class="mt-1 text-xs text-gray-500">Optional: User's first name for detailed records</p>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name
                            </label>
                            <input type="text" name="last_name" id="last_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('last_name') border-red-500 @enderror"
                                value="{{ old('last_name') }}">
                            <p class="mt-1 text-xs text-gray-500">Optional: User's last name for detailed records</p>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Security Information</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Minimum 6 characters, should be secure</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Re-enter the password to confirm</p>
                        </div>
                    </div>
                </div>

                <!-- Role Assignment -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Role Assignment</h3>
                    <p class="text-sm text-gray-600 mb-4">Select one or more roles to assign to this user. Roles control what actions and data the user can access.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <label class="cursor-pointer flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" 
                                    value="{{ $role->id }}" 
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded @error('roles') border-red-500 @enderror"
                                    {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $role->display_name }}</div>
                                    @if($role->description)
                                        <div class="text-xs text-gray-500">{{ $role->description }}</div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Account Status</h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active Account
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Uncheck to create an inactive account</p>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.backoffice-users.index') }}" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

// Form validation and submission
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        Toastify({
            text: "Passwords do not match!",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#F59E0B",
            stopOnFocus: true
        }).showToast();
        return false;
    }
    
    const roles = document.querySelectorAll('input[name="roles[]"]:checked');
    if (roles.length === 0) {
        e.preventDefault();
        Toastify({
            text: "Please select at least one role!",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#F59E0B",
            stopOnFocus: true
        }).showToast();
        return false;
    }
});
</script>
@endsection 