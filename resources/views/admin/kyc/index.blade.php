@extends('admin.layouts.app')

@section('title', 'KYC Applications')

@section('content')
<div class="space-y-8 py-8">
    <!-- Page header with improved styling -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                        <i class="fas fa-id-card text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">KYC Applications</h1>
                        <p class="mt-1 text-sm text-gray-500">Review and manage transporter verification requests</p>
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

    <!-- KYC Applications Table with improved styling -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Pending Applications</h2>
                <div class="flex items-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <span class="h-2 w-2 rounded-full bg-yellow-500 mr-2"></span>
                        {{ $kycApplications->total() }} Pending
                    </span>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Details</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Truck Details</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kycApplications as $application)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">{{ substr($application->driver_first_name ?? 'N/A', 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $application->driver_first_name ?? 'N/A' }} {{ $application->driver_last_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $application->driver_country ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="font-medium">License:</span> {{ $application->license_number ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span class="font-medium">Trailer:</span> {{ $application->trailer_number ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span class="font-medium">Tonnage:</span> {{ $application->tonage ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="font-medium">Plate:</span> {{ $application->truck_plate_number ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span class="font-medium">Model:</span> {{ $application->truck_model ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <a href="{{ $application->nrc_document ?? '#' }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                        <i class="fas fa-file-alt mr-1"></i> View NRC
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                        {{ isset($application->added_date) ? \Carbon\Carbon::parse($application->added_date)->format('M d, Y') : 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.kyc.detail', $application->id) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <button onclick="approveKyc('{{ $application->id }}')" class="text-green-600 hover:text-green-900 inline-flex items-center">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                    <button onclick="rejectKyc('{{ $application->id }}')" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 rounded-full p-3 mb-3">
                                        <i class="fas fa-inbox text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">No pending KYC applications found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination with improved styling -->
        @if($kycApplications->count() > 0 && $kycApplications->hasPages())
        <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($kycApplications->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                        Previous
                    </span>
                @else
                    <a href="{{ $kycApplications->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                @endif
                
                @if($kycApplications->hasMorePages())
                    <a href="{{ $kycApplications->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                @else
                    <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                        Next
                    </span>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $kycApplications->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $kycApplications->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $kycApplications->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    {{ $kycApplications->links() }}
                </div>
            </div>
        </div>
        @endif
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

function approveKyc(id) {
    if (confirm('Are you sure you want to approve this KYC application?')) {
        fetch(`/admin/kyc/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFlashMessage('KYC application approved successfully.', 'success');
                // Reload the page after a short delay to show the flash message
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showFlashMessage('Failed to approve KYC application: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('An error occurred while approving the KYC application', 'error');
        });
    }
}

function rejectKyc(id) {
    const reason = prompt('Please enter the reason for rejection:');
    if (reason) {
        fetch(`/admin/kyc/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFlashMessage('KYC application rejected successfully.', 'success');
                // Reload the page after a short delay to show the flash message
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showFlashMessage('Failed to reject KYC application: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('An error occurred while rejecting the KYC application', 'error');
        });
    }
}
</script>
@endpush
@endsection 