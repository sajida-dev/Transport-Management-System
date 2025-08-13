@extends('admin.layouts.app')

@section('title', isset($transporter) ? 'Edit Transporter' : 'Create Transporter')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">
        {{ isset($transporter) ? 'Edit Transporter' : 'Add New Transporter' }}
    </h1>

    <form 
        action="{{ isset($transporter) ? route('admin.transporters.update', $transporter) : route('admin.transporters.store') }}" 
        method="POST" 
        enctype="multipart/form-data" 
        class="bg-white shadow p-6 rounded-lg"
    >
        @csrf
        @if(isset($transporter))
            @method('PUT')
        @endif

       
        {{-- Company & Registration --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
             {{-- User Select (FK) --}}
      
            <x-select 
                name="user_id" 
                label="User" 
                :options="$users->pluck('name', 'id')" 
                :value="old('user_id', $transporter->user_id ?? '')" 
                required 
                :disabled="isset($transporter)"
            />

            <x-input name="company_name" label="Company Name" :value="old('company_name', $transporter->company_name ?? '')" required placeholder="e.g. LoadMasta Logistics" />
            <x-input name="registration_number" label="Registration Number" :value="old('registration_number', $transporter->registration_number ?? '')" required placeholder="Unique registration number" />
           
        </div>

        {{-- Contact --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
             <x-input name="tax_id" label="Tax ID" :value="old('tax_id', $transporter->tax_id ?? '')" placeholder="Optional" />
            <x-input name="phone" label="Phone" :value="old('phone', $transporter->phone ?? '')" required placeholder="+1 234 567 8900" />
            <x-input name="email" label="Email" type="email" :value="old('email', $transporter->email ?? '')" required placeholder="contact@company.com" />
        </div>

        {{-- Address --}}
        <div class="mb-6">
            <x-textarea name="address" label="Address" :value="old('address', $transporter->address ?? '')" required></x-textarea>
        </div>

        {{-- Location --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <x-input name="city" label="City" :value="old('city', $transporter->city ?? '')" required />
            <x-input name="state" label="State" :value="old('state', $transporter->state ?? '')" required />
            <x-input name="postal_code" label="Postal Code" :value="old('postal_code', $transporter->postal_code ?? '')" required />
            <x-input name="country" label="Country" :value="old('country', $transporter->country ?? '')" required />
        </div>

        {{-- Contact Person --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <x-input name="contact_person_name" label="Contact Person Name" :value="old('contact_person_name', $transporter->contact_person_name ?? '')" required />
            <x-input name="contact_person_phone" label="Contact Person Phone" :value="old('contact_person_phone', $transporter->contact_person_phone ?? '')" required />
            <x-input name="contact_person_email" label="Contact Person Email" type="email" :value="old('contact_person_email', $transporter->contact_person_email ?? '')" required />
        </div>

        {{-- License & Insurance --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <x-input name="operating_license_number" label="Operating License Number" :value="old('operating_license_number', $transporter->operating_license_number ?? '')" placeholder="Optional" />
@php
    $licenseExpiry = optional($transporter?->operating_license_expiry)->format('Y-m-d');
    $insuranceExpiry = optional($transporter?->insurance_expiry)->format('Y-m-d');
@endphp

<x-input 
    name="operating_license_expiry" 
    label="Operating License Expiry" 
    type="date" 
    :value="old('operating_license_expiry', $licenseExpiry)" 
/>            <x-input name="insurance_policy_number" label="Insurance Policy Number" :value="old('insurance_policy_number', $transporter->insurance_policy_number ?? '')" placeholder="Optional" />
            <x-input name="insurance_expiry" label="Insurance Expiry Date" type="date" :value="old('insurance_expiry', $insuranceExpiry)" />
           
        </div>

       
      
        {{-- Notes --}}
        <div class="mb-6">
            <x-textarea name="notes" label="Notes" :value="old('notes', $transporter->notes ?? '')" placeholder="Any additional information about the transporter" rows="6">
         </x-textarea>
        </div>

        {{-- Logo --}}
        <div class="mb-6">
            <x-input name="logo" label="Company Logo" type="file" />
            @if(isset($transporter) && $transporter->logo)
                <div class="mt-2">
                    <img src="{{ Storage::url($transporter->logo) }}" alt="Logo" class="h-20 w-auto rounded" />
                </div>
            @endif
        </div>

        {{-- KYC Documents --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">KYC Documents</label>
            <input type="file" name="documents[]" multiple class="w-full border rounded-md p-2" />
            @if(isset($transporter) && $transporter->documents)
                <ul class="mt-2 text-sm text-gray-600">
                    @foreach(json_decode($transporter->documents) as $doc)
                        <li><a href="{{ Storage::url($doc) }}" target="_blank" class="underline text-indigo-600">{{ basename($doc) }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3">
            {{-- cancel button --}}
                <a href="{{ route('admin.transporters.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
            {{-- submit button --}}
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                {{ isset($transporter) ? 'Update Transporter' : 'Save Transporter' }}
            </button>
        </div>
    </form>
</div>
@endsection
