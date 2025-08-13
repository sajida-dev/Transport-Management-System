
{{-- Transporter Management Index View --}}
@extends('admin.layouts.app')

@section('title', 'Transporter Management')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900">Transporter Management</h1>
            <p class="mt-1 text-sm text-gray-600">Manage all registered transporter companies.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            @can('transporter.create')
            <a href="{{ route('admin.transporters.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i> Add Transporter
            </a>
            @endcan
        </div>
    </div>

    {{-- Transporters List --}}
    <div class="mt-6 bg-white shadow-md rounded-md overflow-hidden">
        @if($transporters->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($transporters as $transporter)
                    <li class="group px-4 py-4 my-2 hover:bg-gray-50  transition ">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            {{-- Left Info --}}
                            <div class="flex items-center space-x-4 min-w-0">
                                <div class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm flex-shrink-0">
                                    <i class="fas fa-truck text-xl" title="Transporter"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 flex-wrap">
                                        {{ $transporter->company_name }}
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $transporter->status_badge_class }}">
                                            <i class="fas fa-circle mr-1 text-[8px]"></i>
                                            {{ ucfirst(str_replace('_', ' ', $transporter->status)) }}
                                        </span>
                                    </h3>
                                    <div class="text-sm text-gray-500 mt-1 space-y-1">
                                        <div class="flex items-center space-x-1">
                                            <i class="fas fa-id-card mr-1"></i>
                                            <span class="truncate">Registration No: {{ $transporter->registration_number }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <i class="fas fa-envelope mr-1"></i>
                                            <span class="truncate">{{ $transporter->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-wrap gap-2 sm:justify-end items-center">
                                {{-- Desktop Buttons with text --}}
                                @can('transporter.view')
                                <a href="{{ route('admin.transporters.show', $transporter) }}"
                                   class="hidden sm:inline-flex items-center px-3 py-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600 border border-indigo-600 transition"
                                   title="View Details">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                @endcan
                                @can('transporter.edit')
                                <a href="{{ route('admin.transporters.edit', $transporter) }}"
                                   class="hidden sm:inline-flex items-center px-3 py-1 rounded text-yellow-500 hover:text-white hover:bg-yellow-500 border border-yellow-500 transition"
                                   title="Edit Transporter">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @endcan

                                {{-- Action Buttons --}}
                                @can('transporter.delete')
                                <form action="{{ route('admin.transporters.destroy', $transporter) }}" method="POST" onsubmit="return confirm('Are you sure?')"
                                    class="hidden sm:inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 text-red-600 hover:bg-red-500 border border-red-500 hover:text-white rounded transition">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                                @endcan

                                {{-- Suspend/Reactivate Button --}}
                                
                                @if($transporter->status !== 'suspended')
                                @can('transporter.suspend')
                                    <form action="{{ route('admin.transporters.suspend', $transporter) }}" method="POST" class="hidden sm:inline-flex">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 text-gray-600 hover:bg-gray-500 border border-gray-500 hover:text-white rounded transition">
                                            <i class="fas fa-ban mr-1"></i> Suspend
                                        </button>
                                    </form>
                                    @endcan
                                @else
                                @can('transporter.reactivate')
                                    <form action="{{ route('admin.transporters.reactivate', $transporter) }}" method="POST" class="hidden sm:inline-flex">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 text-green-600 hover:bg-green-500 border border-green-500 hover:text-white rounded transition">
                                            <i class="fas fa-check mr-1"></i> Reactivate
                                        </button>
                                    </form>
                                    @endcan
                                @endif
                                    @can('transporter.trucks.manage')
                                <a href="{{ route('admin.transporters.trucks.index', $transporter) }}"
                                   class="hidden sm:inline-flex items-center px-3 py-1 text-blue-600 hover:bg-blue-500 border border-blue-500 hover:text-white rounded transition">
                                    <i class="fas fa-truck mr-1"></i> Manage Trucks
                                </a>
                                @endcan

                                {{-- Mobile Buttons with only icons and tooltips --}}
                                @can('transporter.show')
                                <a href="{{ route('admin.transporters.show', $transporter) }}"
                                   class="sm:hidden p-2 rounded text-indigo-600 hover:bg-indigo-600 hover:text-white"
                                   title="View Details" aria-label="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                @can('transporter.edit')
                                <a href="{{ route('admin.transporters.edit', $transporter) }}"
                                   class="sm:hidden p-2 rounded text-yellow-500 hover:bg-yellow-500 hover:text-white"
                                   title="Edit Transporter" aria-label="Edit Transporter">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('transporter.delete')
                                <form action="{{ route('admin.transporters.destroy', $transporter) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="sm:hidden inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-500 hover:text-white rounded"
                                            title="Delete" aria-label="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan

                                {{-- Suspend/Reactivate Button for mobile --}}
                                
                                @if($transporter->status !== 'suspended')
                                @can('transporter.suspend')
                                    <form action="{{ route('admin.transporters.suspend', $transporter) }}" method="POST" class="sm:hidden inline-block">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 text-gray-600 hover:bg-gray-500 hover:text-white rounded"
                                                title="Suspend" aria-label="Suspend">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                    @endcan
                                @else
                                    @can('transporter.reactivate')
                                    <form action="{{ route('admin.transporters.reactivate', $transporter) }}" method="POST" class="sm:hidden inline-block">
                                        @csrf
                                        <button type="submit"
                                                class="p-2 text-green-600 hover:bg-green-500 hover:text-white rounded"
                                                title="Reactivate" aria-label="Reactivate">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endcan
                                @endif
                                    @can('transporter.trucks.manage')
                                <a href="{{ route('admin.transporters.trucks.index', $transporter) }}"
                                   class="sm:hidden p-2 rounded text-blue-600 hover:bg-blue-500 hover:text-white"
                                   title="Manage Trucks" aria-label="Manage Trucks">
                                    <i class="fas fa-truck"></i>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No transporters found</h3>
                <p>No transporters have been added yet.</p>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($transporters->hasPages())
        <div class="mt-6">
            {{ $transporters->links() }}
        </div>
    @endif
</div>
@endsection
