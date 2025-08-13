@extends('admin.layouts.app')

@section('title', isset($booking) ? 'Edit Booking' : 'Create Booking')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($booking) ? 'Edit Booking' : 'Create New Booking' }}
        </h1>

        <form action="{{ isset($booking) ? route('admin.bookings.update', $booking->id) : route('admin.bookings.store') }}"
            method="POST" class="bg-white shadow p-6 rounded-lg">
            @csrf
            @if (isset($booking))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Foreign keys: load_id, transporter_id, truck_id, driver_id --}}
                <x-select name="load_id" label="Load" :options="$loads ?? []" required
                    value="{{ old('load_id', $booking->load_id ?? '') }}" placeholder="Select Load" />

                <x-select name="transporter_id" label="Transporter" :options="$transporters ?? []" required
                    value="{{ old('transporter_id', $booking->transporter_id ?? '') }}" placeholder="Select Transporter" />

                <x-select name="truck_id" label="Truck" :options="$trucks ?? []"
                    value="{{ old('truck_id', $booking->truck_id ?? '') }}" placeholder="Select Truck (optional)" />

                <x-select name="driver_id" label="Driver" :options="$drivers ?? []"
                    value="{{ old('driver_id', $booking->driver_id ?? '') }}" placeholder="Select Driver (optional)" />

                {{-- Quoted and Accepted Amount --}}
                <x-input name="quoted_amount" label="Quoted Amount" type="number" step="0.01" min="0" required
                    value="{{ old('quoted_amount', $booking->quoted_amount ?? '') }}" />

                <x-input name="accepted_amount" label="Accepted Amount" type="number" step="0.01" min="0"
                    value="{{ old('accepted_amount', $booking->accepted_amount ?? '') }}" />

                {{-- Currency --}}
                <x-select name="currency" label="Currency" required :options="[
                    'USD' => 'USD - US Dollar',
                    'EUR' => 'EUR - Euro',
                    'GBP' => 'GBP - British Pound',
                    'ZMW' => 'ZMW - Zambian Kwacha',
                    'KES' => 'KES - Kenyan Shilling',
                    'NGN' => 'NGN - Nigerian Naira',
                    'ZAR' => 'ZAR - South African Rand',
                    'INR' => 'INR - Indian Rupee',
                    'CNY' => 'CNY - Chinese Yuan',
                    'JPY' => 'JPY - Japanese Yen',
                    'CAD' => 'CAD - Canadian Dollar',
                    'AUD' => 'AUD - Australian Dollar',
                    'CHF' => 'CHF - Swiss Franc',
                    'AED' => 'AED - UAE Dirham',
                    'SAR' => 'SAR - Saudi Riyal',
                ]" :value="old('currency', $booking->currency ?? 'USD')" />
            </div>
            {{-- Notes and Special Instructions --}}
            <x-textarea name="notes" label="Notes"
                placeholder="Additional notes">{{ old('notes', $booking->notes ?? '') }}</x-textarea>

            <x-textarea name="special_instructions" label="Special Instructions"
                placeholder="Special instructions">{{ old('special_instructions', $booking->special_instructions ?? '') }}</x-textarea>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Status and Payment Status --}}
                <x-select name="status" label="Status" :options="[
                    'pending' => 'Pending',
                    'accepted' => 'Accepted',
                    'rejected' => 'Rejected',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]" required
                    value="{{ old('status', $booking->status ?? 'pending') }}" />

                <x-select name="payment_status" label="Payment Status" :options="[
                    'pending' => 'Pending',
                    'partial' => 'Partial',
                    'paid' => 'Paid',
                    'overdue' => 'Overdue',
                ]" required
                    value="{{ old('payment_status', $booking->payment_status ?? 'pending') }}" />

                {{-- Status timestamps --}}

                <x-input name="quoted_at" label="Quoted At" type="datetime-local"
                    value="{{ old('quoted_at', isset($booking->quoted_at) ? $booking->quoted_at->format('Y-m-d\TH:i') : '') }}" />
                <x-input name="accepted_at" label="Accepted At" type="datetime-local"
                    value="{{ old('accepted_at', isset($booking->accepted_at) ? $booking->accepted_at->format('Y-m-d\TH:i') : '') }}" />
                <x-input name="rejected_at" label="Rejected At" type="datetime-local"
                    value="{{ old('rejected_at', isset($booking->rejected_at) ? $booking->rejected_at->format('Y-m-d\TH:i') : '') }}" />
                <x-input name="started_at" label="Started At" type="datetime-local"
                    value="{{ old('started_at', isset($booking->started_at) ? $booking->started_at->format('Y-m-d\TH:i') : '') }}" />
                <x-input name="completed_at" label="Completed At" type="datetime-local"
                    value="{{ old('completed_at', isset($booking->completed_at) ? $booking->completed_at->format('Y-m-d\TH:i') : '') }}" />
                <x-input name="cancelled_at" label="Cancelled At" type="datetime-local"
                    value="{{ old('cancelled_at', isset($booking->cancelled_at) ? $booking->cancelled_at->format('Y-m-d\TH:i') : '') }}" />
            </div>

            {{-- Cancellation Reason and Cancelled By --}}
            <x-textarea name="cancellation_reason" label="Cancellation Reason"
                placeholder="Reason for cancellation">{{ old('cancellation_reason', $booking->cancellation_reason ?? '') }}</x-textarea>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-select name="cancelled_by" label="Cancelled By" :options="$users ?? []"
                    value="{{ old('cancelled_by', $booking->cancelled_by ?? '') }}" placeholder="Select User (optional)" />

                {{-- Performance Tracking --}}

                <x-input name="actual_distance_km" label="Actual Distance (km)" type="number" step="0.01" min="0"
                    value="{{ old('actual_distance_km', $booking->actual_distance_km ?? '') }}" />
                <x-input name="fuel_consumption_liters" label="Fuel Consumption (liters)" type="number" step="0.01"
                    min="0" value="{{ old('fuel_consumption_liters', $booking->fuel_consumption_liters ?? '') }}" />
                <x-input name="delivery_time_hours" label="Delivery Time (hours)" type="number" min="0"
                    value="{{ old('delivery_time_hours', $booking->delivery_time_hours ?? '') }}" />
                <x-input name="rating" label="Rating (1-5)" type="number" step="0.01" min="1" max="5"
                    value="{{ old('rating', $booking->rating ?? '') }}" />
            </div>

            <x-textarea name="feedback" label="Feedback"
                placeholder="Customer feedback or comments">{{ old('feedback', $booking->feedback ?? '') }}</x-textarea>

            {{-- Submit Buttons --}}
            <div class="flex justify-end mt-6 gap-2">
                <a href="{{ route('admin.bookings.index') }}"
                    class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    {{ isset($booking) ? 'Update Booking' : 'Create Booking' }}
                </button>
            </div>
        </form>
    </div>
@endsection
