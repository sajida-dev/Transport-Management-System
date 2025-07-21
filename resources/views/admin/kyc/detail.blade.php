@extends('admin.layouts.app')

@section('title', 'KYC Application Details')

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
                        <h1 class="text-2xl font-bold text-gray-900">KYC Application Details</h1>
                        <p class="mt-1 text-sm text-gray-500">Review and verify transporter information</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.kyc') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Applications
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

    @if(isset($error))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Error</h3>
                <div class="mt-1 text-sm text-red-700">{{ $error }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Application Status Badge -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Application Status</h2>
                <div class="flex items-center">
                    @if($application->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <span class="h-2 w-2 rounded-full bg-yellow-500 mr-2"></span>
                            Pending Review
                        </span>
                    @elseif($application->status == 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <span class="h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                            Approved
                        </span>
                    @elseif($application->status == 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <span class="h-2 w-2 rounded-full bg-red-500 mr-2"></span>
                            Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Application Details -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Personal Information -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
            </div>
            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->transporterName ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">NRC Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->idNumber ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->transporterPhone ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->email ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->address ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- User Details -->
        @if(isset($application->user))
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">User Details</h2>
            </div>
            <div class="px-6 py-5">
                <div class="flex items-center space-x-4 mb-6">
                    @if($application->user['profileImage'])
                        <img src="{{ $application->user['profileImage'] }}" alt="Profile Image" class="h-20 w-20 rounded-full object-cover">
                    @else
                        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $application->user['first_name'] }} {{ $application->user['last_name'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $application->user['user_type'] }}</p>
                    </div>
                </div>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->user['phone_number'] }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">NRC Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->user['nrc'] }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->user['gender'] }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Country</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->user['country'] }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Province</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->user['province'] }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">City/Town</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->user['city_town'] }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Driver Verified</dt>
                        <dd class="mt-1">
                            @if($application->user['driver_verified'])
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Not Verified
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Online Status</dt>
                        <dd class="mt-1">
                            @if($application->user['isOnline'])
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="h-2 w-2 rounded-full bg-green-500 mr-1"></span> Online
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="h-2 w-2 rounded-full bg-gray-500 mr-1"></span> Offline
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->user['added_date'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        @endif

        <!-- Vehicle Information -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Vehicle Information</h2>
            </div>
            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">License Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->licenseNumber ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Trailer Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->trailerNumber ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tonnage</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->tonnage ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Truck Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->model ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Truck Plate Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $application->licenseNumber ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Documents -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden lg:col-span-2">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Documents</h2>
            </div>
            <div class="px-6 py-5">
                <div class="space-y-6">
                    <!-- ID Documents -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-4">Identification Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($application->idFrontUrl))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-id-card text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">ID Front</h3>
                                            <p class="text-xs text-gray-500">National ID Card (Front)</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->idFrontUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if(isset($application->idBackUrl))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-id-card text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">ID Back</h3>
                                            <p class="text-xs text-gray-500">National ID Card (Back)</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->idBackUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Driver's License -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-4">Driver's License</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($application->drivingLicenseFront))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-car text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">License Front</h3>
                                            <p class="text-xs text-gray-500">Driver's License (Front)</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->drivingLicenseFront }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if(isset($application->drivingLicenseBack))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-car text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">License Back</h3>
                                            <p class="text-xs text-gray-500">Driver's License (Back)</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->drivingLicenseBack }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Vehicle Documents -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-4">Vehicle Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($application->licenseUrl))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-file-alt text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">Vehicle License</h3>
                                            <p class="text-xs text-gray-500">Truck Registration</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->licenseUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if(isset($application->sideViewUrl))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-truck text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">Vehicle Side View</h3>
                                            <p class="text-xs text-gray-500">Truck Side Photo</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->sideViewUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if(isset($application->trailerUrl))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-trailer text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">Trailer Photo</h3>
                                            <p class="text-xs text-gray-500">Trailer Image</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->trailerUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Documents -->
                    <div>
                        <h3 class="text-md font-medium text-gray-700 mb-4">Additional Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($application->selfieImageUrl))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                            <i class="fas fa-user text-indigo-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900">Selfie</h3>
                                            <p class="text-xs text-gray-500">Driver's Selfie</p>
                                        </div>
                                    </div>
                                    <a href="{{ $application->selfieImageUrl }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if($application->status == 'pending')
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Actions</h2>
        </div>
        <div class="px-6 py-5">
            <div class="flex space-x-4">
                <button onclick="approveKyc('{{ $application->id }}')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i>
                    Approve Application
                </button>
                <button onclick="rejectKyc('{{ $application->id }}')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i>
                    Reject Application
                </button>
            </div>
        </div>
    </div>
    @endif
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
                // Redirect after a short delay to show the flash message
                setTimeout(() => {
                    window.location.href = '{{ route('admin.kyc') }}';
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
                // Redirect after a short delay to show the flash message
                setTimeout(() => {
                    window.location.href = '{{ route('admin.kyc') }}';
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