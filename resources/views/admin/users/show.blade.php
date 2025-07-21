@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="space-y-8 py-8">
    <!-- Page header with improved styling -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                        <i class="fas fa-user text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                        <p class="mt-1 text-sm text-gray-500">View and manage user information</p>
                    </div>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
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

    <!-- User Profile Card -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-indigo-800">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-20 w-20 rounded-full bg-white flex items-center justify-center ring-4 ring-white">
                    <i class="fas fa-user text-indigo-600 text-3xl"></i>
                </div>
                <div class="ml-5">
                    <h2 class="text-xl font-bold text-black">{{ $user['first_name'] ?? '' }} {{ $user['last_name'] ?? '' }}</h2>
                    <div class="mt-1 flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user['driver_verified'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $user['driver_verified'] ? 'Verified' : 'Unverified' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($user['user_type'] ?? 'User') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-5">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-address-card text-indigo-500 mr-2"></i>
                        Contact Information
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-envelope text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-sm text-gray-900">{{ $user['email'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-phone text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Phone</p>
                                <p class="text-sm text-gray-900">{{ $user['phone_number'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-venus-mars text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Gender</p>
                                <p class="text-sm text-gray-900">{{ $user['gender'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center mb-4">
                        <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>
                        Location
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-home text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Address</p>
                                <p class="text-sm text-gray-900">{{ $user['address'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-city text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">City/Town</p>
                                <p class="text-sm text-gray-900">{{ $user['city_town'] ?? 'N/A' }}, {{ $user['province'] ?? '' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-globe text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Country</p>
                                <p class="text-sm text-gray-900">{{ $user['country'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-6 bg-gray-50 rounded-lg p-5">
                <h3 class="text-lg font-medium text-gray-900 flex items-center mb-4">
                    <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                    Additional Information
                </h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-500">NRC Number</p>
                        <p class="text-sm text-gray-900">{{ $user['nrc'] ?? 'N/A' }}</p>
                    </div>
                    @if(isset($user['user_type']) && $user['user_type'] === 'transporter')
                    <div>
                        <p class="text-sm font-medium text-gray-500">Driver Verification</p>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user['driver_verified'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $user['driver_verified'] ? 'Verified' : 'Unverified' }}
                            </span>
                        </p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-500">Member Since</p>
                        <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user['added_date'] ?? now())->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Account Status</p>
                        <div class="mt-1">
                            @if($user['isBanned'] ?? false)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>
                                    Banned
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Active
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($user['isBanned'] ?? false)
                <!-- Ban Reason Section -->
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h4 class="text-sm font-medium text-red-800 flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Ban Information
                    </h4>
                    <div>
                        <p class="text-sm font-medium text-red-700">Reason:</p>
                        <p class="text-sm text-red-900 mt-1">{{ $user['banReason'] ?? 'No reason provided' }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- User Activity Section -->
    @if(isset($user['user_type']))
        @if($user['user_type'] === 'transporter' && isset($additionalData['trucks']))
            <!-- Transporter's Trucks -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-truck text-indigo-500 mr-2"></i>
                        Registered Trucks
                    </h3>
                </div>
                <div class="px-6 py-5">
                    @if(count($additionalData['trucks']) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Truck Details</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver Info</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($additionalData['trucks'] as $truck)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    Plate: {{ $truck['truck_plate_number'] ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Model: {{ $truck['truck_model'] ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Tonage: {{ $truck['tonage'] ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $truck['driver_first_name'] ?? '' }} {{ $truck['driver_last_name'] ?? '' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    NRC: {{ $truck['driver_nrc_number'] ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $truck['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                                       ($truck['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($truck['status'] ?? 'Unknown') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($truck['added_date'] ?? now())->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="flex justify-center">
                                <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-truck text-gray-400 text-xl"></i>
                                </div>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No trucks registered</h3>
                            <p class="mt-1 text-sm text-gray-500">This user hasn't registered any trucks yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($user['user_type'] === 'load_owner' && isset($additionalData['loads']))
            <!-- Load Owner's Loads -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-box text-indigo-500 mr-2"></i>
                        Posted Loads
                    </h3>
                </div>
                <div class="px-6 py-5">
                    @if(count($additionalData['loads']) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Load Details</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($additionalData['loads'] as $load)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $load['load_type'] ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Weight: {{ $load['weight'] ?? 'N/A' }} tons
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Price: K{{ number_format($load['price'] ?? 0, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    From: {{ $load['pickup_location'] ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    To: {{ $load['delivery_location'] ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $load['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($load['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($load['status'] === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $load['status'] ?? 'Unknown')) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($load['added_date'] ?? now())->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="flex justify-center">
                                <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-xl"></i>
                                </div>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No loads posted</h3>
                            <p class="mt-1 text-sm text-gray-500">This user hasn't posted any loads yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <!-- Action Buttons -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="fas fa-cog text-indigo-500 mr-2"></i>
                User Actions
            </h3>
        </div>
        <div class="px-6 py-5">
            <div class="flex flex-wrap gap-4">
                <button type="button" onclick="openEditModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-edit mr-2"></i>
                    Edit User
                </button>
                @if(!($user['driver_verified'] ?? false))
                <form action="{{ route('admin.users.verify', $user['uid']) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you sure you want to verify this user?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-check-circle mr-2"></i>
                        Verify User
                    </button>
                </form>
                @else
                <button type="button" disabled class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed">
                    <i class="fas fa-check-circle mr-2"></i>
                    Already Verified
                </button>
                @endif
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <i class="fas fa-key mr-2"></i>
                    Reset Password
                </button>
                @if(!($user['isBanned'] ?? false))
                <button type="button" onclick="openSuspendModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-ban mr-2"></i>
                    Suspend User
                </button>
                @else
                <form action="{{ route('admin.users.unban', $user['uid']) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you sure you want to unban this user?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-unlock mr-2"></i>
                        Unban User
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit User Details</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.users.update', $user['uid']) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ $user['first_name'] ?? '' }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ $user['last_name'] ?? '' }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ $user['email'] ?? '' }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ $user['phone_number'] ?? '' }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ ($user['gender'] ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ ($user['gender'] ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ ($user['gender'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="nrc" class="block text-sm font-medium text-gray-700">NRC Number</label>
                        <input type="text" name="nrc" id="nrc" value="{{ $user['nrc'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $user['address'] ?? '' }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3 mt-6">
                    <div>
                        <label for="city_town" class="block text-sm font-medium text-gray-700">City/Town</label>
                        <input type="text" name="city_town" id="city_town" value="{{ $user['city_town'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                        <input type="text" name="province" id="province" value="{{ $user['province'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                        <input type="text" name="country" id="country" value="{{ $user['country'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suspend User Modal -->
<div id="suspendUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 text-red-600">Suspend User</h3>
                <button type="button" onclick="closeSuspendModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-medium">Warning!</span> This action will suspend the user's account and prevent them from accessing the platform.
                </div>
            </div>
            
            <form action="{{ route('admin.users.suspend', $user['uid']) }}" method="POST" id="suspendUserForm">
                @csrf
                
                <div class="mb-4">
                    <label for="ban_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Suspension</label>
                    <textarea name="ban_reason" id="ban_reason" rows="4" required placeholder="Please provide a detailed reason for suspending this user..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeSuspendModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Suspend User
                    </button>
                </div>
            </form>
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

function openEditModal() {
    document.getElementById('editUserModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}

function openSuspendModal() {
    document.getElementById('suspendUserModal').classList.remove('hidden');
}

function closeSuspendModal() {
    document.getElementById('suspendUserModal').classList.add('hidden');
    // Clear the textarea
    document.getElementById('ban_reason').value = '';
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