<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        // Adjust authorization logic if needed
        return true;
    }

    public function rules()
    {
        $bookingId = $this->route('booking')?->id;

        return [

            'load_id' => ['required', 'exists:loads,id'],
            'transporter_id' => ['required', 'exists:transporters,id'],
            'truck_id' => ['nullable', 'exists:trucks,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],

            'quoted_amount' => ['required', 'numeric', 'min:0'],
            'accepted_amount' => ['nullable', 'numeric', 'min:0'],

            'currency' => ['required', 'string', 'size:3'],

            'notes' => ['nullable', 'string'],
            'special_instructions' => ['nullable', 'string'],

            'status' => ['required', Rule::in(['pending', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['pending', 'partial', 'paid', 'overdue'])],

            'quoted_at' => ['nullable', 'date'],
            'accepted_at' => ['nullable', 'date'],
            'rejected_at' => ['nullable', 'date'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'cancelled_at' => ['nullable', 'date'],

            'cancellation_reason' => ['nullable', 'string'],
            'cancelled_by' => ['nullable', 'exists:users,id'],

            'actual_distance_km' => ['nullable', 'numeric', 'min:0'],
            'fuel_consumption_liters' => ['nullable', 'numeric', 'min:0'],
            'delivery_time_hours' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string'],
        ];
    }
}
