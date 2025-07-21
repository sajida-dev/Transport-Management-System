@extends('admin.layouts.app')

@section('title', 'Load Management')

@php
$is_active = function($button_selection, $current_selection) {
    return $button_selection === $current_selection ? 'text-indigo-600 border-indigo-500' : 'text-gray-500 hover:text-gray-700 border-transparent';
};
@endphp

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">Load Management</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all loads posted in the system.</p>
        </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mt-4 border-b border-gray-200 pb-5 sm:pb-0">
        <div class="mt-3 sm:mt-4">
             <div class="sm:hidden">
                <label for="current-tab" class="sr-only">Select a tab</label>
                <select id="current-tab" name="current-tab" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" onchange="window.location.href = this.value;">
                    <option value="{{ route('admin.loads', ['selection' => 'all']) }}" {{ $selection == 'all' ? 'selected' : '' }}>All Loads</option>
                    <option value="{{ route('admin.loads', ['selection' => 'pending']) }}" {{ $selection == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="{{ route('admin.loads', ['selection' => 'approved']) }}" {{ $selection == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="{{ route('admin.loads', ['selection' => 'available']) }}" {{ $selection == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="{{ route('admin.loads', ['selection' => 'in_transit']) }}" {{ $selection == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="{{ route('admin.loads', ['selection' => 'completed']) }}" {{ $selection == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="{{ route('admin.loads', ['selection' => 'cancelled']) }}" {{ $selection == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                 </select>
            </div>
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                     <a href="{{ route('admin.loads', ['selection' => 'all']) }}" 
                       class="{{ $is_active('all', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">All Loads</a>
                    <a href="{{ route('admin.loads', ['selection' => 'pending']) }}" 
                       class="{{ $is_active('pending', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Pending</a>
                    <a href="{{ route('admin.loads', ['selection' => 'approved']) }}" 
                       class="{{ $is_active('approved', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Approved</a>
                    <a href="{{ route('admin.loads', ['selection' => 'available']) }}" 
                       class="{{ $is_active('available', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Available</a>
                    <a href="{{ route('admin.loads', ['selection' => 'in_transit']) }}" 
                       class="{{ $is_active('in_transit', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">In Transit</a>
                    <a href="{{ route('admin.loads', ['selection' => 'completed']) }}" 
                       class="{{ $is_active('completed', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Completed</a>
                    <a href="{{ route('admin.loads', ['selection' => 'cancelled']) }}" 
                       class="{{ $is_active('cancelled', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Cancelled</a>
                 </nav>
            </div>
        </div>
    </div>

    

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Load Details</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Route</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Load Owner</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cargo Info</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Dates</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($loads as $load)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                         <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center">
                                            <i class="fas fa-truck text-2xl text-gray-400"></i> 
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $load->load_name ?? $load->cargo_description ?? 'Untitled Load' }}</div>
                                            <div class="text-gray-500 text-xs">ID: {{ $load->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium">{{ $load->pickup_loc_name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400 my-1">
                                        <i class="fas fa-arrow-down"></i>
                                    </div>
                                    <div class="font-medium">{{ $load->dropoff_loc_name ?? 'N/A' }}</div>
                                    @if(isset($load->distance))
                                        <div class="text-xs text-gray-400 mt-1">{{ $load->distance }} km</div>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    @if(isset($load->user))
                                        <div class="font-medium">{{ $load->user['name'] ?? $load->user['first_name'] . ' ' . $load->user['last_name'] }}</div>
                                        <div class="text-xs">{{ $load->user['email'] ?? 'N/A' }}</div>
                                        @if(isset($load->user['phone']))
                                            <div class="text-xs">{{ $load->user['phone'] }}</div>
                                        @endif
                                    @else
                                        <div class="text-gray-400">User not found</div>
                                        @if(isset($load->user_error))
                                            <div class="text-xs text-red-500">{{ $load->user_error }}</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium">{{ $load->cargo_type ?? 'General Cargo' }}</div>
                                    @if(isset($load->weight))
                                        <div class="text-xs">Weight: {{ $load->weight }} {{ $load->weight_unit ?? 'kg' }}</div>
                                    @endif
                                    @if(isset($load->price))
                                        <div class="text-xs font-medium text-green-600">ZMW {{ number_format($load->price, 2) }}</div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @php
                                        $statusClass = match($load->status ?? 'unknown') {
                                            'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
                                            'approved' => 'bg-blue-100 text-blue-800 ring-blue-600/20',
                                            'available' => 'bg-green-100 text-green-800 ring-green-600/20',
                                            'unavailable' => 'bg-red-100 text-red-800 ring-red-600/20',
                                            'in_transit' => 'bg-purple-100 text-purple-800 ring-purple-600/20',
                                            'completed' => 'bg-gray-100 text-gray-800 ring-gray-600/20',
                                            'cancelled' => 'bg-red-100 text-red-800 ring-red-600/20',
                                            'rejected' => 'bg-red-100 text-red-800 ring-red-600/20',
                                            default => 'bg-gray-100 text-gray-600 ring-gray-500/10'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $load->status ?? 'Unknown')) }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    @if(isset($load->formatted_added_date))
                                        <div class="text-xs">
                                            <strong>Posted:</strong><br>
                                            {{ $load->formatted_added_date }}
                                        </div>
                                    @endif
                                    @if(isset($load->formatted_pickup_date))
                                        <div class="text-xs mt-1">
                                            <strong>Pickup:</strong><br>
                                            {{ $load->formatted_pickup_date }}
                                        </div>
                                    @endif
                                    @if(isset($load->formatted_delivery_date))
                                        <div class="text-xs mt-1">
                                            <strong>Delivery:</strong><br>
                                            {{ $load->formatted_delivery_date }}
                                        </div>
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <div class="flex flex-col space-y-1">
                                        <a href="{{ route('admin.loadApprovals.detail', ['selection' => $selection, 'id' => $load->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">View</a>

                                       
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-truck text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-lg font-medium">No loads found</p>
                                        <p class="text-sm">{{ $selection === 'all' ? 'No loads have been posted yet.' : 'No loads found for the selected status.' }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Load Count Summary -->
    @if(count($loads) > 0)
    <div class="mt-4 text-sm text-gray-600">
        Showing {{ count($loads) }} load(s) for status: <strong>{{ ucfirst(str_replace('_', ' ', $selection)) }}</strong>
    </div>
    @endif
</div>
@endsection