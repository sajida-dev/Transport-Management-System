@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Notifications</h1>
            <p class="mt-2 text-sm text-gray-700">Manage and view all your notifications.</p>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
        @if($notifications->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <li class="px-6 py-4 hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bell text-blue-500 text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                            @if(!$notification->read_at)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    New
                                </span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                <p class="text-gray-500">You don't have any notifications at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection 