<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'timezone' => ['required', 'string', 'timezone'],
            'email_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
            'sms_api_key' => ['required_if:sms_notifications,true', 'string'],
            'sms_sender_id' => ['required_if:sms_notifications,true', 'string'],
            'firebase_api_key' => ['required_if:push_notifications,true', 'string'],
            'firebase_project_id' => ['required_if:push_notifications,true', 'string'],
            'firebase_config' => ['required_if:push_notifications,true', 'json'],
        ];
    }

    public function messages()
    {
        return [
            'site_name.required' => 'Please enter the site name.',
            'contact_email.required' => 'Please enter the contact email address.',
            'contact_email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter the support phone number.',
            'timezone.required' => 'Please select a timezone.',
            'timezone.timezone' => 'Please select a valid timezone.',
            'sms_api_key.required_if' => 'SMS API key is required when SMS notifications are enabled.',
            'sms_sender_id.required_if' => 'SMS Sender ID is required when SMS notifications are enabled.',
            'firebase_api_key.required_if' => 'Firebase API key is required when push notifications are enabled.',
            'firebase_project_id.required_if' => 'Firebase Project ID is required when push notifications are enabled.',
            'firebase_config.required_if' => 'Firebase configuration is required when push notifications are enabled.',
            'firebase_config.json' => 'Firebase configuration must be a valid JSON string.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'email_notifications' => $this->boolean('email_notifications'),
            'sms_notifications' => $this->boolean('sms_notifications'),
            'push_notifications' => $this->boolean('push_notifications'),
        ]);
    }
}