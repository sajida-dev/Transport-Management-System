@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Truck Details</h1>
                        <p class="mt-1 text-sm text-gray-500">View detailed information about this truck and its driver.</p>
                    </div>
                    <a href="{{ route('admin.trucks', ['selection' => 'all']) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Trucks
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Truck Information -->
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Truck Information</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Basic details about the truck.</p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Truck ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono">{{ $truck->id }}</span>
                                    <button onclick="copyToClipboard('{{ $truck->id }}')" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">License Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $truck->licenseNumber ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Model</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $truck->model ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Tonnage</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $truck->tonnage ?? 'N/A' }} tons</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $truck->status === 'approved' ? 'bg-green-100 text-green-800' : ($truck->status === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($truck->status ?? 'Unknown') }}
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Trailer Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $truck->trailerNumber ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Trailer Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $truck->trailerType ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Driver Information -->
                @if(isset($truck->user))
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Driver Information</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about the truck driver.</p>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-3">
                            <!-- Profile Image -->
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Profile Image</dt>
                                <dd class="mt-1">
                                    @if(isset($truck->user['profileImage']))
                                        <img src="{{ $truck->user['profileImage'] }}" alt="Driver Profile" class="h-24 w-24 rounded-full object-cover">
                                    @else
                                        <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['full_name'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">NRC</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['nrc'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['phone_number'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($truck->user['gender']) }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Country</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['country'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Province</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['province'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">City/Town</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['city_town'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">User Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['user_type'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Verification Status</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $truck->user['driver_verified'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $truck->user['driver_verified'] ? 'Verified' : 'Unverified' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Online Status</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $truck->user['isOnline'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $truck->user['isOnline'] ? 'Online' : 'Offline' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Registration Date</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->user['added_date'] }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif

                <!-- Location Information -->
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Location Information</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Current location details of the truck.</p>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $truck->address ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">City</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $truck->city ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Province</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $truck->province ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Documents & Images -->
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Documents & Images</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Truck-related documents and images.</p>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                            @if(isset($truck->drivingLicenseFront))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Driving License Front</p>
                                <img src="{{ $truck->drivingLicenseFront }}" alt="Driving License Front" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                            @if(isset($truck->drivingLicenseBack))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Driving License Back</p>
                                <img src="{{ $truck->drivingLicenseBack }}" alt="Driving License Back" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                            @if(isset($truck->idFrontUrl))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">ID Front</p>
                                <img src="{{ $truck->idFrontUrl }}" alt="ID Front" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                            @if(isset($truck->idBackUrl))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">ID Back</p>
                                <img src="{{ $truck->idBackUrl }}" alt="ID Back" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                            @if(isset($truck->licenseUrl))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">License</p>
                                <img src="{{ $truck->licenseUrl }}" alt="License" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                            @if(isset($truck->selfieImageUrl))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Selfie</p>
                                <img src="{{ $truck->selfieImageUrl }}" alt="Selfie" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                            @if(isset($truck->sideViewUrl))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Side View</p>
                                <img src="{{ $truck->sideViewUrl }}" alt="Side View" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                            @if(isset($truck->trailerUrl))
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Trailer</p>
                                <img src="{{ $truck->trailerUrl }}" alt="Trailer" class="w-full h-32 object-cover rounded">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Registration Information -->
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Registration Information</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Truck registration and approval details.</p>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Added Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if(isset($truck->added_date))
                                        {{ $truck->added_date instanceof \Google\Cloud\Core\Timestamp ? $truck->added_date->get()->format('M d, Y H:i') : $truck->added_date }}
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Approved Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if(isset($truck->approved_date))
                                        {{ $truck->approved_date instanceof \Google\Cloud\Core\Timestamp ? $truck->approved_date->get()->format('M d, Y H:i') : $truck->approved_date }}
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show a temporary success message
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-green-500"></i>';
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 1000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>
@endpush
@endsection
