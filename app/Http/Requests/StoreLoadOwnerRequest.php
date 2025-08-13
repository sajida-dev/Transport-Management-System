<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoadOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Or add authorization logic
    }

    public function rules(): array
    {
        return [
            // User
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'nrc' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'profile_image_url' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:50',
            'password' => 'required|confirmed|min:6',

            // Load Owner
            'company_name' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'business_license_number' => 'nullable|string|max:100',
            'business_license_expiry' => 'nullable|date',
            'status' => 'required|in:active,inactive,suspended,pending_verification',
            'notes' => 'nullable|string',
            'logo' => 'nullable|file|image|max:2048',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
