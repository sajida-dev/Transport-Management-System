@extends('admin.layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="space-y-8 py-8">
    <!-- Page header with improved styling -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                        <i class="fas fa-user-cog text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                        <p class="mt-1 text-sm text-gray-500">Manage your account settings and preferences</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div id="flash-message" class="hidden">
        <div class="rounded-md p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i id="flash-icon" class="h-5 w-5"></i>
                </div>
                <div class="ml-3">
                    <p id="flash-text" class="text-sm font-medium"></p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="hideFlashMessage()" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2">
                            <span class="sr-only">Dismiss</span>
                            <i class="fas fa-times h-5 w-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="h-32 w-32 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                            <i class="fas fa-user text-indigo-600 text-5xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $adminUser->name ?? 'Admin User' }}</h2>
                        <p class="text-sm text-gray-500">{{ $adminUser->email ?? 'admin@example.com' }}</p>
                        <div class="mt-4 w-full">
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-500">Role</span>
                                <span class="text-sm text-gray-900">Administrator</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-500">Member Since</span>
                                <span class="text-sm text-gray-900">{{ $adminUser->created_at ? $adminUser->created_at->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm font-medium text-gray-500">Last Login</span>
                                <span class="text-sm text-gray-900">{{ $adminUser->last_login_at ? $adminUser->last_login_at->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information Form -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
                </div>
                <form method="POST" action="{{ route('admin.profile') }}" class="p-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', $adminUser->name ?? 'Admin User Name') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" value="{{ old('email', $adminUser->email ?? 'admin@example.com') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1">
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $adminUser->phone ?? '') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change Form -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your password to keep your account secure.</p>
                </div>
                <form method="POST" action="{{ route('admin.password.update') }}" class="p-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <div class="mt-1">
                                <input type="password" name="current_password" id="current_password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <div class="mt-1">
                                <input type="password" name="password" id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <div class="mt-1">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-key mr-2"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notification Preferences -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Notification Preferences</h3>
                    <p class="mt-1 text-sm text-gray-500">Manage how you receive notifications.</p>
                </div>
                <form method="POST" action="{{ route('admin.notifications.update') }}" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="email_notifications" name="email_notifications" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ $adminUser->email_notifications ?? true ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="email_notifications" class="font-medium text-gray-700">Email Notifications</label>
                                <p class="text-gray-500">Receive notifications via email about important updates and activities.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="sms_notifications" name="sms_notifications" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ $adminUser->sms_notifications ?? false ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="sms_notifications" class="font-medium text-gray-700">SMS Notifications</label>
                                <p class="text-gray-500">Receive notifications via SMS for critical alerts.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="push_notifications" name="push_notifications" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ $adminUser->push_notifications ?? true ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="push_notifications" class="font-medium text-gray-700">Push Notifications</label>
                                <p class="text-gray-500">Receive push notifications in your browser for real-time updates.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-bell mr-2"></i>
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Deletion -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Delete Account</h3>
                    <p class="mt-1 text-sm text-gray-500">Permanently delete your account and all associated data.</p>
                </div>
                <div class="p-6">
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Warning</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="button" onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showFlashMessage(message, type = 'success') {
    const flashMessage = document.getElementById('flash-message');
    const flashText = document.getElementById('flash-text');
    const flashIcon = document.getElementById('flash-icon');
    
    // Set message text
    flashText.textContent = message;
    
    // Set icon and colors based on type
    if (type === 'success') {
        flashIcon.className = 'fas fa-check-circle h-5 w-5 text-green-400';
        flashMessage.querySelector('.rounded-md').className = 'rounded-md p-4 mb-4 bg-green-50';
    } else if (type === 'error') {
        flashIcon.className = 'fas fa-exclamation-circle h-5 w-5 text-red-400';
        flashMessage.querySelector('.rounded-md').className = 'rounded-md p-4 mb-4 bg-red-50';
    } else if (type === 'warning') {
        flashIcon.className = 'fas fa-exclamation-triangle h-5 w-5 text-yellow-400';
        flashMessage.querySelector('.rounded-md').className = 'rounded-md p-4 mb-4 bg-yellow-50';
    }
    
    // Show the message
    flashMessage.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(hideFlashMessage, 5000);
}

function hideFlashMessage() {
    document.getElementById('flash-message').classList.add('hidden');
}

function confirmDelete() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        // Redirect to delete account route
        window.location.href = '{{ route('admin.account.delete') }}';
    }
}

// Check for flash messages in session
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showFlashMessage('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showFlashMessage('{{ session('error') }}', 'error');
    @endif
    
    @if(session('warning'))
        showFlashMessage('{{ session('warning') }}', 'warning');
    @endif
});
</script>
@endpush
@endsection
