@extends('admin.layouts.app')

@section('title', 'Load Booking Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Load Booking Details</h1>
            <p class="mt-1 text-sm text-gray-500">View and manage load booking information</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.load_bookings', ['selection' => $selection]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Load Bookings
            </a>
            @if ($order->order_status === 'pending')
                <button type="button" onclick="openConfirmModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Confirm Booking
                </button>
                <button type="button" onclick="openCancelModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Cancel Booking
                </button>
            @endif
            @if ($order->order_status === 'in_transit')
                <div class="mt-6 flex space-x-3">
                    <button onclick="openCompleteModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Complete Order
                    </button>
                    <button onclick="openCancelInTransitModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel Order
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 rounded-md bg-red-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Booking Status Banner -->
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full bg-indigo-100">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Booking #{{ $order->id }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Load Order Details</p>
                    </div>
                </div>
                <div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $getStatusBadgeClass($order->order_status ?? 'unknown') }}">
                        {{ ucfirst($order->order_status ?? 'unknown') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="border-t border-gray-200">
            <dl>
                <!-- Customer Information -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                @if(isset($order->customer['profileImage']) && !empty($order->customer['profileImage']))
                                    <img class="h-16 w-16 rounded-full object-cover" src="{{ $order->customer['profileImage'] }}" alt="Customer profile">
                                @else
                                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="font-medium text-gray-900 text-lg">
                                            @if(isset($order->cus_id))
                                                <a href="{{ route('admin.users.show', $order->cus_id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline transition duration-150 ease-in-out">
                                                    {{ isset($order->customer['first_name']) ? ($order->customer['first_name'] . ' ' . ($order->customer['last_name'] ?? '')) : ($order->customer['name'] ?? 'N/A') }}
                                                </a>
                                            @else
                                                {{ isset($order->customer['first_name']) ? ($order->customer['first_name'] . ' ' . ($order->customer['last_name'] ?? '')) : ($order->customer['name'] ?? 'N/A') }}
                                            @endif
                                        </div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            Customer ID: {{ $order->cus_id ?? 'N/A' }}
                                            @if(isset($order->cus_id))
                                                <a href="{{ route('admin.users.show', $order->cus_id) }}" class="ml-2 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-900">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                    View Profile
                                                </a>
                                            @endif
                                        </div>
                                        
                                        <!-- Contact Information -->
                                        <div class="mt-3 space-y-1">
                                            <div class="flex items-center text-sm">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-gray-600">{{ $order->customer['email'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex items-center text-sm">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span class="text-gray-600">{{ $order->customer['phone_number'] ?? $order->customer['phone'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <!-- Personal Details -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Personal Details</h4>
                                            <div class="space-y-1 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Gender:</span>
                                                    <span class="text-gray-900">{{ $order->customer['gender'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">NRC:</span>
                                                    <span class="text-gray-900">{{ $order->customer['nrc'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">User Type:</span>
                                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ ($order->customer['user_type'] ?? '') === 'Customer' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ $order->customer['user_type'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Location Details -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Location</h4>
                                            <div class="space-y-1 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Country:</span>
                                                    <span class="text-gray-900">{{ $order->customer['country'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Province:</span>
                                                    <span class="text-gray-900">{{ $order->customer['province'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">City/Town:</span>
                                                    <span class="text-gray-900">{{ $order->customer['city_town'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Status -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Status</h4>
                                            <div class="flex space-x-2">
                                                @if(isset($order->customer['isOnline']) && $order->customer['isOnline'])
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <span class="w-1.5 h-1.5 mr-1 bg-green-400 rounded-full"></span>
                                                        Online
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <span class="w-1.5 h-1.5 mr-1 bg-gray-400 rounded-full"></span>
                                                        Offline
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dd>
                </div>

                <!-- Transporter Information -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Driver/Transporter Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if(isset($order->driver) || isset($order->transporter))
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                @if(isset($order->driver['profileImage']) && !empty($order->driver['profileImage']))
                                    <img class="h-16 w-16 rounded-full object-cover" src="{{ $order->driver['profileImage'] }}" alt="Driver profile">
                                @elseif(isset($order->transporter['profileImage']) && !empty($order->transporter['profileImage']))
                                    <img class="h-16 w-16 rounded-full object-cover" src="{{ $order->transporter['profileImage'] }}" alt="Transporter profile">
                                @else
                                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="font-medium text-gray-900 text-lg">
                                            @if(isset($order->driver) && isset($order->driver_id))
                                                <a href="{{ route('admin.users.show', $order->driver_id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline transition duration-150 ease-in-out">
                                                    {{ isset($order->driver['first_name']) ? ($order->driver['first_name'] . ' ' . ($order->driver['last_name'] ?? '')) : ($order->driver['name'] ?? 'N/A') }}
                                                </a>
                                            @elseif(isset($order->transporter) && isset($order->transporter['user_id']))
                                                <a href="{{ route('admin.users.show', $order->transporter['user_id']) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline transition duration-150 ease-in-out">
                                                    {{ isset($order->transporter['first_name']) ? ($order->transporter['first_name'] . ' ' . ($order->transporter['last_name'] ?? '')) : ($order->transporter['name'] ?? 'N/A') }}
                                                </a>
                                            @elseif(isset($order->transporter) && isset($order->cus_id))
                                                <a href="{{ route('admin.users.show', $order->cus_id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline transition duration-150 ease-in-out">
                                                    {{ isset($order->transporter['first_name']) ? ($order->transporter['first_name'] . ' ' . ($order->transporter['last_name'] ?? '')) : ($order->transporter['name'] ?? 'N/A') }}
                                                </a>
                                            @elseif(isset($order->transporter))
                                                {{ isset($order->transporter['first_name']) ? ($order->transporter['first_name'] . ' ' . ($order->transporter['last_name'] ?? '')) : ($order->transporter['name'] ?? 'N/A') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            @if(isset($order->driver_id))
                                                Driver ID: {{ $order->cus_id ?? $order->driver_id }}
                                                <a href="{{ route('admin.users.show', $order->driver_id) }}" class="ml-2 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-900">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                    View Profile
                                                </a>
                                            @elseif(isset($order->transporter['user_id']))
                                                Transporter ID: {{ $order->cus_id ?? $order->transporter['user_id'] }}
                                                <a href="{{ route('admin.users.show', $order->transporter['user_id']) }}" class="ml-2 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-900">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                    View Profile
                                                </a>
                                            @else
                                                ID: {{ $order->cus_id ?? 'N/A' }}
                                            @endif
                                        </div>
                                        
                                        <!-- Contact Information -->
                                        <div class="mt-3 space-y-1">
                                            <div class="flex items-center text-sm">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-gray-600">
                                                    {{ $order->driver['email'] ?? $order->transporter['email'] ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center text-sm">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span class="text-gray-600">
                                                    {{ $order->driver['phone_number'] ?? $order->driver['phone'] ?? $order->transporter['phone_number'] ?? $order->transporter['phone'] ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <!-- Personal Details -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Personal Details</h4>
                                            <div class="space-y-1 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Gender:</span>
                                                    <span class="text-gray-900">
                                                        {{ $order->driver['gender'] ?? $order->transporter['gender'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">NRC:</span>
                                                    <span class="text-gray-900">
                                                        {{ $order->driver['nrc'] ?? $order->transporter['nrc'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">User Type:</span>
                                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ (($order->driver['user_type'] ?? $order->transporter['user_type'] ?? '') === 'Driver') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ $order->driver['user_type'] ?? $order->transporter['user_type'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Location Details -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Location</h4>
                                            <div class="space-y-1 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Country:</span>
                                                    <span class="text-gray-900">
                                                        {{ $order->driver['country'] ?? $order->transporter['country'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Province:</span>
                                                    <span class="text-gray-900">
                                                        {{ $order->driver['province'] ?? $order->transporter['province'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">City/Town:</span>
                                                    <span class="text-gray-900">
                                                        {{ $order->driver['city_town'] ?? $order->transporter['city_town'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Driver/Transporter Status -->
                                        
                                        
                                        <!-- Truck Information -->
                                        @if(isset($order->truck))
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Truck Information</h4>
                                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                                <!-- Basic Truck Details -->
                                                <div class="grid grid-cols-2 gap-3 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500">Truck ID:</span>
                                                        <span class="text-gray-900 font-medium">{{ $order->truck['id'] ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500">Status:</span>
                                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ ($order->truck['status'] ?? '') === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ ucfirst($order->truck['status'] ?? 'unknown') }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Vehicle Details -->
                                                <div class="border-t border-gray-200 pt-3">
                                                    <h5 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Vehicle Details</h5>
                                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">License Number:</span>
                                                            <span class="text-gray-900 font-mono">{{ $order->truck['license_number'] ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Model:</span>
                                                            <span class="text-gray-900">{{ $order->truck['model'] ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Tonnage:</span>
                                                            <span class="text-gray-900">{{ $order->truck['tonnage'] ?? 'N/A' }} tons</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Trailer Number:</span>
                                                            <span class="text-gray-900 font-mono">{{ $order->truck['trailer_number'] ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Trailer Information -->
                                                @if(isset($order->truck['trailer_type']) || isset($order->truck['trailer_type2']))
                                                <div class="border-t border-gray-200 pt-3">
                                                    <h5 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Trailer Information</h5>
                                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                                        @if(isset($order->truck['trailer_type']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Trailer Type:</span>
                                                            <span class="text-gray-900">{{ $order->truck['trailer_type'] }}</span>
                                                        </div>
                                                        @endif
                                                        @if(isset($order->truck['trailer_type2']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Trailer Type 2:</span>
                                                            <span class="text-gray-900">{{ $order->truck['trailer_type2'] }}</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Driver/Transporter Details -->
                                                @if(isset($order->truck['transporter_name']) || isset($order->truck['transporter_phone']))
                                                <div class="border-t border-gray-200 pt-3">
                                                    <h5 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Transporter Details</h5>
                                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                                        @if(isset($order->truck['transporter_name']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Name:</span>
                                                            <span class="text-gray-900">{{ $order->truck['transporter_name'] }}</span>
                                                        </div>
                                                        @endif
                                                        @if(isset($order->truck['transporter_phone']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Phone:</span>
                                                            <span class="text-gray-900">{{ $order->truck['transporter_phone'] }}</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Location Details -->
                                                @if(isset($order->truck['address']) || isset($order->truck['city']) || isset($order->truck['province']))
                                                <div class="border-t border-gray-200 pt-3">
                                                    <h5 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Location Details</h5>
                                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                                        @if(isset($order->truck['address']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Address:</span>
                                                            <span class="text-gray-900">{{ $order->truck['address'] }}</span>
                                                        </div>
                                                        @endif
                                                        @if(isset($order->truck['city']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">City:</span>
                                                            <span class="text-gray-900">{{ $order->truck['city'] }}</span>
                                                        </div>
                                                        @endif
                                                        @if(isset($order->truck['province']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Province:</span>
                                                            <span class="text-gray-900">{{ $order->truck['province'] }}</span>
                                                        </div>
                                                        @endif
                                                        @if(isset($order->truck['id_number']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">ID Number:</span>
                                                            <span class="text-gray-900">{{ $order->truck['id_number'] }}</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Registration Dates -->
                                                @if(isset($order->truck['added_date']) || isset($order->truck['approved_date']))
                                                <div class="border-t border-gray-200 pt-3">
                                                    <h5 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Registration Dates</h5>
                                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                                        @if(isset($order->truck['added_date']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Added Date:</span>
                                                            <span class="text-gray-900">{{ $order->truck['added_date'] }}</span>
                                                        </div>
                                                        @endif
                                                        @if(isset($order->truck['approved_date']))
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Approved Date:</span>
                                                            <span class="text-gray-900">{{ $order->truck['approved_date'] }}</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Document Links (if available) -->
                                                @if(isset($order->truck['selfie_image_url']) || isset($order->truck['id_front_url']) || isset($order->truck['driving_license_front']) || isset($order->truck['license_url']) || isset($order->truck['side_view_url']) || isset($order->truck['trailer_url']))
                                                <div class="border-t border-gray-200 pt-3">
                                                    <h5 class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Documents & Images</h5>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        @if(isset($order->truck['selfie_image_url']))
                                                        <a href="{{ $order->truck['selfie_image_url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Selfie Image
                                                        </a>
                                                        @endif
                                                        @if(isset($order->truck['id_front_url']))
                                                        <a href="{{ $order->truck['id_front_url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            ID Front
                                                        </a>
                                                        @endif
                                                        @if(isset($order->truck['id_back_url']))
                                                        <a href="{{ $order->truck['id_back_url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            ID Back
                                                        </a>
                                                        @endif
                                                        @if(isset($order->truck['driving_license_front']))
                                                        <a href="{{ $order->truck['driving_license_front'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-md hover:bg-green-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            License Front
                                                        </a>
                                                        @endif
                                                        @if(isset($order->truck['driving_license_back']))
                                                        <a href="{{ $order->truck['driving_license_back'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-md hover:bg-green-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            License Back
                                                        </a>
                                                        @endif
                                                        @if(isset($order->truck['license_url']))
                                                        <a href="{{ $order->truck['license_url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-md hover:bg-purple-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            License Doc
                                                        </a>
                                                        @endif
                                                        @if(isset($order->truck['side_view_url']))
                                                        <a href="{{ $order->truck['side_view_url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-md hover:bg-orange-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Side View
                                                        </a>
                                                        @endif
                                                        @if(isset($order->truck['trailer_url']))
                                                        <a href="{{ $order->truck['trailer_url'] }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Trailer Image
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @elseif(isset($order->truck_id))
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Truck Information</h4>
                                            <div class="text-sm text-gray-500">
                                                Truck ID: {{ $order->truck_id }} (Details not loaded)
                                            </div>
                                        </div>
                                        @else
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Truck Information</h4>
                                            <div class="text-sm text-gray-500">
                                                No truck assigned to this order
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16 flex items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-500 text-lg">No driver/transporter assigned</div>
                                <div class="text-gray-400 text-sm">Waiting for booking confirmation</div>
                            </div>
                        </div>
                        @endif
                    </dd>
                </div>

                <!-- Load Details -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Load Details</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="font-medium">Load ID</div>
                                <div class="text-gray-500">{{ $order->load_id ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-medium">Amount</div>
                                <div class="text-gray-500">{{ $order->amount ?? '0' }} {{ $order->currency ?? 'USD' }}</div>
                            </div>
                            <div>
                                <div class="font-medium">Added Date</div>
                                <div class="text-gray-500">
                                    @if(isset($order->added_date))
                                        {{ \Carbon\Carbon::parse($order->added_date)->format('M d, Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="font-medium">Status</div>
                                <div class="text-gray-500">{{ ucfirst($order->order_status ?? 'unknown') }}</div>
                            </div>
                        </div>
                    </dd>
                </div>

                <!-- Additional Information -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Additional Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-4">
                            @if(isset($order->notes) && !empty($order->notes))
                            <div>
                                <div class="font-medium">Notes</div>
                                <div class="text-gray-500">{{ $order->notes }}</div>
                            </div>
                            @endif
                            @if(isset($order->cancellation_reason) && !empty($order->cancellation_reason))
                            <div>
                                <div class="font-medium text-red-600">Cancellation Reason</div>
                                <div class="text-gray-500">{{ $order->cancellation_reason }}</div>
                            </div>
                            @endif
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Modals -->
@include('admin.load_bookings.partials.confirm-modal')
@include('admin.load_bookings.partials.cancel-modal')
@include('admin.load_bookings.partials.complete-modal')
@include('admin.load_bookings.partials.cancel-in-transit-modal')

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Complete Load Order
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to mark this load order as completed? This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.load_bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                @csrf
                <input type="hidden" name="action" value="complete">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Complete Order
                </button>
                <button type="button" onclick="closeCompleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Cancel In-Transit Modal -->
<div id="cancelInTransitModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Cancel In-Transit Load
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to cancel this in-transit load? This action cannot be undone.
                        </p>
                        <div class="mt-4">
                            <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Reason for Cancellation</label>
                            <textarea id="cancel_reason" name="cancel_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Please provide a reason for cancellation"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.load_bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                @csrf
                <input type="hidden" name="action" value="cancel_in_transit">
                <input type="hidden" name="cancel_reason" id="cancel_reason_input">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel Load
                </button>
                <button type="button" onclick="closeCancelInTransitModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Go Back
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Modal Functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Specific modal functions
    function openConfirmModal() {
        openModal('confirmModal');
    }

    function closeConfirmModal() {
        closeModal('confirmModal');
    }

    function openCancelModal() {
        openModal('cancelModal');
    }

    function closeCancelModal() {
        closeModal('cancelModal');
    }

    function openCompleteModal() {
        openModal('completeModal');
    }

    function closeCompleteModal() {
        closeModal('completeModal');
    }

    function openCancelInTransitModal() {
        openModal('cancelInTransitModal');
    }

    function closeCancelInTransitModal() {
        closeModal('cancelInTransitModal');
    }

    // Handle form submission for cancellation
    const cancelForm = document.querySelector('#cancelModal form');
    if (cancelForm) {
        cancelForm.addEventListener('submit', function(e) {
            const reason = document.getElementById('cancel_reason').value.trim();
            if (!reason) {
                e.preventDefault();
                alert('Please provide a cancellation reason');
                return;
            }
            document.getElementById('cancel_reason_input').value = reason;
        });
    }

    // Handle form submission for in-transit cancellation
    const cancelInTransitForm = document.querySelector('#cancelInTransitModal form');
    if (cancelInTransitForm) {
        cancelInTransitForm.addEventListener('submit', function(e) {
            const reason = document.getElementById('cancel_reason').value.trim();
            if (!reason) {
                e.preventDefault();
                alert('Please provide a cancellation reason');
                return;
            }
            document.getElementById('cancel_reason_input').value = reason;
        });
    }

    // Close modals when clicking outside
    const modals = ['confirmModal', 'cancelModal', 'completeModal', 'cancelInTransitModal'];
    window.addEventListener('click', function(event) {
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    });

    // Close modals when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            modals.forEach(modalId => closeModal(modalId));
        }
    });
</script>
@endpush

@endsection