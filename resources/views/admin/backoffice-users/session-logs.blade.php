{{-- @extends('admin.layouts.app')

@section('title', 'Session Logs')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Session Logs</h1>
                <p class="mt-2 text-sm text-gray-600">View all login sessions for {{ $user->full_name }}</p>
            </div>
            <a href="{{ route('admin.backoffice-users.show', $user->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Back to User</a>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Session Details</h3>
            @if ($sessionLogs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($sessionLogs as $session)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $session->ip_address ?: 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($session->user_agent, 50) ?: 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->format('M d, Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No session logs found for this user.</p>
            @endif
        </div>
    </div>
</div>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Session Logs')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Session Logs</h1>
                    <p class="mt-2 text-sm text-gray-600">Login sessions for {{ $user->full_name }}</p>
                </div>
                <a href="{{ route('admin.backoffice-users.show', $user->id) }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Back to User
                </a>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Active Sessions</h3>

                @if ($sessionLogs->count())
                    <ul class="divide-y divide-gray-200">
                        @foreach ($sessionLogs as $session)
                            <li class="py-4 flex items-center justify-between">
                                <div>
                                    <div class="text-sm text-gray-700">
                                        {{ $session->platform ?? 'Unknown OS' }} -
                                        {{ $session->browser ?? 'Unknown Browser' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        IP: {{ $session->ip_address ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        Last active: {{ $session->last_active }}
                                    </div>
                                </div>
                                <div>
                                    @if ($session->is_current_device)
                                        <span class="text-green-600 text-xs font-semibold">This device</span>
                                    @else
                                        <span class="text-gray-400 text-xs">Other device</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No session logs found for this user.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
