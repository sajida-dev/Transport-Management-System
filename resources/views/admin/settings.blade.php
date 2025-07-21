@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="space-y-8">
    <!-- Page header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">System Settings</h1>
        <p class="mt-2 text-sm text-gray-700">Manage your application settings and configurations.</p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- General Settings -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">General Settings</h3>
                <form class="mt-6 space-y-6" action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                        <div class="mt-1">
                            <input type="text" name="site_name" id="site_name" value="{{ $settings->site_name ?? 'LoadMasta' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                        <div class="mt-1">
                            <input type="email" name="contact_email" id="contact_email" value="{{ $settings->contact_email ?? 'contact@loadmasta.com' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Support Phone</label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone" value="{{ $settings->phone ?? '+260 970000000' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700">Default Timezone</label>
                        <select id="timezone" name="timezone" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                            <option value="Africa/Lusaka" {{ ($settings->timezone ?? 'Africa/Lusaka') == 'Africa/Lusaka' ? 'selected' : '' }}>Africa/Lusaka</option>
                            <option value="UTC" {{ ($settings->timezone ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Notification Settings</h3>
                <form class="mt-6 space-y-6" action="{{ route('admin.settings.notifications') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="email_notifications" name="email_notifications" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ ($settings->email_notifications ?? true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="email_notifications" class="font-medium text-gray-700">Email Notifications</label>
                                <p class="text-gray-500">Receive email notifications for new bookings and important updates.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="sms_notifications" name="sms_notifications" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ ($settings->sms_notifications ?? true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="sms_notifications" class="font-medium text-gray-700">SMS Notifications</label>
                                <p class="text-gray-500">Receive SMS alerts for critical system events.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="push_notifications" name="push_notifications" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ ($settings->push_notifications ?? true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="push_notifications" class="font-medium text-gray-700">Push Notifications</label>
                                <p class="text-gray-500">Enable browser push notifications for real-time updates.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SMS Gateway Settings -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">SMS Gateway Configuration</h3>
                <form class="mt-6 space-y-6" action="{{ route('admin.settings.sms') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="sms_api_key" class="block text-sm font-medium text-gray-700">API Key</label>
                        <div class="mt-1">
                            <input type="password" name="sms_api_key" id="sms_api_key" value="{{ $settings->sms_api_key ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="sms_sender_id" class="block text-sm font-medium text-gray-700">Sender ID</label>
                        <div class="mt-1">
                            <input type="text" name="sms_sender_id" id="sms_sender_id" value="{{ $settings->sms_sender_id ?? 'LoadMasta' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="mr-3 inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Test Connection
                        </button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Firebase Configuration -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Firebase Configuration</h3>
                <form class="mt-6 space-y-6" action="{{ route('admin.settings.firebase') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="firebase_api_key" class="block text-sm font-medium text-gray-700">Firebase API Key</label>
                        <div class="mt-1">
                            <input type="password" name="firebase_api_key" id="firebase_api_key" value="{{ $settings->firebase_api_key ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="firebase_project_id" class="block text-sm font-medium text-gray-700">Project ID</label>
                        <div class="mt-1">
                            <input type="text" name="firebase_project_id" id="firebase_project_id" value="{{ $settings->firebase_project_id ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="firebase_config" class="block text-sm font-medium text-gray-700">Firebase Configuration File</label>
                        <div class="mt-1">
                            <textarea id="firebase_config" name="firebase_config" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $settings->firebase_config ?? '' }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Paste your Firebase configuration JSON here.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="mr-3 inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Verify Connection
                        </button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">System Information</h3>
            <dl class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ phpversion() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ app()->version() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Environment</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ app()->environment() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Server</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Database</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ config('database.default') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Cache Driver</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ config('cache.default') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection