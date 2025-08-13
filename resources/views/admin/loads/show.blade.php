@extends('admin.layouts.app')

@section('title', 'Load Details')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Load #{{ $load->load_number }}</h1>
            <div class="space-x-2">
                @can('transporter.edit')
                    <a href="{{ route('admin.loads.edit', $load) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                @endcan

                @if ($load->status === 'pending')
                    <button onclick="openApproveModal()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i> Assign Driver
                    </button>

                    <button onclick="openRejectModal()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                @endif
                <a href="{{ route('admin.loads.destroy', $load->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    <i class="fas fa-trash mr-2"></i> Delete
                </a>

                <a href="{{ route('admin.loads.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>

            </div>
        </div>

        {{-- Load Summary --}}
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Title</h2>
                    <p class="text-gray-600">{{ $load->title }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Load Type</h2>
                    <p class="capitalize text-gray-600">{{ $load->load_type }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Status</h2>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                    @switch($load->status)
                        @case('pending') bg-yellow-100 text-yellow-800 @break
                        @case('assigned') bg-blue-100 text-blue-800 @break
                        @case('in_transit') bg-indigo-100 text-indigo-800 @break
                        @case('delivered') bg-green-100 text-green-800 @break
                        @case('cancelled') bg-red-100 text-red-800 @break
                        @case('completed') bg-gray-100 text-gray-800 @break
                        @default bg-gray-100 text-gray-600
                    @endswitch">
                        <i class="fas fa-circle mr-1 text-xs"></i> {{ ucfirst(str_replace('_', ' ', $load->status)) }}
                    </span>
                </div>
            </div>
            {{-- Description --}}
            @if ($load->description)
                <div class="my-3">
                    <h2 class="text-lg font-semibold mb-2 text-gray-800">Description</h2>
                    <p class="text-gray-600">{{ $load->description }}</p>
                </div>
            @endif
        </div>



        {{-- Pickup & Delivery Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Pickup --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4 text-blue-600"><i class="fas fa-truck-loading mr-1"></i> Pickup Info
                </h2>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li><strong>Location:</strong> {{ $load->pickup_location }}</li>
                    <li><strong>Address:</strong> {{ $load->pickup_address }}, {{ $load->pickup_city }},
                        {{ $load->pickup_state }}, {{ $load->pickup_country }}</li>
                    <li><strong>Postal Code:</strong> {{ $load->pickup_postal_code }}</li>
                    <li><strong>Date & Time:</strong> {{ $load->pickup_date }} at {{ $load->pickup_time }}</li>
                    <li><strong>Contact:</strong> {{ $load->pickup_contact_name }} ({{ $load->pickup_contact_phone }})
                    </li>
                    <li><strong>Instructions:</strong> {{ $load->pickup_instructions ?? '—' }}</li>
                </ul>
                <div id="pickup-map" class="w-full h-48 mt-4 rounded shadow"></div>
            </div>

            {{-- Delivery --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4 text-green-600"><i class="fas fa-truck-moving mr-1"></i> Delivery
                    Info
                </h2>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li><strong>Location:</strong> {{ $load->delivery_location }}</li>
                    <li><strong>Address:</strong> {{ $load->delivery_address }}, {{ $load->delivery_city }},
                        {{ $load->delivery_state }}, {{ $load->delivery_country }}</li>
                    <li><strong>Postal Code:</strong> {{ $load->delivery_postal_code }}</li>
                    <li><strong>Date & Time:</strong> {{ $load->delivery_date }} at {{ $load->delivery_time }}</li>
                    <li><strong>Contact:</strong> {{ $load->delivery_contact_name }} ({{ $load->delivery_contact_phone }})
                    </li>
                    <li><strong>Instructions:</strong> {{ $load->delivery_instructions ?? '—' }}</li>
                </ul>
                <div id="delivery-map" class="w-full h-48 mt-4 rounded shadow"></div>
            </div>
        </div>

        {{-- Pricing and Details --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-money-bill-wave mr-1"></i> Pricing
                </h2>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li><strong>Total Amount:</strong> {{ number_format($load->total_amount, 2) }} {{ $load->currency }}
                    </li>
                    <li><strong>Rate per KM:</strong> {{ $load->rate_per_km ?? '—' }}</li>
                    <li><strong>Distance:</strong> {{ $load->total_distance_km ?? '—' }} km</li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-info-circle mr-1"></i> Load Details
                </h2>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li><strong>Weight:</strong> {{ $load->weight_tonnes }} tonnes</li>
                    <li><strong>Dimensions (L×W×H):</strong>
                        {{ $load->length_meters ?? '—' }} × {{ $load->width_meters ?? '—' }} ×
                        {{ $load->height_meters ?? '—' }} m
                    </li>
                    <li><strong>Priority:</strong> {{ ucfirst($load->priority) }}</li>
                    <li><strong>Refrigeration:</strong> {{ $load->requires_refrigeration ? 'Yes' : 'No' }}</li>
                    <li><strong>Special Equipment:</strong> {{ $load->requires_special_equipment ? 'Yes' : 'No' }}</li>
                    <li><strong>Hazardous:</strong> {{ $load->is_hazardous ? 'Yes' : 'No' }}</li>
                </ul>
            </div>
        </div>

        {{-- Notes --}}
        @if ($load->notes)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Additional Notes</h2>
                <p class="text-gray-600">{{ $load->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Approve Load Modal -->
    <div id="approveLoadModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Assign Driver
                            </h3>

                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-col">
                    <form id="approveLoadForm" action="{{ route('admin.loads.update-status', ['load' => $load]) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="action" value="assign_driver">
                        <x-select name="driver_id" label="Select Driver" :options="$drivers" placeholder="Choose a driver"
                            required />
                        <div class="flex flex-row justify-end mt-4">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Assign Driver
                            </button>
                            <button type="button" onclick="closeApproveModal()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Reject Load Modal -->
    <div id="rejectLoadModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Cancel Load
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to cancel this load? This action cannot be undone.
                                </p>
                                <div class="mt-4">
                                    <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Cancel
                                        Reason</label>
                                    <textarea id="cancel_reason" name="cancel_reason" rows="3"
                                        class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="rejectLoadForm" action="{{ route('admin.loads.update-status', ['load' => $load]) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="action" value="cancel">
                        <input type="hidden" name="cancel_reason" id="cancel_reason_input">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reject
                        </button>
                    </form>
                    <button type="button" onclick="closeRejectModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Scripts --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function initMap(id, lat, lng) {
            const map = L.map(id).setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(map);
            L.marker([lat, lng]).addTo(map);
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if ($load->pickup_latitude && $load->pickup_longitude)
                initMap('pickup-map', {{ $load->pickup_latitude }}, {{ $load->pickup_longitude }});
            @endif

            @if ($load->delivery_latitude && $load->delivery_longitude)
                initMap('delivery-map', {{ $load->delivery_latitude }}, {{ $load->delivery_longitude }});
            @endif
        });
    </script>
@endsection


@push('scripts')
    <script>
        // Function to open the approve modal
        function openApproveModal() {
            document.getElementById('approveLoadModal').classList.remove('hidden');
        }

        // Function to close the approve modal
        function closeApproveModal() {
            document.getElementById('approveLoadModal').classList.add('hidden');
        }

        // Function to open the reject modal
        function openRejectModal() {
            document.getElementById('rejectLoadModal').classList.remove('hidden');
        }

        // Function to close the reject modal
        function closeRejectModal() {
            document.getElementById('rejectLoadModal').classList.add('hidden');
        }

        // Update rejection reason before form submission
        document.getElementById('rejectLoadForm').addEventListener('submit', function(e) {
            const reason = document.getElementById('cancel_reason').value;
            document.getElementById('cancel_reason_input').value = reason;
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const approveModal = document.getElementById('approveLoadModal');
            const rejectModal = document.getElementById('rejectLoadModal');

            if (event.target === approveModal) {
                closeApproveModal();
            }
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        });

        // Close modals when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeApproveModal();
                closeRejectModal();
            }
        });
    </script>
@endpush
