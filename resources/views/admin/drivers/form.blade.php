@extends('admin.layouts.app')

@section('title', isset($driver) ? 'Edit Driver' : 'Create Driver')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($driver) ? 'Edit Driver' : 'Add New Driver' }}
        </h1>

        <form action="{{ isset($driver) ? route('admin.drivers.update', $driver->id) : route('admin.drivers.store') }}"
            method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded-lg">
            @csrf
            @if (isset($driver))
                @method('PUT')
            @endif

            {{-- Personal Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="first_name" label="First Name" placeholder="e.g. John" title="Driver's first name"
                    value="{{ old('first_name', $driver->user->first_name ?? '') }}" required />

                <x-input name="last_name" label="Last Name" placeholder="e.g. Doe" title="Driver's last name"
                    value="{{ old('last_name', $driver->user->last_name ?? '') }}" required />

                <x-input name="email" label="Email Address" type="email" placeholder="e.g. john@example.com"
                    title="Valid email address" value="{{ old('email', $driver->user->email ?? '') }}" required />

                <x-input name="phone_number" label="Phone Number" placeholder="e.g. +1 123 456 7890"
                    title="Include country code if needed"
                    value="{{ old('phone_number', $driver->user->phone_number ?? '') }}" required />

                <x-input name="license_number" label="License Number" placeholder="e.g. D1234567"
                    title="Official driver’s license number"
                    value="{{ old('license_number', $driver->license_number ?? '') }}" required />

                <x-select name="license_type" label="License Type" :options="[
                    'light_vehicle' => 'Light Vehicle',
                    'heavy_vehicle' => 'Heavy Vehicle',
                    'commercial' => 'Commercial',
                    'specialized' => 'Specialized',
                ]" :value="old('license_type', $driver->license_type ?? '')" required
                    title="Choose the type of license the driver holds" />

                <x-input name="license_expiry_date" label="License Expiry Date" type="date"
                    title="Date when the license expires"
                    value="{{ old('license_expiry_date', isset($driver->license_expiry_date) ? $driver->license_expiry_date->format('Y-m-d') : '') }}"
                    required />

                <x-input name="date_of_birth" label="Date of Birth" type="date" title="Driver's birth date"
                    value="{{ old('date_of_birth', isset($driver->date_of_birth) ? $driver->date_of_birth->format('Y-m-d') : '') }}"
                    required />

                {{-- Password (only for creating new driver) --}}
                @if (!isset($driver))
                    <div class="mb-2 relative">
                        <label class="block text-gray-700 mb-1" for="password">Password <span
                                class="text-red-500">*</span></label>
                        <input id="password" name="password" type="password" placeholder="Enter a strong password"
                            class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 pr-10"
                            required title="Password must be at least 8 characters">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-9 text-gray-600">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path id="eyeOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            {{-- Address --}}
            <x-textarea name="address" label="Address" placeholder="Full residential address"
                title="Driver’s full home address" value="{{ old('address', $driver->address ?? '') }}" required />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="city" label="City" placeholder="e.g. New York" title="City name"
                    value="{{ old('city', $driver->city ?? '') }}" required />

                <x-input name="state" label="State" placeholder="e.g. NY" title="State or province"
                    value="{{ old('state', $driver->state ?? '') }}" required />

                <x-input name="postal_code" label="Postal Code" placeholder="e.g. 10001" title="ZIP or postal code"
                    value="{{ old('postal_code', $driver->postal_code ?? '') }}" required />

                <x-input name="country" label="Country" placeholder="e.g. United States" title="Country of residence"
                    value="{{ old('country', $driver->country ?? '') }}" required />

                <x-select name="transporter_id" label="Transporter" required :options="$transporters->pluck('company_name', 'id')"
                    title="Select the company or transporter the driver works with" :value="old('transporter_id', $driver->transporter_id ?? '')" />

                <x-input name="experience_years" label="Years of Experience" type="number" min="0"
                    placeholder="e.g. 5" title="Number of years the driver has been working"
                    value="{{ old('experience_years', $driver->experience_years ?? '') }}" required />
            </div>

            {{-- Emergency Contact --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="emergency_contact_name" label="Emergency Contact Name" placeholder="e.g. Jane Doe"
                    title="Person to contact in case of emergency"
                    value="{{ old('emergency_contact_name', $driver->emergency_contact_name ?? '') }}" required />

                <x-input name="emergency_contact_phone" label="Emergency Contact Phone"
                    placeholder="e.g. +1 987 654 3210" title="Emergency contact’s phone number"
                    value="{{ old('emergency_contact_phone', $driver->emergency_contact_phone ?? '') }}" required />

                <x-input name="emergency_contact_relationship" label="Relationship"
                    placeholder="e.g. Wife, Brother, Friend" title="Relationship of emergency contact to driver"
                    value="{{ old('emergency_contact_relationship', $driver->emergency_contact_relationship ?? '') }}"
                    required />
            </div>

            {{-- Medical Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="medical_certificate_number" label="Medical Certificate Number" placeholder="e.g. MC123456"
                    title="Optional: driver's medical certificate number"
                    value="{{ old('medical_certificate_number', $driver->medical_certificate_number ?? '') }}" />

                <x-input name="medical_certificate_expiry" label="Medical Certificate Expiry" type="date"
                    title="Date the medical certificate expires"
                    value="{{ old('medical_certificate_expiry', isset($driver->medical_certificate_expiry) ? $driver->medical_certificate_expiry->format('Y-m-d') : '') }}" />
            </div>

            {{-- Notes --}}
            <x-textarea name="notes" label="Notes" placeholder="Any extra information or comments"
                value="{{ old('notes', $driver->notes ?? '') }}" />

            {{-- File Uploads --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="profile_photo" label="Profile Photo" type="file" title="Upload driver's photo" />
                <x-input name="license_photo" label="License Photo" type="file"
                    title="Upload scanned license copy" />
                <x-input name="medical_certificate_photo" label="Medical Certificate Photo" type="file"
                    title="Upload medical certificate copy" />
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium">KYC Documents</label>
                <input type="file" name="kyc_documents[]" multiple title="Upload all supporting ID/KYC documents">
            </div>

            {{-- Vehicle Types --}}
            <x-chip-multiselect required name="vehicle_types" label="Vehicle Types" :options="['truck' => 'Truck', 'bus' => 'Bus', 'van' => 'Van', 'taxi' => 'Taxi']" :selected="old(
                'vehicle_types',
                is_array($driver->vehicle_types ?? null)
                    ? $driver->vehicle_types
                    : json_decode($driver->vehicle_types ?? '[]', true),
            )"
                title="Select the types of vehicles the driver is licensed and experienced to operate" />

            {{-- Actions --}}
            <div class="flex justify-end mt-6 gap-2">
                <a href="{{ route('admin.drivers.index') }}" class="px-4 py-2 border">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white">
                    {{ isset($driver) ? 'Update Driver' : 'Save Driver' }}
                </button>
            </div>
        </form>
    </div>

    {{-- Password Toggle Script --}}
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("eyeIcon");
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a10.055 10.055 0 012.034-3.368M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 3l18 18" />`;
            } else {
                input.type = "password";
                icon.innerHTML =
                    `<path id="eyeOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>
@endsection
