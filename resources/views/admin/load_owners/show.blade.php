@extends('admin.layouts.app')

@section('title', 'Load Owner Details')

@section('content')
    <div class="w-full px-4 py-8 space-y-6">
        <!-- Header with Action Buttons -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Load Owner Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.load_owners.index') }}"
                    class="btn-secondary inline-flex items-center px-4 py-2 text-sm rounded-md">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
                <a href="{{ route('admin.load_owners.edit', $load_owner) }}"
                    class="btn-yellow inline-flex items-center px-4 py-2 text-sm rounded-md">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('admin.load_owners.toggleStatus', $load_owner) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    @php
                        $isActive = $load_owner->status === 'active';
                        $toggleTitle = $isActive ? 'Suspend' : 'Activate';
                        $toggleIcon = $isActive ? 'fa-user-slash' : 'fa-play';
                        $toggleColor = $isActive ? 'btn-orange' : 'btn-green';
                    @endphp
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm rounded-md {{ $toggleColor }}">
                        <i class="fas {{ $toggleIcon }} mr-1"></i> {{ $toggleTitle }}
                    </button>
                </form>
                <form action="{{ route('admin.load_owners.destroy', $load_owner) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this load owner?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-red inline-flex items-center px-4 py-2 text-sm rounded-md">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Load Owner Info -->
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-medium text-gray-900">Load Owner Info</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $statusClasses = [
                        'active' => 'bg-green-100 text-green-800',
                        'inactive' => 'bg-gray-100 text-gray-800',
                        'suspended' => 'bg-orange-100 text-orange-800',
                        'pending_verification' => 'bg-yellow-100 text-yellow-800',
                    ];
                @endphp
                <div>
                    <label class="block text-sm text-gray-500">Company Name</label>
                    <p class="text-gray-900">{{ $load_owner->company_name }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Contact Person</label>
                    <p class="text-gray-900">
                        {{ $load_owner->contact_person_name }}
                        <span class="text-gray-600">({{ $load_owner->contact_person_email }})</span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Location</label>
                    <p class="text-gray-900">{{ $load_owner->city }}, {{ $load_owner->state }},
                        {{ $load_owner->country }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Status</label>
                    <span
                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$load_owner->status] }}">
                        {{ ucfirst(str_replace('_', ' ', $load_owner->status)) }}
                    </span>
                </div>
                @if ($load_owner->tax_id)
                    <div>
                        <label class="block text-sm text-gray-500">Tax ID</label>
                        <p class="text-gray-900">{{ $load_owner->tax_id }}</p>
                    </div>
                @endif
                @if ($load_owner->business_license_number)
                    <div>
                        <label class="block text-sm text-gray-500">Business License</label>
                        <p class="text-gray-900">{{ $load_owner->business_license_number }}</p>
                    </div>
                @endif
                @if ($load_owner->business_license_expiry)
                    <div>
                        <label class="block text-sm text-gray-500">License Expiry</label>
                        <p class="text-gray-900">{{ $load_owner->business_license_expiry->format('M d, Y') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Associated User Info -->
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-medium text-gray-900">Associated User</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-500">Username</label>
                    <p class="text-gray-900">{{ $load_owner->user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Email</label>
                    <p class="text-gray-900">{{ $load_owner->user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Phone</label>
                    <p class="text-gray-900">{{ $load_owner->user->phone_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Last Login</label>
                    <p class="text-gray-900">{{ $load_owner->user->last_login_at?->format('M d, Y H:i:s') ?? 'Never' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
