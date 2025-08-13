<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTruckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $truckId = $this->route('truck')->id ?? null;

        return [
            'transporter_id' => ['required', 'exists:transporters,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'registration_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('trucks')->ignore($truckId),
            ],
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'digits:4', 'min:1900', 'max:' . now()->year],
            'color' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:flatbed,box_truck,refrigerated,tanker,dump_truck,lowboy,other'],
            'capacity_tonnes' => ['required', 'numeric', 'min:0'],
            'length_meters' => ['required', 'numeric', 'min:0'],
            'width_meters' => ['required', 'numeric', 'min:0'],
            'height_meters' => ['required', 'numeric', 'min:0'],
            'engine_number' => ['nullable', 'string', 'max:255'],
            'chassis_number' => ['nullable', 'string', 'max:255'],
            'insurance_policy_number' => ['nullable', 'string', 'max:255'],
            'insurance_expiry' => ['nullable', 'date'],
            'fitness_certificate_number' => ['nullable', 'string', 'max:255'],
            'fitness_expiry' => ['nullable', 'date'],
            'permit_number' => ['nullable', 'string', 'max:255'],
            'permit_expiry' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'documents.*' => ['nullable', 'file', 'max:2048'],
        ];
    }
}
