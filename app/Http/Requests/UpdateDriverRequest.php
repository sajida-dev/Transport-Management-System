<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $driverId = $this->route('driver')->id;
        $userId = $this->route('driver')->user_id;

        return [
            'transporter_id' => ['required', 'exists:transporters,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'string', 'max:20'],
            'license_number' => ['required', 'string', 'max:50', Rule::unique('drivers', 'license_number')->ignore($driverId)],
            'license_type' => ['required', 'in:light_vehicle,heavy_vehicle,commercial,specialized'],
            'license_expiry_date' => ['required', 'date', 'after:today'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:100'],
            'medical_certificate_number' => ['nullable', 'string', 'max:100'],
            'medical_certificate_expiry' => ['nullable', 'date'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            'vehicle_types' => ['required', 'array'],
            'vehicle_types.*' => ['string'],
            'notes' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'license_photo' => ['nullable', 'image', 'max:2048'],
            'medical_certificate_photo' => ['nullable', 'image', 'max:2048'],
            'kyc_documents' => ['nullable', 'array'],
            'kyc_documents.*' => ['file', 'max:2048'],
        ];
    }
}
