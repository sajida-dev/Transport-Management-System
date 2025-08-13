@extends('admin.layouts.app')

@section('title', 'Transporter Details')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $transporter->company_name }}</h1>
                <p class="mt-1 text-sm text-gray-500">View details for this transporter company.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('transporter.edit')
                    <a href="{{ route('admin.transporters.edit', $transporter) }}"
                        class="btn btn-sm px-2 py-1 rounded-md bg-yellow-500 hover:bg-yellow-600 text-white">
                        <i class="fas fa-edit mr-1"></i> <span class="hidden sm:inline">Edit</span>
                    </a>
                @endcan
                @can('transporter.delete')
                    <form action="{{ route('admin.transporters.destroy', $transporter) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this transporter?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm px-2 py-1 rounded-md bg-red-500 hover:bg-red-600 text-white">
                            <i class="fas fa-trash-alt mr-1"></i> <span class="hidden sm:inline">Delete</span>
                        </button>
                    </form>
                @endcan

                {{-- Suspend/Reactivate Button --}}
                @if ($transporter->status !== 'suspended')
                    @can('transporter.suspend')
                        <form action="{{ route('admin.transporters.suspend', $transporter) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm px-2 py-1 rounded-md bg-gray-500 hover:bg-gray-600 text-white">
                                <i class="fas fa-ban mr-1"></i> <span class="hidden sm:inline">Suspend</span>
                            </button>
                        </form>
                    @endcan
                @else
                    @can('transporter.reactivate')
                        <form action="{{ route('admin.transporters.reactivate', $transporter) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm px-2 py-1 rounded-md bg-green-500 hover:bg-green-600 text-white">
                                <i class="fas fa-check mr-1"></i> <span class="hidden sm:inline">Reactivate</span>
                            </button>
                        </form>
                    @endcan
                @endif
                @can('transporter.trucks.manage')
                    <a href="{{ route('admin.transporters.trucks.index', $transporter) }}"
                        class="btn btn-sm bg-indigo-600 px-2 py-1 rounded-md hover:bg-indigo-700 text-white">
                        <i class="fas fa-truck mr-1"></i> <span class="hidden sm:inline">Manage Trucks</span>
                    </a>
                @endcan
            </div>
        </div>

        {{-- Card --}}
        <div class="mt-6 bg-white shadow-sm rounded-lg p-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="h-20 w-20 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-full">
                    <i class="fas fa-building text-3xl"></i>
                </div>
                <div class="flex-1 space-y-2">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $transporter->company_name }}</h2>
                    <p class="text-sm text-gray-600">Registration No: <span
                            class="font-medium text-gray-800">{{ $transporter->registration_number }}</span></p>
                    <p class="text-sm text-gray-600">Email: <a href="mailto:{{ $transporter->email }}"
                            class="text-blue-600 hover:underline">{{ $transporter->email }}</a></p>
                    <p class="text-sm text-gray-600">Phone: <a href="tel:{{ $transporter->phone }}"
                            class="text-blue-600 hover:underline">{{ $transporter->phone }}</a></p>
                    <p class="text-sm text-gray-600">Address: {{ $transporter->address ?? '-' }}</p>
                </div>
                <div class="text-sm">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $transporter->status_badge_class }}">
                        <i class="fas fa-circle mr-1 text-[8px]"></i> {{ ucfirst($transporter->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Statistics Section --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 text-center shadow-sm">
                <p class="text-sm text-gray-500">Total Trucks</p>
                <p class="text-2xl font-bold text-gray-900">{{ $transporter->trucks->count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center shadow-sm">
                <p class="text-sm text-gray-500">Active Drivers</p>
                <p class="text-2xl font-bold text-gray-900">{{ $transporter->drivers->count() ?? 0 }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center shadow-sm">
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-xl font-semibold text-gray-800">{{ ucfirst($transporter->status) }}</p>
            </div>
        </div>

        {{-- Meta Section --}}
        <div x-data="{ open: false }" class="mt-8">
            <button @click="open = !open" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                <i class="fas fa-info-circle"></i> Show Meta Info
            </button>

            <div x-show="open" x-transition class="mt-4 text-sm bg-gray-50 rounded p-4 border">
                <p><strong>Created At:</strong> {{ $transporter->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $transporter->updated_at->format('d M Y, H:i') }}</p>
                <p><strong>ID:</strong> {{ $transporter->id }}</p>
            </div>
        </div>

    </div>
@endsection
