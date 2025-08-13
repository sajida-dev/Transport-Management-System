<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transporter_id' => 'required|exists:transporters,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:255|unique:drivers,license_number,',
            'license_type' => 'required|in:light_vehicle,heavy_vehicle,commercial,specialized',
            'license_expiry_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:255',
            'medical_certificate_number' => 'nullable|string|max:255',
            'medical_certificate_expiry' => 'nullable|date',
            'experience_years' => 'required|integer|min:0',
            'vehicle_types' => 'required|array',
            'vehicle_types.*' => 'string',
            'notes' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'license_photo' => 'nullable|image|max:2048',
            'medical_certificate_photo' => 'nullable|image|max:2048',
            'kyc_documents' => 'nullable|array',
            'kyc_documents.*' => 'file|max:2048',


        ];
    }
}
