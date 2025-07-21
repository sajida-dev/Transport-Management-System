@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-12 py-6">
        <!-- Page header -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Dashboard Overview</h1>
            <div class="mt-4 sm:mt-0">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Download Report
                </button>
            </div>
        </div>

        <!-- Session Status Messages -->
        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-red-400 h-5 w-5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Error Fetching Data</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (session('status')) {{-- General status messages --}}
             <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                         <i class="fas fa-check-circle text-green-400 h-5 w-5"></i>
                    </div>
                    <div class="ml-3">
                         <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                     </div>
                </div>
            </div>
        @endif

        <!-- Truck Booking Management Section -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-900">Truck Booking Management</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
                <!-- Pending Requests -->
                <a href="{{ route('admin.bookings', ['selection' => 'pending']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-yellow-100 p-3">
                                <i class="fas fa-shopping-cart text-yellow-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Pending Requests</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $truckPendingRequests ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Total Bookings -->
                <a href="{{ route('admin.bookings', ['selection' => 'all']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-blue-100 p-3">
                                <i class="fas fa-calendar-check text-blue-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Total Bookings</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $truckTotalBookings ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Cancelled Orders -->
                <a href="{{ route('admin.bookings', ['selection' => 'cancelled']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-red-100 p-3">
                                <i class="fas fa-ban text-red-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Cancelled Orders</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $truckCancelledOrders ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Approved Orders -->
                <a href="{{ route('admin.bookings', ['selection' => 'approved']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-green-100 p-3">
                                <i class="fas fa-check-circle text-green-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Approved Orders</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $truckApprovedOrders ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Active Trucks Card -->
                <a href="{{ route('admin.trucks', ['selection' => 'active']) }}" class="block">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Active Trucks</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ $activeTrucks }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <span class="text-gray-500">View all active trucks</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Load Booking Management Section -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-900">Load Booking Management</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
                <!-- Pending Requests -->
                <a href="{{ route('admin.load_bookings', ['selection' => 'pending']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-yellow-100 p-3">
                                <i class="fas fa-clock text-yellow-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Pending Requests</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $loadPendingRequests ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Load Approvals -->
                <a href="{{ route('admin.loadApprovals', ['selection' => 'pending']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-cyan-100 p-3">
                                <i class="fas fa-check text-cyan-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Load Approvals</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $loadApprovals ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Total Bookings -->
                <a href="{{ route('admin.load_bookings', ['selection' => 'all']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-blue-100 p-3">
                                <i class="fas fa-calendar-check text-blue-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Total Bookings</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $loadTotalBookings ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Cancelled Orders -->
                <a href="{{ route('admin.load_bookings', ['selection' => 'cancelled']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-red-100 p-3">
                                <i class="fas fa-ban text-red-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Cancelled Orders</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $loadCancelledOrders ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Approved Orders -->
                <a href="{{ route('admin.load_bookings', ['selection' => 'completed']) }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-green-100 p-3">
                                <i class="fas fa-check-circle text-green-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Completed Orders</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $loadApprovedOrders ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


        <!-- User Management Section -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-900">User Management</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Load Owners -->
                <a href="{{ route('admin.users.load_owners') }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-purple-100 p-3">
                                <i class="fas fa-user-tie text-purple-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Load Owners</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $loadOwners ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Transporters -->
                <a href="{{ route('admin.users.transporters') }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-orange-100 p-3">
                                <i class="fas fa-truck-moving text-orange-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">Transporters</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $transporters ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- KYC Applications -->
                <a href="{{ route('admin.kyc') }}" class="block">
                    <div class="overflow-hidden rounded-lg bg-white p-5 shadow-sm border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-teal-100 p-3">
                                <i class="fas fa-file-contract text-teal-600 h-6 w-6"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="truncate text-xs font-semibold uppercase tracking-wider text-gray-500">KYC Applications</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $kycApplications ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Active Trucks Section -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-900">Active Trucks</h2>
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="p-6">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Currently Active Trucks</h3>
                            <p class="mt-2 text-sm text-gray-700">A list of all trucks currently active in the system.</p>
                        </div>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Truck Details</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Driver</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Contact</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Location</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($activeTrucksList ?? [] as $truck)
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                                    <div class="font-medium text-gray-900">{{ $truck['plate_number'] }}</div>
                                                    <div class="text-gray-500">{{ $truck['model'] }} - {{ $truck['tonnage'] }} tons</div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <div class="font-medium text-gray-900">{{ $truck['driver_name'] }}</div>
                                                    <div class="text-gray-500">ID: {{ $truck['driver_id'] }}</div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $truck['contact'] }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Active
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $truck['current_location'] ?? 'Unknown' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">
                                                    No active trucks found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Line chart -->
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="p-6">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Booking Statistics</h3>
                            <p class="mt-2 text-sm text-gray-700">Monthly booking trends over the past year.</p>
                        </div>
                    </div>
                    <div class="mt-6" style="height: 300px;">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="p-6">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Recent Activity</h3>
                            <p class="mt-2 text-sm text-gray-700">Latest system activities and updates.</p>
                        </div>
                    </div>
                    <div class="mt-6 flow-root">
                        <ul role="list" class="-my-5 divide-y divide-gray-200">
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-truck text-indigo-600 h-6 w-6"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900">New truck registration</p>
                                        <p class="truncate text-sm text-gray-500">ABC 123 XY - Toyota Dyna</p>
                                    </div>
                                    <div class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">5 minutes ago</div>
                                </div>
                            </li>
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user text-green-600 h-6 w-6"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900">New user registration</p>
                                        <p class="truncate text-sm text-gray-500">John Doe (john@example.com)</p>
                                    </div>
                                    <div class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">2 hours ago</div>
                                </div>
                            </li>
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar-check text-blue-600 h-6 w-6"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900">Booking completed</p>
                                        <p class="truncate text-sm text-gray-500">Booking #12345 - Lusaka to Kitwe</p>
                                    </div>
                                    <div class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">5 hours ago</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-6">
                        <a href="#" class="flex w-full items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">View all</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Bookings',
                    data: [65, 59, 80, 81, 56, 55, 40, 60, 55, 30, 78, 95], // Example data
                    fill: false,
                    borderColor: 'rgb(79, 70, 229)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

