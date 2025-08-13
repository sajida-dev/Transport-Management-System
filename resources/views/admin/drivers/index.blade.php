@extends('admin.layouts.app')

@section('title', 'Driver Management')

@section('content')
<div class="w-full px-4 py-8">

    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Driver Management</h1>
            <p class="mt-1 text-sm text-gray-600">Manage drivers and their licenses, status, and transporters</p>
        </div>
        <div class="mt-4 sm:mt-0">
           @can('driver.create')
                <a href="{{ route('admin.drivers.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Add New Driver
                </a>
           @endcan  
        </div>
    </div>

    <!-- Filters -->
    <x-card :title="'Filter Drivers'" :noPadding="true" class="mb-6" nopadding>
        {{-- Filter Form --}}
        <form id="driverFilterForm" method="GET" action="{{ route('admin.drivers.index') }}" class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-input label="Search" name="search" placeholder="Name, Email, Phone, License"
                         value="{{ request('search') }}" />

                <x-select label="Status" name="status" :options="[
                    '' => 'All',
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'suspended' => 'Suspended',
                    'expired' => 'Expired',
                    'pending_verification' => 'Pending Verification',
                ]" :value="request('status')" />

                <x-select label="Transporter" name="transporter_id"
                          :options="$transporters->pluck('company_name', 'id')->prepend('All','')" 
                          :value="request('transporter_id')" />

                <x-input type="date" label="License Expiry Date" name="license_expiry_date"
                         value="{{ request('license_expiry_date') }}" placeholder="YYYY-MM-DD" />
            </div>
        </form>
    </x-card>

    <!-- Prepare Rows -->
    @php
    $user = auth()->user();
    $rows = $drivers->map(function($driver) {
        
        return [
            'columns' => [
                $driver->id,
                $driver->user->profile_photo
                    ? '<img src="' . Storage::url($driver->user->profile_photo) . '" class="w-10 h-10 rounded-full object-cover" />'
                    : '<div class="w-10 h-10 flex items-center justify-center bg-gray-300 rounded-full"><i class="fas fa-user text-white"></i></div>',
                
                // Name (from users table)
                '<div class="text-sm font-medium text-gray-900">' 
                    . e($driver->user->name) . 
                '</div>' .
                ($driver->isLicenseExpired()
                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">License Expired</span>'
                    : ''),
                
                // Email (from users table)
                e($driver->user->email),

                // Phone number (from users table)
                e($driver->user->phone_number),

                // License details (from drivers table)
                e($driver->license_number) . '<br><span class="text-xs text-gray-400">' . e($driver->license_type) . '</span>',

                // Transporter
                e($driver->transporter->company_name ?? 'N/A'),

                // Status badge
                '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' 
                    . $driver->status_badge_class . '">' .
                    ucfirst(str_replace('_', ' ', $driver->status)) . 
                '</span>',
            ],
            'id' => $driver->id, // for actions
        ];
    })->toArray();

        

@endphp


    <!-- Driver Table -->
    <x-card :title="'All Drivers'" :subtitle="'View and manage all registered drivers'" class="mb-8" noPadding>
        <x-table
            :headers="['ID', 'Photo', 'Name', 'Email', 'Phone', 'License', 'Transporter', 'Status']"
            :rows="$rows"
            :actions="function($row) use ($drivers,$user) {
                $driver = $drivers->firstWhere('id', $row['id']);
                $actions = [];

            if ($user->can('driver.view')) {
                $actions['viewUrl'] = route('admin.drivers.show', $driver);
            }
            if ($user->can('driver.edit')) {
                $actions['editUrl'] = route('admin.drivers.edit', $driver);
            }
            if ($user->can('driver.delete')) {
                $actions['deleteUrl'] = route('admin.drivers.destroy', $driver);
            }

            return view('components.action-buttons', $actions)->render();
            }"
        >
            {{ $drivers->withQueryString()->links() }}
        </x-table>
    </x-card>

</div>
@endsection

@push('scripts')
<script>
   
    ['search', 'status', 'transporter_id', 'license_expiry_date'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => document.getElementById('driverFilterForm').submit());
    });
</script>
@endpush

