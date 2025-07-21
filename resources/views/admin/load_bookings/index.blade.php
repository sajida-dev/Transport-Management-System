@extends('admin.layouts.app')

@section('title', 'Load Bookings Management')

@php
$is_active = function($button_selection, $current_selection) {
    return $button_selection === $current_selection ? 'text-indigo-600 border-indigo-500' : 'text-gray-500 hover:text-gray-700 border-transparent';
};

$getStatusBadgeClass = function($status) {
    return match($status) {
        'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
        'approved' => 'bg-blue-100 text-blue-800 ring-blue-600/20',
        'in_transit' => 'bg-cyan-100 text-cyan-800 ring-cyan-600/20',
        'completed' => 'bg-green-100 text-green-800 ring-green-600/20',
        'cancelled' => 'bg-red-100 text-red-800 ring-red-600/20',
        default => 'bg-gray-100 text-gray-800 ring-gray-600/20'
    };
};
@endphp

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">Load Bookings Management</h1>
            <p class="mt-2 text-sm text-gray-700">Manage bookings associated with specific loads.</p>
        </div>
        {{-- Optional Add Button --}}
        {{-- <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                 Manual Load Booking
            </button>
        </div> --}}
    </div>

    <!-- Filter Tabs -->
    <div class="mt-4 border-b border-gray-200 pb-5 sm:pb-0">
        <div class="mt-3 sm:mt-4">
            <div class="sm:hidden">
                <label for="current-tab" class="sr-only">Select a tab</label>
                <select id="current-tab" name="current-tab" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" onchange="window.location.href = this.value;">
                    <option value="{{ route('admin.load_bookings', ['selection' => 'all']) }}" {{ $selection == 'all' ? 'selected' : '' }}>All</option>
                    <option value="{{ route('admin.load_bookings', ['selection' => 'pending']) }}" {{ $selection == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="{{ route('admin.load_bookings', ['selection' => 'confirmed']) }}" {{ $selection == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="{{ route('admin.load_bookings', ['selection' => 'in_transit']) }}" {{ $selection == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="{{ route('admin.load_bookings', ['selection' => 'completed']) }}" {{ $selection == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="{{ route('admin.load_bookings', ['selection' => 'cancelled']) }}" {{ $selection == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.load_bookings', ['selection' => 'all']) }}" 
                       class="{{ $is_active('all', $selection) }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">All</a>
                    <a href="{{ route('admin.load_bookings', ['selection' => 'pending']) }}" 
                       class="{{ $is_active('pending', $selection) }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Pending</a>
                    <a href="{{ route('admin.load_bookings', ['selection' => 'confirmed']) }}" 
                       class="{{ $is_active('confirmed', $selection) }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Confirmed</a>
                    <a href="{{ route('admin.load_bookings', ['selection' => 'in_transit']) }}" 
                       class="{{ $is_active('in_transit', $selection) }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">In Transit</a>
                    <a href="{{ route('admin.load_bookings', ['selection' => 'completed']) }}" 
                       class="{{ $is_active('completed', $selection) }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Completed</a>
                    <a href="{{ route('admin.load_bookings', ['selection' => 'cancelled']) }}" 
                       class="{{ $is_active('cancelled', $selection) }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Cancelled</a>
                </nav>
            </div>
        </div>
    </div>

    @if(isset($error))
        <div class="mt-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400 h-5 w-5"></i>
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

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Booking ID / Load</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Route</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Load Owner</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Transporter</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Booking Date</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($loadOrders as $order)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center">
                                            <i class="fas fa-file-invoice text-2xl text-gray-400"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">Booking #{{ $order->id }}</div>
                                            <div class="text-gray-500">Load: {{ $order->load_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div>{{ $order->load_pickup ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">to</div>
                                    <div>{{ $order->load_dropoff_destination ?? 'N/A' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-gray-900">{{ $order->customer->name ?? 'N/A' }}</div>
                                    <div class="text-gray-500">{{ $order->customer->email ?? 'N/A' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-gray-900">{{ $order->transporter->name ?? 'N/A' }}</div>
                                    <div class="text-gray-500">{{ $order->transporter->plate_number ?? 'N/A' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $getStatusBadgeClass($order->order_status) }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->added_date)->format('M d, Y H:i') }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="{{ route('admin.load_bookings.show', ['selection' => $selection, 'id' => $order->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View Details</a>
                                    @if ($order->order_status === 'pending')
                                        <form action="{{ route('admin.load_bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="confirm">
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Confirm</button>
                                        </form>
                                        <form id="cancelForm-{{ $order->id }}" action="{{ route('admin.load_bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="cancel">
                                            <input type="hidden" name="cancellation_reason" id="rejectReason-{{ $order->id }}">
                                            <button type="button" onclick="openRejectModal('{{ $order->id }}')" class="text-red-600 hover:text-red-900">Cancel</button>
                                        </form>
                                    @elseif ($order->order_status === 'cancelled' && isset($order->reject_reason))
                                        <button type="button" onclick="showRejectionReason('{{ $order->reject_reason }}')" class="text-gray-600 hover:text-gray-900">View Rejection Reason</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                                    No load bookings found for this status.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Cancel Load Booking</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Please provide a reason for cancelling this load booking. This will be visible to the customer.</p>
                            <textarea id="rejectionReasonText" rows="3" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitRejection()" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Cancel Booking</button>
                    <button type="button" onclick="closeRejectModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Rejection Reason Modal -->
<div id="viewRejectReasonModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Rejection Reason</h3>
                        <div class="mt-2">
                            <p id="viewRejectionReasonText" class="text-sm text-gray-500"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeViewRejectReasonModal()" class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentOrderId = null;

    function openRejectModal(orderId) {
        currentOrderId = orderId;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectionReasonText').value = '';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        currentOrderId = null;
    }

    function submitRejection() {
        const reason = document.getElementById('rejectionReasonText').value.trim();
        if (!reason) {
            alert('Please provide a rejection reason');
            return;
        }
        
        document.getElementById(`rejectReason-${currentOrderId}`).value = reason;
        document.getElementById(`cancelForm-${currentOrderId}`).submit();
    }

    function showRejectionReason(reason) {
        document.getElementById('viewRejectionReasonText').textContent = reason;
        document.getElementById('viewRejectReasonModal').classList.remove('hidden');
    }

    function closeViewRejectReasonModal() {
        document.getElementById('viewRejectReasonModal').classList.add('hidden');
    }

    // Close modals when clicking outside or pressing escape
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRejectModal();
            closeViewRejectReasonModal();
        }
    });

    document.addEventListener('click', function(event) {
        const rejectModal = document.getElementById('rejectModal');
        const viewRejectReasonModal = document.getElementById('viewRejectReasonModal');
        
        if (event.target === rejectModal) {
            closeRejectModal();
        }
        if (event.target === viewRejectReasonModal) {
            closeViewRejectReasonModal();
        }
    });
</script>
@endpush 