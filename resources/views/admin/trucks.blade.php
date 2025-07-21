@extends('admin.layouts.app')

@section('title', 'Trucks Management')

@section('content')
<div class="space-y-8">
    <!-- Page header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Trucks</h1>
            <p class="mt-2 text-sm text-gray-700">Manage and approve registered trucks in the system.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex-none">
            <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <i class="fas fa-plus -ml-1 mr-2 h-4 w-4"></i>
                Add Truck
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div>
            <select class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="">All Types</option>
                <option value="cargo">Cargo Truck</option>
                <option value="container">Container Truck</option>
                <option value="tipper">Tipper Truck</option>
            </select>
        </div>
        <div>
            <select class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div>
            <div class="relative mt-2 rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400 h-5 w-5"></i>
                </div>
                <input type="text" name="search" id="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Search trucks...">
            </div>
        </div>
    </div>

    <!-- Trucks Grid -->
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($trucks ?? [] as $truck)
        <!-- Truck Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="relative h-48 w-full">
                <img src="{{ $truck->image_url ?? 'https://images.unsplash.com/photo-1586768035999-418ffd992414?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" alt="Truck" class="h-full w-full object-cover">
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $truck->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($truck->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($truck->status ?? 'Pending') }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $truck->license_plate ?? 'ABC 123 XY' }}</h3>
                    <span class="text-sm font-medium text-gray-500">{{ $truck->tonnage ?? '30' }} Ton</span>
                </div>
                <dl class="grid grid-cols-2 gap-x-4 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->type ?? 'Cargo Truck' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->make ?? 'Toyota' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->model ?? 'Dyna' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $truck->year ?? '2020' }}</dd>
                    </div>
                </dl>
                
                <!-- Documents section -->
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-900">Documents</h4>
                    <ul role="list" class="mt-4 space-y-3">
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="far fa-file-pdf text-red-500 h-5 w-5"></i>
                                <span class="ml-2 text-sm text-gray-500">Registration</span>
                            </div>
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View</a>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="far fa-file-pdf text-red-500 h-5 w-5"></i>
                                <span class="ml-2 text-sm text-gray-500">Insurance</span>
                            </div>
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View</a>
                        </li>
                    </ul>
                </div>

                <!-- Action buttons -->
                @if(($truck->status ?? 'pending') === 'pending')
                <div class="mt-6 flex space-x-3">
                    <button type="button" class="flex-1 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Approve
                    </button>
                    <button type="button" class="flex-1 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        Reject
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Example static card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="relative h-48 w-full">
                <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Truck" class="h-full w-full object-cover">
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">XYZ 789 AB</h3>
                    <span class="text-sm font-medium text-gray-500">15 Ton</span>
                </div>
                <dl class="grid grid-cols-2 gap-x-4 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">Container Truck</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make</dt>
                        <dd class="mt-1 text-sm text-gray-900">Volvo</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">FH16</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">2022</dd>
                    </div>
                </dl>
                
                <!-- Documents section -->
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-900">Documents</h4>
                    <ul role="list" class="mt-4 space-y-3">
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="far fa-file-pdf text-red-500 h-5 w-5"></i>
                                <span class="ml-2 text-sm text-gray-500">Registration</span>
                            </div>
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View</a>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="far fa-file-pdf text-red-500 h-5 w-5"></i>
                                <span class="ml-2 text-sm text-gray-500">Insurance</span>
                            </div>
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View</a>
                        </li>
                    </ul>
                </div>

                <!-- Action buttons -->
                <div class="mt-6 flex space-x-3">
                    <button type="button" class="flex-1 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Approve
                    </button>
                    <button type="button" class="flex-1 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        <nav class="flex items-center justify-between">
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Previous</a>
                <a href="#" class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">20</span> trucks
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-chevron-left h-5 w-5"></i>
                        </a>
                        <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">1</a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">2</a>
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">8</a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">9</a>
                        <a href="#" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-chevron-right h-5 w-5"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </nav>
    </div>
</div>
@endsection
