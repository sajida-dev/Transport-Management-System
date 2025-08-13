<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransporterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $transporterId = optional($this->route('transporter'))->id;

        return [
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'registration_number' => ['required', 'string', Rule::unique('transporters')->ignore($transporterId)],
            'tax_id' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => ['required', 'email', Rule::unique('transporters')->ignore($transporterId)],
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email',
            'operating_license_number' => 'nullable|string|max:255',
            'operating_license_expiry' => 'nullable|date',
            'insurance_policy_number' => 'nullable|string|max:255',
            'insurance_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ];
    }
}
