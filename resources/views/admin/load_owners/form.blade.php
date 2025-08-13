@extends('admin.layouts.app')

@section('title', isset($load_owner) ? 'Edit Load Owner' : 'Create Load Owner')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($load_owner) ? 'Edit Load Owner' : 'Register New Load Owner' }}
        </h1>

        <form
            action="{{ isset($load_owner) ? route('admin.load_owners.update', ['load_owner' => $load_owner->id]) : route('admin.load_owners.store') }}"
            method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded-lg">
            @csrf
            @if (isset($load_owner))
                @method('PUT')
            @endif

            {{-- USER FIELDS --}}
            <h2 class="text-xl font-semibold mb-4">User Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="name" label="Name" required value="{{ old('name', $user->name ?? '') }}" />
                <x-input name="first_name" label="First Name" value="{{ old('first_name', $user->first_name ?? '') }}" />
                <x-input name="last_name" label="Last Name" value="{{ old('last_name', $user->last_name ?? '') }}" />
                <x-input name="email" label="Email" type="email" required
                    value="{{ old('email', $user->email ?? '') }}" />
                <x-input name="phone_number" label="Phone Number"
                    value="{{ old('phone_number', $user->phone_number ?? '') }}" />

                {{-- Example: Gender select --}}
                <x-select name="gender" label="Gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']"
                    value="{{ old('gender', $user->gender ?? '') }}" />

                <x-input name="nrc" label="NRC" value="{{ old('nrc', $user->nrc ?? '') }}" />


                {{-- Password fields --}}
                @if (!isset($load_owner))
                    <x-password-input name="password" label="Password" required autocomplete="new-password" />
                    <x-password-input name="password_confirmation" label="Confirm Password" required
                        autocomplete="new-password" />
                @endif
            </div>

            {{-- Full width textarea --}}
            <div class="mb-6">
                <x-textarea name="address" label="Address" value="{{ old('address', $user->address ?? '') }}"
                    class="w-full" />
            </div>

            {{-- LOAD OWNER FIELDS --}}
            <h2 class="text-xl font-semibold mt-8 mb-4">Load Owner Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="company_name" label="Company Name" required
                    value="{{ old('company_name', $load_owner->company_name ?? '') }}" />
                <x-input name="contact_person_name" label="Contact Person Name" required
                    value="{{ old('contact_person_name', $load_owner->contact_person_name ?? '') }}" />
                <x-input name="contact_person_phone" label="Contact Person Phone" required
                    value="{{ old('contact_person_phone', $load_owner->contact_person_phone ?? '') }}" />
                <x-input name="contact_person_email" label="Contact Person Email" type="email" required
                    value="{{ old('contact_person_email', $load_owner->contact_person_email ?? '') }}" />
                <x-input name="city" label="City" required value="{{ old('city', $load_owner->city ?? '') }}" />
                <x-input name="state" label="State" required value="{{ old('state', $load_owner->state ?? '') }}" />
                <x-input name="postal_code" label="Postal Code" required
                    value="{{ old('postal_code', $load_owner->postal_code ?? '') }}" />
                <x-input name="country" label="Country" required
                    value="{{ old('country', $load_owner->country ?? '') }}" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="tax_id" label="Tax ID" value="{{ old('tax_id', $load_owner->tax_id ?? '') }}" />
                <x-input name="business_license_number" label="Business License Number"
                    value="{{ old('business_license_number', $load_owner->business_license_number ?? '') }}" />
                <x-input name="business_license_expiry" label="Business License Expiry" type="date"
                    value="{{ old('business_license_expiry', $load_owner->business_license_expiry ?? '') }}" />


            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-input name="profile_image" label="Profile Image" type="file" />
                <x-input name="logo" label="Company Logo" type="file" />
                <x-input name="documents[]" label="KYC Documents" type="file" multiple />
            </div>

            {{-- Full width textarea for notes --}}
            <div class="mb-6">
                <x-textarea name="notes" label="Notes" value="{{ old('notes', $load_owner->notes ?? '') }}"
                    class="w-full" />
            </div>

            {{-- Submit --}}
            <div class="flex justify-end mt-6 gap-2">
                <a href="{{ route('admin.load_owners.index') }}" class="px-4 py-2 border rounded text-gray-600">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded">
                    {{ isset($load_owner) ? 'Update Load Owner' : 'Create Load Owner' }}
                </button>
            </div>
        </form>
    </div>
@endsection
