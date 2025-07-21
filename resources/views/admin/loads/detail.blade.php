@extends('admin.layouts.app')

@section('title', 'Load Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Load Details</h1>
            <p class="mt-1 text-sm text-gray-500">View and manage load information</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.loadApprovals', ['selection' => $selection]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Loads
            </a>
            @if ($load->status === 'pending')
                <button type="button" onclick="openApproveModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Approve Load
                </button>
                <button type="button" onclick="openRejectModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Reject Load
                </button>
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
        <!-- Load Status Banner -->
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full bg-indigo-100">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Load #{{ $load->id }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $load->load_name ?? 'Unnamed Load' }}</p>
                    </div>
                </div>
                <div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $getStatusBadgeClass($load->status ?? 'unknown') }}">
                        {{ ucfirst($load->status ?? 'unknown') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Load Details -->
        <div class="border-t border-gray-200">
            <dl>
                <!-- Route Information -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Route</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1">
                                <div class="font-medium">Pickup Location</div>
                                <div class="text-gray-500">{{ $load->pickup_loc_name ?? 'N/A' }}</div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <div class="flex-1">
                                <div class="font-medium">Dropoff Location</div>
                                <div class="text-gray-500">{{ $load->dropoff_loc_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </dd>
                </div>

                <!-- Customer Information -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer Details</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                @if(!empty($load->user['profileImage']) && $load->user['profileImage'] !== 'N/A')
                                    <img src="{{ $load->user['profileImage'] }}" alt="Profile Picture" class="h-16 w-16 rounded-full object-cover">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="font-medium text-gray-900">Full Name</div>
                                        <div class="text-gray-500">{{ $load->user['name'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Gender</div>
                                        <div class="text-gray-500">{{ ucfirst($load->user['gender'] ?? 'N/A') }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Email</div>
                                        <div class="text-gray-500">{{ $load->user['email'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Phone Number</div>
                                        <div class="text-gray-500">{{ $load->user['phone_number'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">NRC Number</div>
                                        <div class="text-gray-500">{{ $load->user['nrc'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Country</div>
                                        <div class="text-gray-500">{{ $load->user['country'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Province</div>
                                        <div class="text-gray-500">{{ $load->user['province'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">City/Town</div>
                                        <div class="text-gray-500">{{ $load->user['city_town'] ?? 'N/A' }}</div>
                                    </div>
                                    @if(isset($load->user['is_staff']) && $load->user['is_staff'])
                                    <div>
                                        <div class="font-medium text-gray-900">Account Type</div>
                                        <div class="text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Staff Account
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">User ID</div>
                                        <div class="text-gray-500 text-xs font-mono">{{ $load->user['uid'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dd>
                </div>

                <!-- Load Details -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Load Details</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="font-medium">Tonnage</div>
                                <div class="text-gray-500">{{ $load->tonage ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-medium">Rate</div>
                                <div class="text-gray-500">{{ $load->rate ?? '0' }} {{ $load->currency ?? 'USD' }}</div>
                            </div>
                            <div>
                                <div class="font-medium">Trailer Type</div>
                                <div class="text-gray-500">{{ $load->trailer ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-medium">Added Date</div>
                                <div class="text-gray-500">
                                    @if(isset($load->added_date))
                                        {{ \Carbon\Carbon::parse($load->added_date)->format('M d, Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    </dd>
                </div>

                <!-- Additional Information -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Additional Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-4">
                            <div>
                                <div class="font-medium">Description</div>
                                <div class="text-gray-500">{{ $load->description ?? 'No description provided' }}</div>
                            </div>
                            @if(isset($load->rejection_reason))
                            <div>
                                <div class="font-medium text-red-600">Rejection Reason</div>
                                <div class="text-gray-500">{{ $load->rejection_reason }}</div>
                            </div>
                            @endif
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Approve Load Modal -->
<div id="approveLoadModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
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
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="approveLoadForm" action="{{ route('admin.loadApprovals.submit', ['selection' => $selection, 'load_id' => $load->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Approve
                    </button>
                </form>
                <button type="button" onclick="closeApproveModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Load Modal -->
<div id="rejectLoadModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Reject Load
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to reject this load? This action cannot be undone.
                            </p>
                            <div class="mt-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                                <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="rejectLoadForm" action="{{ route('admin.loadApprovals.submit', ['selection' => $selection, 'load_id' => $load->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="rejection_reason" id="rejection_reason_input">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Reject
                    </button>
                </form>
                <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Function to open the approve modal
    function openApproveModal() {
        document.getElementById('approveLoadModal').classList.remove('hidden');
    }

    // Function to close the approve modal
    function closeApproveModal() {
        document.getElementById('approveLoadModal').classList.add('hidden');
    }

    // Function to open the reject modal
    function openRejectModal() {
        document.getElementById('rejectLoadModal').classList.remove('hidden');
    }

    // Function to close the reject modal
    function closeRejectModal() {
        document.getElementById('rejectLoadModal').classList.add('hidden');
    }

    // Update rejection reason before form submission
    document.getElementById('rejectLoadForm').addEventListener('submit', function(e) {
        const reason = document.getElementById('rejection_reason').value;
        document.getElementById('rejection_reason_input').value = reason;
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const approveModal = document.getElementById('approveLoadModal');
        const rejectModal = document.getElementById('rejectLoadModal');
        
        if (event.target === approveModal) {
            closeApproveModal();
        }
        if (event.target === rejectModal) {
            closeRejectModal();
        }
    });

    // Close modals when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeApproveModal();
            closeRejectModal();
        }
    });
</script>
@endpush