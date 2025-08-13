@extends('admin.layouts.app')
@section('title', 'All Truck Management')

@php
    $selection = $selection ?? 'all';

    $is_active = fn($button, $current) => $button === $current
        ? 'text-indigo-600 border-indigo-500'
        : 'text-gray-500 hover:text-gray-700 border-transparent';

    $getStatusBadgeClass = fn($status) => match ($status) {
        'active' => 'bg-green-100 text-green-700 ring-1 ring-green-600/20',
        'inactive' => 'bg-gray-100 text-gray-600 ring-1 ring-gray-500/10',
        'pending_approval' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20',
        'rejected' => 'bg-red-100 text-red-700 ring-1 ring-red-600/10',
        default => 'bg-blue-100 text-blue-700 ring-1 ring-blue-700/10',
    };
@endphp

@section('content')
    <div class="px-1 sm:px-2">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">Truck Management</h1>
                <p class="mt-2 text-sm text-gray-700">A list of all trucks registered in the system.</p>
            </div>

        </div>

        <!-- Table -->
        <div class=" flow-root mt-5 bg-white shadow-sm rounded-lg p-2">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Truck
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Transporter</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Driver</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Capacity</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Registered</th>
                                {{-- <th class="py-3.5 pl-3 pr-4 sm:pr-0 text-right text-sm font-semibold text-gray-900">Actions
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($trucks as $truck)
                                <tr>
                                    <td class="py-4 pl-4 pr-3 text-sm sm:pl-0">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                @if ($truck->photo)
                                                    <img src="{{ asset('/' . 'storage/' . $truck->photo) }}"
                                                        class="h-10 w-10 rounded-full object-cover" alt="Truck">
                                                @else
                                                    <i class="fas fa-truck text-3xl text-gray-400"></i>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">
                                                    {{ $truck->registration_number ?? 'N/A' }}</div>
                                                <div class="text-gray-500">{{ $truck->model ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        <div class="font-medium">{{ optional($truck->transporter)->company_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-gray-500">{{ optional($truck->transporter)->email }}</div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        @if ($truck->driver)
                                            <div class="font-medium text-gray-900">{{ $truck->driver->name }}</div>
                                            <div class="text-gray-500">{{ $truck->driver->phone ?? 'N/A' }}</div>
                                        @else
                                            <span class="text-gray-400 italic">No Driver</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm text-center text-gray-500">{{ $truck->capacity_tonnes }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $getStatusBadgeClass($truck->status) }}">
                                            {{ ucfirst($truck->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        {{ $truck->created_at ? $truck->created_at->format('Y-m-d') : 'N/A' }}
                                    </td>
                                    {{-- <td class="px-3 py-4 text-sm text-right text-gray-500">
                                        <div class="flex justify-end gap-3">
                                            @can('transporter.trucks.show')
                                                <a href="{{ route('admin.transporters.trucks.show', [$transporter, $truck]) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('transporter.trucks.edit')
                                                <a href="{{ route('admin.transporters.trucks.edit', [$transporter, $truck]) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('transporter.trucks.delete')
                                                <form
                                                    action="{{ route('admin.transporters.trucks.destroy', [$transporter, $truck]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this truck?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-gray-500 text-sm">No trucks found for
                                        this status.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Optional Pagination -->
                    @if (method_exists($trucks, 'links'))
                        <div class="mt-4">
                            {{ $trucks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const button = event.currentTarget;
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check text-green-500"></i>';
                setTimeout(() => button.innerHTML = originalHTML, 1000);
            }).catch(err => console.error('Failed to copy text:', err));
        }
    </script>
@endpush
