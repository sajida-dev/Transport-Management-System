@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="w-full px-2 py-8">
    <div class="w-full mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Activity Logs</h1>
            <p class="mt-2 text-sm text-gray-600">View all activity logs for {{ $user->full_name }}</p>
        </div>
        <a href="{{ route('admin.backoffice-users.show', $user->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Back to User</a>
    </div>
    <div class="bg-white shadow rounded-lg p-6 w-full">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Details</h3>
        @if($activityLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activityLogs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->log_name ?: 'General' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $log->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No activity logs found for this user.</p>
        @endif
    </div>
</div>
@endsection