@extends('admin.layouts.app')

@section('title', 'Truck Bookings Management')

@php
$is_active = function($button_selection, $current_selection) {
    return $button_selection === $current_selection ? 'text-indigo-600 border-indigo-500' : 'text-gray-500 hover:text-gray-700 border-transparent';
};
@endphp

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    @if(session('error'))
        <div class="mt-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(isset($error))
        <div class="mt-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ $error }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">Truck Bookings Management</h1>
            <p class="mt-2 text-sm text-gray-700">Manage direct truck bookings made through the system.</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mt-4 border-b border-gray-200 pb-5 sm:pb-0">
        <div class="mt-3 sm:mt-4">
            <div class="sm:hidden">
                <label for="current-tab" class="sr-only">Select a tab</label>
                <select id="current-tab" name="current-tab" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" onchange="window.location.href = this.value;">
                    <option value="{{ route('admin.bookings', ['selection' => 'all']) }}" {{ $selection == 'all' ? 'selected' : '' }}>All</option>
                    <option value="{{ route('admin.bookings', ['selection' => 'pending']) }}" {{ $selection == 'pending' ? 'selected' : '' }}>Pending Confirmation</option>
                    <option value="{{ route('admin.bookings', ['selection' => 'confirmed']) }}" {{ $selection == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="{{ route('admin.bookings', ['selection' => 'in_transit']) }}" {{ $selection == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="{{ route('admin.bookings', ['selection' => 'completed']) }}" {{ $selection == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="{{ route('admin.bookings', ['selection' => 'cancelled']) }}" {{ $selection == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.bookings', ['selection' => 'all']) }}" 
                       class="{{ $is_active('all', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">All</a>
                    <a href="{{ route('admin.bookings', ['selection' => 'pending']) }}" 
                       class="{{ $is_active('pending', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Pending</a>
                    <a href="{{ route('admin.bookings', ['selection' => 'confirmed']) }}" 
                       class="{{ $is_active('confirmed', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Confirmed</a>
                    <a href="{{ route('admin.bookings', ['selection' => 'in_transit']) }}" 
                       class="{{ $is_active('in_transit', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">In Transit</a>
                    <a href="{{ route('admin.bookings', ['selection' => 'completed']) }}" 
                       class="{{ $is_active('completed', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Completed</a>
                    <a href="{{ route('admin.bookings', ['selection' => 'cancelled']) }}" 
                       class="{{ $is_active('cancelled', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Cancelled</a>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Booking ID / Truck</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Route</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer (Booker)</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Transporter</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Booking Date</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-full bg-indigo-100">
                                                <i class="fas fa-truck text-indigo-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">#{{ $booking->id }}</div>
                                                <div class="text-gray-500">{{ $booking->truck->plate_number ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">{{ $booking->origin ?? 'From: ' . ($booking->initial_latitude ?? 'N/A') . ', ' . ($booking->initial_longitude ?? 'N/A') }}</span>
                                            <span class="text-xs text-gray-400">to</span>
                                            <span class="font-medium text-gray-900">{{ $booking->destination ?? 'To: ' . ($booking->destination_latitude ?? 'N/A') . ', ' . ($booking->destination_longitude ?? 'N/A') }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">{{ $booking->customer->name ?? 'N/A' }}</span>
                                            <span class="text-xs text-gray-500">{{ $booking->customer->email ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">{{ $booking->transporter->name ?? $booking->truck->transporter_name ?? 'N/A' }}</span>
                                            <span class="text-xs text-gray-500">{{ $booking->truck->truck_type ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @php
                                            $status = $booking->order_status ?? 'unknown';
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
                                                'approved' => 'bg-blue-100 text-blue-700 ring-blue-700/10',
                                                'in_transit' => 'bg-cyan-100 text-cyan-700 ring-cyan-700/10',
                                                'completed' => 'bg-green-100 text-green-700 ring-green-600/20',
                                                'cancelled' => 'bg-red-100 text-red-700 ring-red-600/10',
                                                'unknown' => 'bg-gray-100 text-gray-600 ring-gray-500/10'
                                            ];
                                            $statusClass = $statusClasses[$status] ?? $statusClasses['unknown'];
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @if(isset($booking->formatted_added_date))
                                            {{ $booking->formatted_added_date }}
                                        @elseif(isset($booking->added_date))
                                            {{ \Carbon\Carbon::parse($booking->added_date)->format('M d, Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.bookings.detail', ['selection' => $selection, 'booking_id' => $booking->id]) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <span class="sr-only">View details for booking #{{ $booking->id }}</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        @if ($booking->order_status === 'pending')
                                            <form action="{{ route('admin.bookings.submit', ['selection' => $selection]) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                    <span class="sr-only">Approve booking #{{ $booking->id }}</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.bookings.submit', ['selection' => $selection]) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="cancel">
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <span class="sr-only">Cancel booking #{{ $booking->id }}</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @elseif($booking->order_status === 'in_transit')
                                            <form action="{{ route('admin.bookings.submit', ['selection' => $selection]) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="complete">
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                    <span class="sr-only">Complete booking #{{ $booking->id }}</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.bookings.submit', ['selection' => $selection]) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="cancel_in_transit">
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <span class="sr-only">Cancel in-transit booking #{{ $booking->id }}</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-12">
                                            <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                                            <p class="mt-1 text-sm text-gray-500">No truck bookings found for this status.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection