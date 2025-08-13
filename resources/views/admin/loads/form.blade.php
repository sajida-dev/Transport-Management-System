{{-- resources/views/admin/loads/form.blade.php --}}

@extends('admin.layouts.app')

@section('title', isset($load) ? 'Edit Load' : 'Create Load')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($load) ? 'Edit Load' : 'Post New Load' }}
        </h1>

        <form action="{{ isset($load) ? route('admin.loads.update', $load->id) : route('admin.loads.store') }}" method="POST"
            enctype="multipart/form-data" class="bg-white shadow p-6 rounded-lg">
            @csrf
            @if (isset($load))
                @method('PUT')
            @endif

            {{-- Title --}}
            <h2 class="text-xl font-semibold mb-4">Load Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Load Owner --}}
                <x-select name="load_owner_id" label="Load Owner" :options="$loadOwners"
                    value="{{ old('load_owner_id', $load->load_owner_id ?? '') }}" required
                    title="Select the owner responsible for this load" />

                {{-- Load Number --}}
                <x-input name="load_number" label="Load Number" placeholder="Unique reference e.g. LOAD-2025-001"
                    value="{{ old('load_number', $load->load_number ?? '') }}" required
                    title="System-wide unique identifier" />

                {{-- Title --}}
                <x-input name="title" label="Load Title" placeholder="Brief title e.g. Cement Shipment to Lusaka"
                    value="{{ old('title', $load->title ?? '') }}" required title="Short description of the shipment" />

                {{-- Load Type --}}
                <x-select name="load_type" label="Load Type" :options="[
                    'general' => 'General',
                    'refrigerated' => 'Refrigerated',
                    'hazardous' => 'Hazardous',
                    'oversized' => 'Oversized',
                    'fragile' => 'Fragile',
                    'liquid' => 'Liquid',
                    'other' => 'Other',
                ]"
                    value="{{ old('load_type', $load->load_type ?? '') }}" required title="Category or nature of goods" />

                {{-- Weight --}}
                <x-input name="weight_tonnes" label="Weight (tonnes)" type="number" step="0.01" placeholder="e.g. 24.5"
                    value="{{ old('weight_tonnes', $load->weight_tonnes ?? '') }}" required
                    title="Total weight of cargo in tonnes" />

                {{-- Dimensions --}}
                <x-input name="length_meters" label="Length (m)" type="number" step="0.01" placeholder="e.g. 5.2"
                    value="{{ old('length_meters', $load->length_meters ?? '') }}" />

                <x-input name="width_meters" label="Width (m)" type="number" step="0.01" placeholder="e.g. 2.3"
                    value="{{ old('width_meters', $load->width_meters ?? '') }}" />

                <x-input name="height_meters" label="Height (m)" type="number" step="0.01" placeholder="e.g. 2.5"
                    value="{{ old('height_meters', $load->height_meters ?? '') }}" />
            </div>
            {{-- Description --}}
            <x-textarea name="description" label="Description" placeholder="Optional: Include details about the load"
                value="{{ old('description', $load->description ?? '') }}" />

            {{-- Pickup Section --}}
            <h2 class="text-xl font-semibold mb-4 mt-10">Pickup Information</h2>
            <div id="pickup-map" class="w-full h-64 rounded shadow mb-2"></div>
            <button type="button" onclick="getPickupLocation()"
                class="mb-4 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                Use My Location
            </button>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="pickup_location" label="Pickup Location" placeholder="e.g. Kitwe Industrial Yard"
                    value="{{ old('pickup_location', $load->pickup_location ?? '') }}" required />

                <x-input name="pickup_address" label="Pickup Address" placeholder="Street, building, or landmark"
                    value="{{ old('pickup_address', $load->pickup_address ?? '') }}" required />

                <x-input name="pickup_city" label="City" placeholder="e.g. Kitwe"
                    value="{{ old('pickup_city', $load->pickup_city ?? '') }}" required />

                <x-input name="pickup_state" label="State/Province" placeholder="e.g. Copperbelt"
                    value="{{ old('pickup_state', $load->pickup_state ?? '') }}" required />

                <x-input name="pickup_postal_code" label="Postal Code" placeholder="e.g. 50100"
                    value="{{ old('pickup_postal_code', $load->pickup_postal_code ?? '') }}" required />

                <x-input name="pickup_country" label="Country" placeholder="e.g. Zambia"
                    value="{{ old('pickup_country', $load->pickup_country ?? '') }}" required />

                <x-input name="pickup_latitude" label="Latitude" readonly
                    value="{{ old('pickup_latitude', $load->pickup_latitude ?? '') }}" />

                <x-input name="pickup_longitude" label="Longitude" readonly
                    value="{{ old('pickup_longitude', $load->pickup_longitude ?? '') }}" />

                <x-input name="pickup_date" label="Pickup Date" type="date"
                    value="{{ old('pickup_date', isset($load->pickup_date) ? $load->pickup_date->format('Y-m-d') : '') }}"
                    required />

                <x-input name="pickup_time" label="Pickup Time" type="time"
                    value="{{ old('pickup_time', $load->pickup_time ?? '') }}" required />

                <x-input name="pickup_contact_name" label="Contact Name" placeholder="Optional: Person on site"
                    value="{{ old('pickup_contact_name', $load->pickup_contact_name ?? '') }}" />

                <x-input name="pickup_contact_phone" label="Contact Phone" placeholder="Optional: +260..."
                    value="{{ old('pickup_contact_phone', $load->pickup_contact_phone ?? '') }}" />


            </div>
            <x-textarea name="pickup_instructions" label="Pickup Instructions" placeholder="e.g. Ask for John at gate"
                value="{{ old('pickup_instructions', $load->pickup_instructions ?? '') }}" />

            {{-- Delivery Section --}}
            <h2 class="text-xl font-semibold mb-4 mt-10">Delivery Information</h2>
            <div id="delivery-map" class="w-full h-64 rounded shadow mb-2"></div>
            <button type="button" onclick="getDeliveryLocation()"
                class="mb-4 px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                Use My Location
            </button>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="delivery_location" label="Delivery Location" placeholder="e.g. Lusaka Warehouse"
                    value="{{ old('delivery_location', $load->delivery_location ?? '') }}" required />

                <x-input name="delivery_address" label="Delivery Address" placeholder="Street, building, or landmark"
                    value="{{ old('delivery_address', $load->delivery_address ?? '') }}" required />

                <x-input name="delivery_city" label="City" placeholder="e.g. Lusaka"
                    value="{{ old('delivery_city', $load->delivery_city ?? '') }}" required />

                <x-input name="delivery_state" label="State/Province" placeholder="e.g. Lusaka Province"
                    value="{{ old('delivery_state', $load->delivery_state ?? '') }}" required />

                <x-input name="delivery_postal_code" label="Postal Code" placeholder="e.g. 10101"
                    value="{{ old('delivery_postal_code', $load->delivery_postal_code ?? '') }}" required />

                <x-input name="delivery_country" label="Country" placeholder="e.g. Zambia"
                    value="{{ old('delivery_country', $load->delivery_country ?? '') }}" required />

                <x-input name="delivery_latitude" label="Latitude" readonly
                    value="{{ old('delivery_latitude', $load->delivery_latitude ?? '') }}" />

                <x-input name="delivery_longitude" label="Longitude" readonly
                    value="{{ old('delivery_longitude', $load->delivery_longitude ?? '') }}" />

                <x-input name="delivery_date" label="Delivery Date" type="date"
                    value="{{ old('delivery_date', isset($load->delivery_date) ? $load->delivery_date->format('Y-m-d') : '') }}"
                    required />

                <x-input name="delivery_time" label="Delivery Time" type="time"
                    value="{{ old('delivery_time', $load->delivery_time ?? '') }}" required />

                <x-input name="delivery_contact_name" label="Contact Name" placeholder="Optional"
                    value="{{ old('delivery_contact_name', $load->delivery_contact_name ?? '') }}" />

                <x-input name="delivery_contact_phone" label="Contact Phone" placeholder="Optional"
                    value="{{ old('delivery_contact_phone', $load->delivery_contact_phone ?? '') }}" />


            </div>
            <x-textarea name="delivery_instructions" label="Delivery Instructions"
                placeholder="e.g. Deliver only between 8am and 5pm"
                value="{{ old('delivery_instructions', $load->delivery_instructions ?? '') }}" />

            {{-- Pricing --}}
            <h2 class="text-xl font-semibold mb-4 mt-10">Pricing</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="rate_per_km" label="Rate per KM (Optional)" type="number" step="0.01"
                    placeholder="e.g. 15.00" value="{{ old('rate_per_km', $load->rate_per_km ?? '') }}" />

                <x-input name="total_distance_km" label="Distance (KM)" type="number" step="0.01"
                    placeholder="e.g. 420" value="{{ old('total_distance_km', $load->total_distance_km ?? '') }}" />

                <x-input name="total_amount" label="Total Amount" type="number" step="0.01"
                    placeholder="e.g. 25000.00" value="{{ old('total_amount', $load->total_amount ?? '') }}" required />

                <x-select name="currency" label="Currency" :options="[
                    'USD' => 'US Dollar (USD)',
                    'EUR' => 'Euro (EUR)',
                    'GBP' => 'British Pound (GBP)',
                    'ZMW' => 'Zambian Kwacha (ZMW)',
                    'ZAR' => 'South African Rand (ZAR)',
                    'KES' => 'Kenyan Shilling (KES)',
                    'NGN' => 'Nigerian Naira (NGN)',
                    'CNY' => 'Chinese Yuan (CNY)',
                    'INR' => 'Indian Rupee (INR)',
                    'AED' => 'UAE Dirham (AED)',
                ]"
                    value="{{ old('currency', $load->currency ?? 'ZMW') }}" required
                    title="Choose the currency in which payment is calculated" />

            </div>

            {{-- Extra --}}
            <h2 class="text-xl font-semibold mb-4 mt-10">Other Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <x-select name="priority" label="Priority" :options="['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent']"
                    value="{{ old('priority', $load->priority ?? '') }}" />

                <div class="flex flex-row justify-evenly items-center">
                    <div class="flex items-center space-x-2 mt-5">
                        <input type="checkbox" name="requires_refrigeration" id="requires_refrigeration" value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            {{ old('requires_refrigeration', $load->requires_refrigeration ?? false) ? 'checked' : '' }}>
                        <label for="requires_refrigeration" class="text-sm text-gray-700">Requires Refrigeration</label>
                    </div>

                    <div class="flex items-center space-x-2 mt-5">
                        <input type="checkbox" name="requires_special_equipment" id="requires_special_equipment"
                            value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            {{ old('requires_special_equipment', $load->requires_special_equipment ?? false) ? 'checked' : '' }}>
                        <label for="requires_special_equipment" class="text-sm text-gray-700">Special Equipment
                            Needed</label>
                    </div>

                    <div class="flex items-center space-x-2 mt-5">
                        <input type="checkbox" name="is_hazardous" id="is_hazardous" value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            {{ old('is_hazardous', $load->is_hazardous ?? false) ? 'checked' : '' }}>
                        <label for="is_hazardous" class="text-sm text-gray-700">Hazardous Cargo</label>
                    </div>
                </div>



            </div>
            <x-textarea name="notes" label="Additional Notes" placeholder="e.g. Keep cargo under shade"
                value="{{ old('notes', $load->notes ?? '') }}" />

            {{-- Submit --}}
            <div class="flex justify-end mt-6 gap-2">
                <a href="{{ route('admin.loads.index') }}" class="px-4 py-2 border rounded text-gray-600">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    {{ isset($load) ? 'Update Load' : 'Create Load' }}
                </button>
            </div>
        </form>

    </div>

    {{-- Map Scripts --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function setupMap(mapId, latInput, lngInput, defaultLat = -15.3875, defaultLng = 28.3228) {
            const map = L.map(mapId).setView([defaultLat, defaultLng], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(map);

            let marker;

            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                document.querySelector(`[name="${latInput}"]`).value = lat;
                document.querySelector(`[name="${lngInput}"]`).value = lng;

                if (marker) marker.setLatLng(e.latlng);
                else marker = L.marker(e.latlng).addTo(map);
            });

            return map;
        }

        const pickupMap = setupMap('pickup-map', 'pickup_latitude', 'pickup_longitude');
        const deliveryMap = setupMap('delivery-map', 'delivery_latitude', 'delivery_longitude');

        function getCurrentLocationFor(map, latInput, lngInput) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    map.setView([lat, lng], 14);
                    document.querySelector(`[name="${latInput}"]`).value = lat;
                    document.querySelector(`[name="${lngInput}"]`).value = lng;
                    L.marker([lat, lng]).addTo(map);
                });
            } else {
                alert("Geolocation not supported.");
            }
        }

        function getPickupLocation() {
            getCurrentLocationFor(pickupMap, 'pickup_latitude', 'pickup_longitude');
        }

        function getDeliveryLocation() {
            getCurrentLocationFor(deliveryMap, 'delivery_latitude', 'delivery_longitude');
        }
    </script>
@endsection
