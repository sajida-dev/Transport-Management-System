<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransporterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:transporters,registration_number',
            'tax_id' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:transporters,email',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email',
            'operating_license_number' => 'nullable|string|max:255',
            'operating_license_expiry' => 'nullable|date|after:today',
            'insurance_policy_number' => 'nullable|string|max:255',
            'insurance_expiry' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ];
    }
}
