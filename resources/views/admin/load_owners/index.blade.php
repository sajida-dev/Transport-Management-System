@extends('admin.layouts.app')

@section('title', 'Load Owners Management')

@section('content')
    <div class="w-full px-4 py-8">
        <div class="w-full">
            <!-- Page Header -->
            <div class="sm:flex sm:items-center sm:justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Load Owners</h1>
                    <p class="mt-2 text-sm text-gray-700">Manage all registered load owner companies and their users</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('admin.load_owners.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i> Add Load Owner
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-1 lg:grid-cols-5 mb-8">
                <x-admin-stats-card icon="fas fa-building" label="Total Load Owners" :value="$loadOwners->count()" color="indigo" />

                <x-admin-stats-card icon="fas fa-check-circle" label="Active Load Owners" :value="$loadOwners->where('status', 'active')->count()"
                    color="green" />

                <x-admin-stats-card icon="fas fa-user-slash" label="Inactive Load Owners" :value="$loadOwners->where('status', 'inactive')->count()"
                    color="red" />

                <x-admin-stats-card icon="fas fa-hourglass-half" label="Pending Verification" :value="$loadOwners->where('status', 'pending_verification')->count()"
                    color="yellow" />

                <x-admin-stats-card icon="fas fa-ban" label="Suspended Load Owners" :value="$loadOwners->where('status', 'suspended')->count()" color="gray" />
            </div>


            <!-- Load Owners Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">All Load Owners</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Browse and manage registered load owners.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($loadOwners as $owner)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $owner->company_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $owner->tax_id ?? 'No Tax ID' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $owner->contact_person_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $owner->contact_person_email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $owner->city }}, {{ $owner->state }}</div>
                                        <div class="text-sm text-gray-500">{{ $owner->country }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if ($owner->status == 'active') bg-green-100 text-green-800
                                        @elseif($owner->status == 'pending_verification') bg-yellow-100 text-yellow-800
                                        @elseif($owner->status == 'suspended') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($owner->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2 items-center">
                                            {{-- View --}}
                                            <a href="{{ route('admin.load_owners.show', $owner) }}"
                                                class="text-blue-600 hover:text-blue-900" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.load_owners.edit', $owner) }}"
                                                class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Toggle Status --}}
                                            <form action="{{ route('admin.load_owners.toggleStatus', $owner) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to change the status of this load owner?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="{{ $owner->status === 'active' ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-600 hover:text-green-800' }}"
                                                    title="Toggle Status">
                                                    <i
                                                        class="fas {{ $owner->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>

                                            {{-- Delete --}}
                                            <form action="{{ route('admin.load_owners.destroy', $owner) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this load owner?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No load owners
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
