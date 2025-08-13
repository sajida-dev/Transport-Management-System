<?php
// app/Http/Requests/UpdateLoadRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $loadId = $this->route('load'); // or 'id' depending on your route definition

        return [
            'load_owner_id' => 'required|exists:users,id',
            'load_number' => 'required|string|unique:loads,load_number,' . $loadId,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'load_type' => 'required|in:general,refrigerated,hazardous,oversized,fragile,liquid,other',
            'weight_tonnes' => 'required|numeric|min:0',
            'length_meters' => 'nullable|numeric|min:0',
            'width_meters' => 'nullable|numeric|min:0',
            'height_meters' => 'nullable|numeric|min:0',

            // Pickup
            'pickup_location' => 'required|string|max:255',
            'pickup_address' => 'required|string',
            'pickup_city' => 'required|string',
            'pickup_state' => 'required|string',
            'pickup_postal_code' => 'required|string',
            'pickup_country' => 'required|string',
            'pickup_latitude' => 'nullable|numeric',
            'pickup_longitude' => 'nullable|numeric',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'pickup_contact_name' => 'nullable|string',
            'pickup_contact_phone' => 'nullable|string',
            'pickup_instructions' => 'nullable|string',

            // Delivery
            'delivery_location' => 'required|string|max:255',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_state' => 'required|string',
            'delivery_postal_code' => 'required|string',
            'delivery_country' => 'required|string',
            'delivery_latitude' => 'nullable|numeric',
            'delivery_longitude' => 'nullable|numeric',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'delivery_contact_name' => 'nullable|string',
            'delivery_contact_phone' => 'nullable|string',
            'delivery_instructions' => 'nullable|string',

            // Pricing
            'rate_per_km' => 'nullable|numeric',
            'total_distance_km' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'currency' => 'required|string|size:3',

            // Requirements
            'required_vehicle_types' => 'nullable|array',
            'special_requirements' => 'nullable|array',
            'requires_refrigeration' => 'boolean',
            'requires_special_equipment' => 'boolean',
            'is_hazardous' => 'boolean',

            // Optional
            'status' => 'in:pending,assigned,in_transit,delivered,cancelled,completed',
            'priority' => 'in:low,medium,high,urgent',
            'notes' => 'nullable|string',
            'documents' => 'nullable|array',
        ];
    }
}
