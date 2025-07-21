@extends('admin.layouts.app')

@section('title', 'Profile')

@section('content')
<div class="space-y-10">
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
        <!-- Profile Information -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Profile Information</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>Update your account's profile information and email address.</p>
                </div>
                <form class="mt-5 space-y-6" action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-16 w-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin User') }}&background=6366f1&color=fff" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Profile Photo</div>
                                <div class="mt-1">
                                    <label class="block text-sm font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
                                        <span>Change photo</span>
                                        <input type="file" name="photo" class="sr-only">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ auth()->user()->name ?? 'Admin User' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" value="{{ auth()->user()->email ?? 'admin@example.com' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <div class="mt-1">
                            <input type="text" name="role" id="role" value="{{ auth()->user()->role ?? 'Administrator' }}" disabled class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Change Password</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>Ensure your account is using a long, random password to stay secure.</p>
                </div>
                <form class="mt-5 space-y-6" action="{{ route('admin.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <div class="mt-1">
                            <input type="password" name="current_password" id="current_password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <div class="mt-1">
                            <input type="password" name="new_password" id="new_password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <div class="mt-1">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Change password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Two Factor Authentication -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Two Factor Authentication</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>Add additional security to your account using two factor authentication.</p>
                </div>
                <div class="mt-5">
                    @if(!auth()->user()?->two_factor_enabled)
                    <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Enable Two-Factor
                    </button>
                    @else
                    <button type="button" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Disable Two-Factor
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Delete Account</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                </div>
                <div class="mt-5">
                    <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Management -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Browser Sessions</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Manage and log out your active sessions on other browsers and devices.</p>
            </div>
            <div class="mt-5 space-y-6">
                <div class="divide-y divide-gray-200">
                    <!-- Current session -->
                    <div class="flex items-center justify-between py-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-desktop text-gray-400 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">This device</p>
                                <p class="text-xs text-gray-500">Last active {{ now()->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                Current session
                            </span>
                        </div>
                    </div>

                    <!-- Other sessions example -->
                    <div class="flex items-center justify-between py-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-mobile-alt text-gray-400 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">iPhone 12</p>
                                <p class="text-xs text-gray-500">Last active 2 hours ago</p>
                            </div>
                        </div>
                        <button type="button" class="text-sm font-medium text-red-600 hover:text-red-500">
                            Log out
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Log Out Other Browser Sessions
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection