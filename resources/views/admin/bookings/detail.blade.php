@extends('admin.layouts.app')

@section('title', 'Order Details')

@php
// Helper functions for currency and distance formatting
$formatCurrency = function($amount, $currency = 'USD') {
    if (!$amount) return 'N/A';
    return $currency . ' ' . number_format($amount, 2);
};

$formatDistance = function($distance) {
    if (!$distance) return 'N/A';
    return number_format($distance, 2) . ' km';
};
@endphp

@section('content')
<div class="min-h-full bg-gray-50">
    <!-- Header with back button and title -->
    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('admin.bookings', ['selection' => $selection]) }}" class="mr-4 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Order Details</h1>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $getStatusBadgeClass($order->order_status) }}">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>
        </div>
    </header>

    <main class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order ID and Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg mb-6 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Order #{{ $order->id }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Created on {{ $order->formatted_added_date ?? 'N/A' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        @if($order->order_status === 'pending')
                            <button type="button" 
                                    onclick="openTruckModal()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confirm Booking
                            </button>
                            <form action="{{ route('admin.bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Cancel Booking
                                </button>
                            </form>
                        @elseif($order->order_status === 'in_transit')
                            <form action="{{ route('admin.bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="complete">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Complete Order
                                </button>
                            </form>
                            <form action="{{ route('admin.bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="cancel_in_transit">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Details Card -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Order Information</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $getStatusBadgeClass($order->order_status) }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Added Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->formatted_added_date ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pickup Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->formatted_pickup_date ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->description ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Commodity</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->commodity ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tonnage</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->tonnage ?? 'N/A' }} tons</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $formatCurrency($order->amount, $order->currency) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Distance</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $formatDistance($order->distance) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Trailer Type 1</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->trailer_type_one ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Trailer Type 2</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->trailer_type_two ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Route Information Card -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Route Information</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <!-- Route Map -->
                            <div class="mb-6">
                                <div id="route-map" class="w-full h-64 rounded-lg border border-gray-300"></div>
                                <div class="mt-2 text-xs text-gray-500 text-center">
                                    Interactive map showing pickup location, driver location, and destination
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Initial Location (Pickup)</h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            <span id="initial-location-coords">{{ $order->initial_latitude }}, {{ $order->initial_longitude }}</span>
                                            <span id="initial-location-name" class="block mt-1 text-xs text-gray-600 font-medium">
                                                <span class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading location name...
                                                </span>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Driver Location</h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            <span id="merchant-location-coords">{{ $order->mer_latitude }}, {{ $order->mer_longitude }}</span>
                                            <span id="merchant-location-name" class="block mt-1 text-xs text-gray-600 font-medium">
                                                @if($order->mer_latitude == 0 && $order->mer_longitude == 0)
                                                    <span class="text-orange-600">No driver location available</span>
                                                @else
                                                    <span class="inline-flex items-center">
                                                        <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Loading location name...
                                                    </span>
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">Destination</h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            <span id="destination-location-coords">{{ $order->destination_latitude }}, {{ $order->destination_longitude }}</span>
                                            <span id="destination-location-name" class="block mt-1 text-xs text-gray-600 font-medium">
                                                <span class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading location name...
                                                </span>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->reject_reason)
                    <!-- Rejection Reason Card -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Rejection Reason</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <p class="text-sm text-gray-900">{{ $order->reject_reason }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Truck Details -->
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Truck Details</h3>
                            <div class="mt-2 max-w-xl text-sm text-gray-500">
                                @if(isset($order->truck))
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <p><span class="font-medium">Transporter Name:</span> {{ $order->truck['transporter_name'] }}</p>
                                            <p><span class="font-medium">Transporter Phone:</span> {{ $order->truck['transporter_phone'] }}</p>
                                            <p><span class="font-medium">Model:</span> {{ $order->truck['model'] }}</p>
                                            <p><span class="font-medium">License Number:</span> {{ $order->truck['license_number'] }}</p>
                                            <p><span class="font-medium">Trailer Number:</span> {{ $order->truck['trailer_number'] }}</p>
                                            <p><span class="font-medium">Trailer Type:</span> {{ $order->truck['trailer_type'] }}</p>
                                            <p><span class="font-medium">Trailer Type 2:</span> {{ $order->truck['trailer_type2'] }}</p>
                                            <p><span class="font-medium">Tonnage:</span> {{ $order->truck['tonnage'] }}</p>
                                        </div>
                                        <div>
                                            <p><span class="font-medium">Address:</span> {{ $order->truck['address'] }}</p>
                                            <p><span class="font-medium">City:</span> {{ $order->truck['city'] }}</p>
                                            <p><span class="font-medium">Province:</span> {{ $order->truck['province'] }}</p>
                                            <p><span class="font-medium">Status:</span> {{ $order->truck['status'] }}</p>
                                            <p><span class="font-medium">Online Status:</span> {{ $order->truck['is_online'] ? 'Online' : 'Offline' }}</p>
                                            <p><span class="font-medium">ID Number:</span> {{ $order->truck['id_number'] }}</p>
                                            <p><span class="font-medium">Added Date:</span> {{ $order->truck['added_date'] }}</p>
                                            <p><span class="font-medium">Approved Date:</span> {{ $order->truck['approved_date'] }}</p>
                                        </div>
                                    </div>

                                    <!-- Truck Images -->
                                    <div class="mt-6">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Truck Documents & Images</h4>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @if($order->truck['driving_license_front'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">Driving License Front</p>
                                                    <img src="{{ $order->truck['driving_license_front'] }}" alt="Driving License Front" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                            @if($order->truck['driving_license_back'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">Driving License Back</p>
                                                    <img src="{{ $order->truck['driving_license_back'] }}" alt="Driving License Back" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                            @if($order->truck['id_front_url'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">ID Front</p>
                                                    <img src="{{ $order->truck['id_front_url'] }}" alt="ID Front" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                            @if($order->truck['id_back_url'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">ID Back</p>
                                                    <img src="{{ $order->truck['id_back_url'] }}" alt="ID Back" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                            @if($order->truck['license_url'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">License</p>
                                                    <img src="{{ $order->truck['license_url'] }}" alt="License" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                            @if($order->truck['selfie_image_url'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">Selfie</p>
                                                    <img src="{{ $order->truck['selfie_image_url'] }}" alt="Selfie" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                            @if($order->truck['side_view_url'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">Side View</p>
                                                    <img src="{{ $order->truck['side_view_url'] }}" alt="Side View" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                            @if($order->truck['trailer_url'])
                                                <div>
                                                    <p class="text-sm font-medium mb-1">Trailer</p>
                                                    <img src="{{ $order->truck['trailer_url'] }}" alt="Trailer" class="w-full h-32 object-cover rounded">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p class="mt-2">No truck details available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information Card -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Load Owner Information</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            @if(isset($order->customer))
                                <div class="flex items-center mb-4">
                                    @if(isset($order->customer['profile_image']) || isset($order->customer['profileImage']))
                                        <img src="{{ $order->customer['profile_image'] ?? $order->customer['profileImage'] }}" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $order->customer['name'] ?? (($order->customer['first_name'] ?? '') . ' ' . ($order->customer['last_name'] ?? '')) ?? 'N/A' }}</h4>
                                        <div class="flex items-center mt-1">
                                            <p class="text-sm text-gray-500">{{ $order->customer['user_type'] ?? 'Load Owner' }}</p>
                                            @if(isset($order->customer['driver_verified']) && $order->customer['driver_verified'])
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Verified
                                                </span>
                                            @endif
                                            @if(isset($order->customer['isOnline']) && $order->customer['isOnline'])
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <span class="mr-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                                    Online
                                                </span>
                                            @endif
                                            @if(isset($order->customer['isBanned']) && $order->customer['isBanned'])
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Banned
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <dl class="grid grid-cols-1 gap-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ ($order->customer['first_name'] ?? '') . ' ' . ($order->customer['last_name'] ?? '') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->customer['email'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->customer['phone_number'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->customer['gender'] ?? 'N/A') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $order->customer['city_town'] ?? 'N/A' }}, 
                                            {{ $order->customer['province'] ?? 'N/A' }}, 
                                            {{ $order->customer['country'] ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">NRC</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->customer['nrc'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">User ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-mono text-xs">{{ $order->customer['uid'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Staff Status</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            @if(isset($order->customer['is_staff']) && $order->customer['is_staff'])
                                                <span class="text-blue-600">Staff Member</span>
                                            @else
                                                <span class="text-gray-600">Regular User</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            @if(isset($order->customer['isBanned']) && $order->customer['isBanned'])
                                                <span class="text-red-600">Banned</span>
                                                @if(isset($order->customer['banReason']) && !empty($order->customer['banReason']))
                                                    <span class="text-xs text-gray-500 block">Reason: {{ $order->customer['banReason'] }}</span>
                                                @endif
                                            @else
                                                <span class="text-green-600">Active</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->customer['added_date'] ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            @else
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Load Owner Information</h3>
                                    <p class="mt-1 text-sm text-gray-500">Load owner information could not be retrieved for this booking.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Transporter Information Card -->
                    @if(isset($order->driver) || isset($order->truck))
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Transporter Information</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            @if(isset($order->driver))
                                <div class="flex items-center mb-4">
                                    @if(isset($order->driver['profile_image']))
                                        <img src="{{ $order->driver['profile_image'] }}" alt="Profile" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $order->driver['name'] ?? 'N/A' }}</h4>
                                        <div class="flex items-center mt-1">
                                            <span class="text-sm text-gray-500">Driver</span>
                                            @if(isset($order->driver['verified']) && $order->driver['verified'])
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Verified
                                                </span>
                                            @endif
                                            @if(isset($order->driver['isOnline']) && $order->driver['isOnline'])
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <span class="mr-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                                    Online
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <dl class="grid grid-cols-1 gap-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->driver['name'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->driver['email'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->driver['phone_number'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">License Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $order->driver['license_number'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Verification Status</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            @if(isset($order->driver['verified']) && $order->driver['verified'])
                                                <span class="text-green-600">Verified</span>
                                            @else
                                                <span class="text-yellow-600">Unverified</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            @else
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Transporter Assigned</h3>
                                    <p class="mt-1 text-sm text-gray-500">No transporter has been assigned to this booking yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Truck Assignment Modal (if needed for approval) -->
@if($order->order_status === 'pending')
<div id="truck-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
            <div class="text-center mb-6">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 mb-4">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Confirm Booking</h3>
                <p class="text-sm text-gray-500 mt-2">
                    @if($order->truck_id)
                        Review the assigned truck details and confirm the booking.
                    @else
                        Assign a truck to this booking and approve the request.
                    @endif
                </p>
            </div>
            
            <form action="{{ route('admin.bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="approve">
                <input type="hidden" name="fcm_token" value="{{ $order->customer['fcm_token'] ?? '' }}">
                <input type="hidden" name="phone_number" value="{{ $order->customer['phone_number'] ?? '' }}">
                
                <!-- Truck Information Display (if truck exists) -->
                @if($order->truck_id && isset($order->truck))
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="text-sm font-medium text-blue-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Assigned Truck Details
                    </h4>
                    <div class="grid grid-cols-2 gap-4 text-xs text-blue-800">
                        <div>
                            <p><span class="font-medium">Transporter:</span> {{ $order->truck['transporter_name'] ?? 'N/A' }}</p>
                            <p><span class="font-medium">Phone:</span> {{ $order->truck['transporter_phone'] ?? 'N/A' }}</p>
                            <p><span class="font-medium">License:</span> {{ $order->truck['license_number'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Model:</span> {{ $order->truck['model'] ?? 'N/A' }}</p>
                            <p><span class="font-medium">Tonnage:</span> {{ $order->truck['tonnage'] ?? 'N/A' }}</p>
                            <p><span class="font-medium">Status:</span> {{ $order->truck['status'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Truck Selection Field -->
                <div class="mb-6">
                    <label for="truck_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Truck Document ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="truck_id" 
                           name="truck_id" 
                           value="{{ $order->truck_id ?? '' }}"
                           required
                           @if($order->truck_id) readonly @endif
                           placeholder="@if(!$order->truck_id)Enter truck document ID (e.g., TB000001)@endif"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @if($order->truck_id) bg-gray-50 text-gray-600 cursor-not-allowed @endif">
                    @if($order->truck_id)
                        <p class="mt-1 text-xs text-green-600 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Truck already assigned to this booking
                        </p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Enter the unique document ID of the truck to assign to this booking.</p>
                    @endif
                </div>
                
                <!-- Order Summary -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Booking Summary</h4>
                    <div class="text-xs text-gray-600 space-y-2">
                        <div class="flex justify-between">
                            <span class="font-medium">Order ID:</span>
                            <span>{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Customer:</span>
                            <span>{{ $order->customer['name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Commodity:</span>
                            <span>{{ $order->commodity ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Tonnage:</span>
                            <span>{{ $order->tonnage ?? 'N/A' }} tons</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeTruckModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @if($order->truck_id)
                            Confirm Booking
                        @else
                            Assign & Confirm
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<!-- Google Maps styling -->
<style>
    #route-map {
        width: 100%;
        height: 264px;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
    }
    
    .gm-style-iw {
        padding: 8px !important;
    }
    
    .gm-style-iw-d {
        overflow: hidden !important;
    }
</style>
@endpush

@push('scripts')
<!-- Google Maps JavaScript API -->
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEqUCL-P9s2RrzRbaL5F99aVOHzBTOq6o&callback=initMapCallback&libraries=geometry">
</script>

<script>
// Global variables for Google Maps and rate limiting
let map;
let markers = [];
let routePath;
const locationCache = new Map();
const requestQueue = [];
let isProcessingQueue = false;
let isInitialized = false;
const REQUEST_DELAY = 2000;
const MAX_RETRIES = 2;

// Modal functions
function openTruckModal() {
    document.getElementById('truck-modal').classList.remove('hidden');
}

function closeTruckModal() {
    document.getElementById('truck-modal').classList.add('hidden');
}

// Rate-limited fetch function with better error handling
async function queuedFetch(url) {
    return new Promise((resolve, reject) => {
        requestQueue.push({ url, resolve, reject });
        if (!isProcessingQueue) {
            processQueue();
        }
    });
}

// Process request queue with rate limiting
async function processQueue() {
    if (isProcessingQueue || requestQueue.length === 0) {
        return;
    }
    
    isProcessingQueue = true;
    
    while (requestQueue.length > 0) {
        const { url, resolve, reject } = requestQueue.shift();
        
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
            
            const response = await fetch(url, {
                headers: {
                    'User-Agent': 'LoadMasta Admin Panel (contact@loadmasta.com)'
                },
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            resolve(response);
        } catch (error) {
            reject(error);
        }
        
        // Wait before processing next request
        if (requestQueue.length > 0) {
            await new Promise(resolve => setTimeout(resolve, REQUEST_DELAY));
        }
    }
    
    isProcessingQueue = false;
}

// OpenStreetMap reverse geocoding function with better error handling
async function getLocationName(lat, lon, retryCount = 0) {
    if (!lat || !lon || (lat == 0 && lon == 0)) {
        return 'Location not available';
    }
    
    // Create cache key
    const cacheKey = `${lat.toFixed(6)},${lon.toFixed(6)}`;
    
    // Check cache first
    if (locationCache.has(cacheKey)) {
        return locationCache.get(cacheKey);
    }
    
    try {
        // Add request to queue for rate limiting
        const response = await queuedFetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`);
        
        if (!response.ok) {
            if (response.status === 429) {
                throw new Error('Rate limit exceeded');
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        let locationName = 'Location name not found';
        
        if (data && data.display_name) {
            // Extract meaningful parts of the address
            const address = data.address || {};
            let locationParts = [];
            
            // Add specific location details in order of preference
            if (address.house_number && address.road) {
                locationParts.push(`${address.house_number} ${address.road}`);
            } else if (address.road) {
                locationParts.push(address.road);
            } else if (address.neighbourhood) {
                locationParts.push(address.neighbourhood);
            } else if (address.suburb) {
                locationParts.push(address.suburb);
            }
            
            if (address.city || address.town || address.village) {
                locationParts.push(address.city || address.town || address.village);
            }
            
            if (address.state || address.province) {
                locationParts.push(address.state || address.province);
            }
            
            if (address.country) {
                locationParts.push(address.country);
            }
            
            locationName = locationParts.length > 0 ? locationParts.join(', ') : data.display_name;
        }
        
        // Cache the result
        locationCache.set(cacheKey, locationName);
        return locationName;
        
    } catch (error) {
        console.error('Error fetching location name:', error);
        
        // Handle specific error types
        if (error.name === 'AbortError') {
            return 'Request timeout - location name unavailable';
        }
        
        if (error.message.includes('Failed to fetch') || error.message.includes('ERR_CONNECTION_REFUSED')) {
            return 'Network error - location name unavailable';
        }
        
        // Retry logic with exponential backoff for rate limiting
        if (retryCount < MAX_RETRIES && (error.message.includes('Rate limit') || error.message.includes('429'))) {
            const delay = Math.pow(2, retryCount) * 3000; // 3s, 6s delays
            console.log(`Rate limited. Retrying in ${delay}ms (attempt ${retryCount + 1}/${MAX_RETRIES})`);
            await new Promise(resolve => setTimeout(resolve, delay));
            return getLocationName(lat, lon, retryCount + 1);
        }
        
        return 'Error loading location name';
    }
}

// Initialize Google Maps
function initMap() {
    try {
        // Initialize map centered on pickup location
        const center = { 
            lat: {{ $order->initial_latitude }}, 
            lng: {{ $order->initial_longitude }} 
        };

        map = new google.maps.Map(document.getElementById('route-map'), {
            zoom: 8,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'on' }]
                }
            ],
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
            zoomControl: true
        });

        // Define locations
        const locations = [
            {
                position: { lat: {{ $order->initial_latitude }}, lng: {{ $order->initial_longitude }} },
                title: 'Pickup Location',
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    fillColor: '#3B82F6',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 3
                },
                content: '<div style="padding: 8px;"><strong>Pickup Location</strong><br>{{ $order->initial_latitude }}, {{ $order->initial_longitude }}</div>'
            },
            @if($order->mer_latitude != 0 && $order->mer_longitude != 0)
            {
                position: { lat: {{ $order->mer_latitude }}, lng: {{ $order->mer_longitude }} },
                title: 'Driver Location',
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    fillColor: '#10B981',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 3
                },
                content: '<div style="padding: 8px;"><strong>Driver Location</strong><br>{{ $order->mer_latitude }}, {{ $order->mer_longitude }}</div>'
            },
            @endif
            {
                position: { lat: {{ $order->destination_latitude }}, lng: {{ $order->destination_longitude }} },
                title: 'Destination',
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    fillColor: '#EF4444',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 3
                },
                content: '<div style="padding: 8px;"><strong>Destination</strong><br>{{ $order->destination_latitude }}, {{ $order->destination_longitude }}</div>'
            }
        ];

        // Add markers
        const bounds = new google.maps.LatLngBounds();
        
        locations.forEach((location, index) => {
            const marker = new google.maps.Marker({
                position: location.position,
                map: map,
                title: location.title,
                icon: location.icon,
                animation: google.maps.Animation.DROP
            });

            const infoWindow = new google.maps.InfoWindow({
                content: location.content
            });

            marker.addListener('click', () => {
                // Close other info windows
                markers.forEach(m => {
                    if (m.infoWindow) {
                        m.infoWindow.close();
                    }
                });
                infoWindow.open(map, marker);
            });

            marker.infoWindow = infoWindow;
            markers.push(marker);
            bounds.extend(location.position);
        });

        // Create route path
        const routeCoordinates = [
            { lat: {{ $order->initial_latitude }}, lng: {{ $order->initial_longitude }} },
            @if($order->mer_latitude != 0 && $order->mer_longitude != 0)
            { lat: {{ $order->mer_latitude }}, lng: {{ $order->mer_longitude }} },
            @endif
            { lat: {{ $order->destination_latitude }}, lng: {{ $order->destination_longitude }} }
        ];

        routePath = new google.maps.Polyline({
            path: routeCoordinates,
            geodesic: true,
            strokeColor: '#6366F1',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            icons: [{
                icon: {
                    path: 'M 0,-1 0,1',
                    strokeOpacity: 1,
                    scale: 4
                },
                offset: '0',
                repeat: '20px'
            }]
        });

        routePath.setMap(map);

        // Fit map to show all markers
        if (markers.length > 0) {
            map.fitBounds(bounds);
            
            // Ensure minimum zoom level
            google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                if (map.getZoom() > 15) {
                    map.setZoom(15);
                }
            });
        }

    } catch (error) {
        console.error('Error initializing Google Maps:', error);
        document.getElementById('route-map').innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500"><p>Error loading map</p></div>';
    }
}

// Fetch and display location names using OpenStreetMap
async function loadLocationNames() {
    // Load pickup location name
    try {
        const pickupName = await getLocationName({{ $order->initial_latitude }}, {{ $order->initial_longitude }});
        const element = document.getElementById('initial-location-name');
        if (element) {
            element.innerHTML = `<span class="text-blue-600">${pickupName}</span>`;
        }
    } catch (error) {
        console.error('Error loading pickup location name:', error);
        const element = document.getElementById('initial-location-name');
        if (element) {
            element.innerHTML = '<span class="text-red-500">Error loading location name</span>';
        }
    }

    // Load driver location name (if available)
    @if($order->mer_latitude != 0 && $order->mer_longitude != 0)
    try {
        const driverName = await getLocationName({{ $order->mer_latitude }}, {{ $order->mer_longitude }});
        const element = document.getElementById('merchant-location-name');
        if (element) {
            element.innerHTML = `<span class="text-green-600">${driverName}</span>`;
        }
    } catch (error) {
        console.error('Error loading driver location name:', error);
        const element = document.getElementById('merchant-location-name');
        if (element) {
            element.innerHTML = '<span class="text-red-500">Error loading location name</span>';
        }
    }
    @endif

    // Load destination name
    try {
        const destinationName = await getLocationName({{ $order->destination_latitude }}, {{ $order->destination_longitude }});
        const element = document.getElementById('destination-location-name');
        if (element) {
            element.innerHTML = `<span class="text-red-600">${destinationName}</span>`;
        }
    } catch (error) {
        console.error('Error loading destination location name:', error);
        const element = document.getElementById('destination-location-name');
        if (element) {
            element.innerHTML = '<span class="text-red-500">Error loading location name</span>';
        }
    }
}

// Initialize everything when the page loads or when Google Maps API is ready
function initializeApp() {
    if (isInitialized) {
        return; // Prevent re-initialization
    }
    
    isInitialized = true;
    
    // Load location names first
    loadLocationNames();
    
    // Initialize map if Google Maps is available
    if (typeof google !== 'undefined' && google.maps) {
        initMap();
    }
}

// Google Maps callback function - must be globally accessible
window.initMapCallback = function() {
    console.log('Google Maps API loaded successfully');
    initializeApp();
};

// Fallback initialization if Google Maps is already loaded
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure all scripts are loaded
    setTimeout(() => {
        if (typeof google !== 'undefined' && google.maps && !isInitialized) {
            console.log('Google Maps already loaded, initializing...');
            initializeApp();
        }
    }, 100);
});

// Fallback for network errors - show coordinates only
window.addEventListener('load', function() {
    setTimeout(() => {
        // Check if location names are still loading after 10 seconds
        const loadingElements = document.querySelectorAll('[id$="-location-name"]');
        loadingElements.forEach(element => {
            if (element && element.innerHTML.includes('Loading location name')) {
                element.innerHTML = '<span class="text-gray-500">Coordinates only (network unavailable)</span>';
            }
        });
    }, 10000);
});
</script>
@endpush
