@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Active Trucks</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all approved trucks in the system with their driver details.</p>
        </div>
    </div>

    @if(isset($error))
        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($trucks as $truck)
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($truck->sideViewUrl)
                                <img class="h-16 w-16 rounded-lg object-cover" src="{{ $truck->sideViewUrl }}" alt="Truck Image">
                            @else
                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $truck->transporterName }}</h3>
                            <p class="text-sm text-gray-500">License: {{ $truck->licenseNumber }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Model</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $truck->model }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tonnage</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $truck->tonnage }} tons</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Trailer Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $truck->trailerType }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Trailer Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $truck->trailerNumber }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if(isset($truck->driver))
                        <div class="mt-6 border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-medium text-gray-500">Driver Information</h4>
                            <div class="mt-2 flex items-center">
                                @if($truck->driver['profile_image'])
                                    <img class="h-10 w-10 rounded-full" src="{{ $truck->driver['profile_image'] }}" alt="Driver Image">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $truck->driver['name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $truck->driver['phone'] }}</p>
                                </div>
                                <div class="ml-auto">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $truck->driver['is_online'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $truck->driver['is_online'] ? 'Online' : 'Offline' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Added: {{ $truck->formatted_added_date ?? 'N/A' }}</span>
                            <span class="text-gray-500">Approved: {{ $truck->formatted_approved_date ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No active trucks</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by approving some truck applications.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection 