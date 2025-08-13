@extends('admin.layouts.app')

@section('title', 'Load Management')

@php
    $statuses = [
        'all' => 'All Loads',
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    $currentStatus = $status ?? 'all';

    $badgeClasses = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ];
@endphp

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Load Management</h1>
                <p class="mt-2 text-sm text-gray-600">Manage all your loads efficiently.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:flex-none">
                @can('load.create')
                    <a href="{{ route('admin.loads.create') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <i class="fas fa-plus mr-2"></i> Add Load
                    </a>
                @endcan
            </div>
        </div>

        {{-- Filter Tabs --}}
        <nav class="flex space-x-4 border-b border-gray-200 mb-6" aria-label="Load filter">
            @foreach ($statuses as $key => $label)
                <a href="{{ route('admin.loads.index', ['status' => $key]) }}"
                    class="whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm
                    {{ $currentStatus === $key
                        ? 'border-indigo-600 text-indigo-600'
                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        {{-- Loads List (Card style) --}}
        <div class="mt-6 bg-white shadow-md rounded-md overflow-hidden">
            @if ($loads->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach ($loads as $load)
                        <li class="group px-4 py-4 my-2 hover:bg-gray-50 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                                {{-- Left Info --}}
                                <div class="flex items-center space-x-4 min-w-0">
                                    <div
                                        class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm flex-shrink-0">
                                        <i class="fas fa-box-open text-xl" title="Load"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 flex-wrap">
                                            {{ $load->load_number }} - {{ $load->title }}
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @switch($load->status)
                                            @case('pending') bg-yellow-200 text-yellow-800 @break
                                            @case('assigned') bg-blue-200 text-blue-800 @break
                                            @case('in_transit') bg-indigo-200 text-indigo-800 @break
                                            @case('delivered') bg-green-200 text-green-800 @break
                                            @case('cancelled') bg-red-200 text-red-800 @break
                                            @case('completed') bg-gray-200 text-gray-800 @break
                                            @default bg-gray-100 text-gray-600
                                        @endswitch
                                    ">
                                                <i class="fas fa-circle mr-1 text-[8px]"></i>
                                                {{ ucfirst(str_replace('_', ' ', $load->status)) }}
                                            </span>
                                        </h3>
                                        <div class="text-sm text-gray-500 mt-1 space-y-1">
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-weight-hanging mr-1"></i>
                                                <span>{{ number_format($load->weight_tonnes, 2) }} tonnes</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-truck-loading mr-1"></i>
                                                <span>Pickup: {{ $load->pickup_city }}, {{ $load->pickup_state }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-truck-moving mr-1"></i>
                                                <span>Delivery: {{ $load->delivery_city }},
                                                    {{ $load->delivery_state }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex flex-wrap gap-2 sm:justify-end items-center">
                                    <a href="{{ route('admin.loads.show', $load) }}"
                                        class="hidden sm:inline-flex items-center px-3 py-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600 border border-indigo-600 transition"
                                        title="View Load">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>

                                    @if (in_array($load->status, ['pending', 'assigned']))
                                        <a href="{{ route('admin.loads.edit', $load) }}"
                                            class="hidden sm:inline-flex items-center px-3 py-1 rounded text-yellow-500 hover:text-white hover:bg-yellow-500 border border-yellow-500 transition"
                                            title="Edit Load">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                    @endif

                                    @if ($load->status === 'pending')
                                        <a href="{{ route('admin.loads.assign', $load) }}"
                                            class="hidden sm:inline-flex items-center px-3 py-1 rounded text-purple-600 hover:text-white hover:bg-purple-600 border border-purple-600 transition"
                                            title="Assign Load">
                                            <i class="fas fa-user-check mr-1"></i> Assign
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.loads.destroy', $load) }}"
                                        class="hidden sm:inline-flex items-center px-3 py-1  rounded text-red-600 hover:bg-red-600 hover:text-white  border border-red-600 transition"
                                        title="Delete Load" aria-label="Delete Load">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>



                                    {{-- Mobile icon-only buttons --}}
                                    <a href="{{ route('admin.loads.show', $load) }}"
                                        class="sm:hidden p-2 rounded text-indigo-600 hover:bg-indigo-600 hover:text-white"
                                        title="View Load" aria-label="View Load">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if (in_array($load->status, ['pending', 'assigned']))
                                        <a href="{{ route('admin.loads.edit', $load) }}"
                                            class="sm:hidden p-2 rounded text-yellow-500 hover:bg-yellow-500 hover:text-white"
                                            title="Edit Load" aria-label="Edit Load">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if ($load->status === 'pending')
                                        <a href="{{ route('admin.loads.assign', $load) }}"
                                            class="sm:hidden p-2 rounded text-purple-600 hover:bg-purple-600 hover:text-white"
                                            title="Assign Load" aria-label="Assign Load">
                                            <i class="fas fa-user-check"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.loads.destroy', $load) }}"
                                        class="sm:hidden p-2 rounded text-red-600 hover:bg-red-600 hover:text-white"
                                        title="Delete Load" aria-label="Delete Load">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No loads found</h3>
                    <p>No loads have been added yet or no loads match the filter.</p>
                </div>
            @endif

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $loads->links() }}
            </div>
        </div>

    </div>
@endsection
