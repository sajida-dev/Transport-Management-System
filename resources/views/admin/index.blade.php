@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            @php
                $cards = [
                    [
                        'title' => 'Total Active Loads',
                        'value' => $activeLoadsCount,
                        'color' => 'indigo',
                        'icon' => 'fa-truck-loading',
                    ],
                    [
                        'title' => 'Bookings Today',
                        'value' => $bookingsToday,
                        'color' => 'green',
                        'icon' => 'fa-calendar-day',
                    ],
                    [
                        'title' => 'Delivered Loads This Month',
                        'value' => $deliveredThisMonth,
                        'color' => 'blue',
                        'icon' => 'fa-check-circle',
                    ],
                    [
                        'title' => 'Active Drivers',
                        'value' => $activeDrivers,
                        'color' => 'yellow',
                        'icon' => 'fa-user-tie',
                    ],
                    [
                        'title' => 'Active Transporters',
                        'value' => $activeTransporters,
                        'color' => 'gray',
                        'icon' => 'fa-truck',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div
                    class="bg-white shadow rounded-lg p-5 flex items-center space-x-4 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-3 rounded-full bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600">
                        <i class="fas {{ $card['icon'] }} fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ $card['title'] }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Live Map Section -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="p-5 border-b border-gray-200 font-semibold text-lg text-gray-700">
                Live Map View (GPS Integration)
            </div>
            <div id="map"
                class="h-96 w-full bg-gray-100 flex items-center justify-center text-gray-400 text-xl font-semibold">
                <i class="fas fa-map-marker-alt fa-3x mb-3"></i><br>
                Live truck locations will appear here
            </div>
        </div>


        <!-- Pending KYC Verifications Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-5 border-b border-gray-200 font-semibold text-lg text-gray-700">
                Pending KYC Verifications
            </div>
            <div class="overflow-x-auto p-5">
                @if ($pendingKyc->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Document</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pendingKyc as $kyc)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst($kyc->verifiable_type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $kyc->document_type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kyc->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                  {{ $kyc->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($kyc->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.kyc.show', $kyc->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 italic">No pending KYC verifications.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- FontAwesome CDN for icons --}}
    @push('head-scripts')
        <style>
            /* Fix Leaflet marker icon paths */
            .leaflet-container {
                font: 14px/1.5 "Helvetica Neue", Arial, Helvetica, sans-serif;
            }
        </style>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @endpush


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize the map centered in the US
                const map = L.map('map').setView([39.8283, -98.5795], 4);

                // Add OpenStreetMap tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Static sample data for trucks (replace later with dynamic backend data)
                const trucks = [{
                        id: 1,
                        name: 'Truck 1',
                        lat: 40.7128,
                        lng: -74.0060,
                        status: 'active'
                    }, // New York
                    {
                        id: 2,
                        name: 'Truck 2',
                        lat: 34.0522,
                        lng: -118.2437,
                        status: 'idle'
                    }, // Los Angeles
                    {
                        id: 3,
                        name: 'Truck 3',
                        lat: 41.8781,
                        lng: -87.6298,
                        status: 'offline'
                    }, // Chicago
                    {
                        id: 4,
                        name: 'Truck 4',
                        lat: 29.7604,
                        lng: -95.3698,
                        status: 'active'
                    }, // Houston
                ];

                // Helper function to get marker color based on status
                function getStatusColor(status) {
                    switch (status) {
                        case 'active':
                            return 'green';
                        case 'idle':
                            return 'orange';
                        case 'offline':
                            return 'red';
                        default:
                            return 'blue';
                    }
                }



                // Function to create a FontAwesome-based divIcon with dynamic color
                function getFaMarkerIcon(color) {
                    return L.divIcon({
                        html: `<i class="fas fa-map-marker-alt fa-3x" style="color: ${color};"></i>`,
                        className: '', // remove default styles
                        iconSize: [30, 42], // size of the icon (adjust if needed)
                        iconAnchor: [15, 42], // point of icon which corresponds to marker location
                    });
                }

                // Add markers for each truck with custom FontAwesome icon
                trucks.forEach(truck => {
                    const marker = L.marker([truck.lat, truck.lng], {
                        icon: getFaMarkerIcon(getStatusColor(truck.status))
                    }).addTo(map);

                    marker.bindPopup(
                        `<strong>${truck.name}</strong><br>ID: ${truck.id}<br>Status: ${truck.status}`
                    );
                });
            });
        </script>
    @endpush


@endsection
