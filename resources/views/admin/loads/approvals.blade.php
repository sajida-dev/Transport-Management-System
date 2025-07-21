@extends('admin.layouts.app')

@section('title', 'Load Approvals Management')

@php
// Helper function for active tab styling
$is_active = function($button_selection, $current_selection) {
    return $button_selection === $current_selection ? 'text-indigo-600 border-indigo-500' : 'text-gray-500 hover:text-gray-700 border-transparent';
};

// Helper function for status badge styling
$getStatusBadgeClass = function($status) {
    return match($status) {
        'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
        'approved' => 'bg-blue-100 text-blue-700 ring-blue-700/10',
        'in_transit' => 'bg-cyan-100 text-cyan-700 ring-cyan-700/10',
        'completed' => 'bg-green-100 text-green-700 ring-green-600/20',
        'cancelled' => 'bg-red-100 text-red-700 ring-red-600/10',
        default => 'bg-gray-100 text-gray-600 ring-gray-500/10'
    };
};
@endphp

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Load Approvals</h1>
            <p class="mt-1 text-sm text-gray-500">Manage and track load approval requests</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <div class="sm:hidden">
            <label for="current-tab" class="sr-only">Select a status</label>
            <select id="current-tab" name="current-tab" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" onchange="window.location.href = this.value;">
                <option value="{{ route('admin.loadApprovals', ['selection' => 'all']) }}" {{ $selection == 'all' ? 'selected' : '' }}>All Loads</option>
                <option value="{{ route('admin.loadApprovals', ['selection' => 'pending']) }}" {{ $selection == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="{{ route('admin.loadApprovals', ['selection' => 'approved']) }}" {{ $selection == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="{{ route('admin.loadApprovals', ['selection' => 'in_transit']) }}" {{ $selection == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                <option value="{{ route('admin.loadApprovals', ['selection' => 'completed']) }}" {{ $selection == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="{{ route('admin.loadApprovals', ['selection' => 'cancelled']) }}" {{ $selection == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="hidden sm:block">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('admin.loadApprovals', ['selection' => 'all']) }}" 
                   class="{{ $is_active('all', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                    All Loads
                </a>
                <a href="{{ route('admin.loadApprovals', ['selection' => 'pending']) }}" 
                   class="{{ $is_active('pending', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                    Pending
                </a>
                <a href="{{ route('admin.loadApprovals', ['selection' => 'approved']) }}" 
                   class="{{ $is_active('approved', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                    Approved
                </a>
                <a href="{{ route('admin.loadApprovals', ['selection' => 'in_transit']) }}" 
                   class="{{ $is_active('in_transit', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                    In Transit
                </a>
                <a href="{{ route('admin.loadApprovals', ['selection' => 'completed']) }}" 
                   class="{{ $is_active('completed', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                    Completed
                </a>
                <a href="{{ route('admin.loadApprovals', ['selection' => 'cancelled']) }}" 
                   class="{{ $is_active('cancelled', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                    Cancelled
                </a>
            </nav>
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

    <!-- Loads Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ ucfirst($selection) }} Loads
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ count($loads) }} loads found
                </p>
            </div>
            <div class="flex items-center">
                <div class="relative rounded-md shadow-sm">
                    <input type="text" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-10 py-2 sm:text-sm border-gray-300 rounded-md" placeholder="Search loads...">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Load Details</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($loads as $load)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100">
                                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Load #{{ $load->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $load->load_name ?? 'Unnamed Load' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $load->pickup_loc_name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 flex items-center">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                                {{ $load->dropoff_loc_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $load->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $load->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $getStatusBadgeClass($load->status ?? 'unknown') }}">
                                {{ ucfirst($load->status ?? 'unknown') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if(isset($load->added_date))
                                {{ \Carbon\Carbon::parse($load->added_date)->format('M d, Y H:i') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.loadApprovals.detail', ['selection' => $selection, 'id' => $load->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View Details</a>
                            @if ($load->status === 'pending')
                                <button type="button" onclick="approveLoad('{{ $load->id }}')" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                <button type="button" onclick="rejectLoad('{{ $load->id }}')" class="text-red-600 hover:text-red-900">Reject</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No loads found for this status.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Approve Load Modal -->
<div id="approveLoadModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Approve Load
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to approve this load? This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <form id="approveLoadForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm">
                        Approve
                    </button>
                </form>
                <button type="button" onclick="closeApproveModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Load Modal -->
<div id="rejectLoadModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Reject Load
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to reject this load? This action cannot be undone.
                        </p>
                        <div class="mt-4">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <form id="rejectLoadForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="rejection_reason" id="rejection_reason_input">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                        Reject
                    </button>
                </form>
                <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('search').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Approve load function
    function approveLoad(loadId) {
        const modal = document.getElementById('approveLoadModal');
        const form = document.getElementById('approveLoadForm');
        form.action = "{{ route('admin.loadApprovals.submit', ['selection' => $selection, 'load_id' => ':loadId']) }}".replace(':loadId', loadId);
        modal.classList.remove('hidden');
    }

    // Reject load function
    function rejectLoad(loadId) {
        const modal = document.getElementById('rejectLoadModal');
        const form = document.getElementById('rejectLoadForm');
        form.action = "{{ route('admin.loadApprovals.submit', ['selection' => $selection, 'load_id' => ':loadId']) }}".replace(':loadId', loadId);
        modal.classList.remove('hidden');
    }

    // Close approve modal
    function closeApproveModal() {
        const modal = document.getElementById('approveLoadModal');
        modal.classList.add('hidden');
    }

    // Close reject modal
    function closeRejectModal() {
        const modal = document.getElementById('rejectLoadModal');
        modal.classList.add('hidden');
    }

    // Update rejection reason before form submission
    document.getElementById('rejectLoadForm').addEventListener('submit', function(e) {
        const reason = document.getElementById('rejection_reason').value;
        document.getElementById('rejection_reason_input').value = reason;
    });
</script>
@endsection 