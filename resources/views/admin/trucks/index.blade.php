@extends('admin.layouts.app')

@section('title', 'Truck Management')

@php
// Helper function to determine active tab - adjust as needed
$is_active = function($button_selection, $current_selection) {
    return $button_selection === $current_selection ? 'text-indigo-600 border-indigo-500' : 'text-gray-500 hover:text-gray-700 border-transparent';
};

// Helper function for status badge styling
$getStatusBadgeClass = function($status) {
    return match($status) {
        'active' => 'bg-green-100 text-green-700 ring-1 ring-inset ring-green-600/20',
        'inactive' => 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-500/10',
        'pending_approval' => 'bg-yellow-100 text-yellow-800 ring-1 ring-inset ring-yellow-600/20',
        'rejected' => 'bg-red-100 text-red-700 ring-1 ring-inset ring-red-600/10',
        default => 'bg-blue-100 text-blue-700 ring-1 ring-inset ring-blue-700/10'
    };
};
@endphp

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">Truck Management</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all trucks registered in the system.</p>
        </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <i class="fas fa-plus mr-1"></i> Add Truck
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mt-4 border-b border-gray-200 pb-5 sm:pb-0">
        <div class="mt-3 sm:mt-4">
            <div class="sm:hidden">
                <label for="current-tab" class="sr-only">Select a tab</label>
                <select id="current-tab" name="current-tab" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" onchange="window.location.href = this.value;">
                    <option value="{{ route('admin.trucks', ['selection' => 'all']) }}" {{ $selection == 'all' ? 'selected' : '' }}>All Trucks</option>
                    <option value="{{ route('admin.trucks', ['selection' => 'active']) }}" {{ $selection == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="{{ route('admin.trucks', ['selection' => 'inactive']) }}" {{ $selection == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="{{ route('admin.trucks', ['selection' => 'pending_approval']) }}" {{ $selection == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="{{ route('admin.trucks', ['selection' => 'rejected']) }}" {{ $selection == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.trucks', ['selection' => 'all']) }}" 
                       class="{{ $is_active('all', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                        All Trucks
                    </a>
                    <a href="{{ route('admin.trucks', ['selection' => 'active']) }}" 
                       class="{{ $is_active('active', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                        Active
                    </a>
                     <a href="{{ route('admin.trucks', ['selection' => 'inactive']) }}" 
                       class="{{ $is_active('inactive', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                        Inactive
                    </a>
                    <a href="{{ route('admin.trucks', ['selection' => 'pending_approval']) }}" 
                       class="{{ $is_active('pending_approval', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                        Pending Approval
                    </a>
                    <a href="{{ route('admin.trucks', ['selection' => 'rejected']) }}" 
                       class="{{ $is_active('rejected', $selection ?? 'all') }} whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">
                        Rejected
                    </a>
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
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Truck Details</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Transporter</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Capacity (Tonnes)</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Registered Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Truck ID</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($items as $truck)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center">
                                            @if(isset($truck->sideViewUrl))
                                                <img src="{{ $truck->sideViewUrl }}" alt="Truck" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <i class="fas fa-truck text-3xl text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $truck->licenseNumber ?? 'N/A' }}</div>
                                            <div class="text-gray-500">{{ $truck->model ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium">{{ $truck->transporterName ?? 'N/A' }}</div>
                                    @if(isset($truck->user))
                                        <div class="text-gray-500">{{ $truck->user['phone'] ?? 'N/A' }}</div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">{{ $truck->tonnage ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $getStatusBadgeClass($truck->status ?? 'unknown') }}">
                                        {{ ucfirst($truck->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if(isset($truck->added_date))
                                        {{ $truck->added_date instanceof \Google\Cloud\Core\Timestamp ? $truck->added_date->get()->format('Y-m-d') : $truck->added_date }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono">{{ $truck->id }}</span>
                                        <button onclick="copyToClipboard('{{ $truck->id }}')" class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.trucks.detail', $truck->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($truck->status === 'pending_approval')
                                            <button onclick="approveTruck('{{ $truck->id }}')" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="rejectTruck('{{ $truck->id }}')" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                                    No trucks found for this status.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

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