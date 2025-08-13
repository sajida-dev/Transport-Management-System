@php
    $notifications = auth()->user()->unreadNotifications()->latest()->take(10)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<div x-data="{ open: false }" x-cloak class="relative">
    <button type="button" 
            class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 relative" 
            @click="open = !open"
            @click.away="open = false">
        <span class="sr-only">View notifications</span>
        <i class="fas fa-bell h-6 w-6"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                <span class="text-xs font-medium text-white">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            </span>
        @endif
    </button>
    
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-10 mt-2.5 w-80 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none max-h-96 overflow-y-auto">
        <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
            <h6 class="text-sm font-semibold text-gray-900">Notifications</h6>
            @if($unreadCount > 0)
                <a href="{{ route('admin.notifications.mark-all-read') }}" class="text-xs text-gray-500 hover:text-gray-700">Mark all read</a>
            @endif
        </div>
        
        @forelse($notifications as $notification)
            <a href="{{ $notification->data['action_url'] ?? '#' }}" 
               class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 {{ $notification->read_at ? '' : 'bg-blue-50' }}" 
               data-notification-id="{{ $notification->id }}">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        @switch($notification->data['type'] ?? '')
                            @case('driver_created')
                                <i class="fas fa-user-plus text-blue-500"></i>
                                @break
                            @case('kyc_document_submitted')
                                <i class="fas fa-file-alt text-yellow-500"></i>
                                @break
                            @case('invoice_overdue')
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                                @break
                            @default
                                <i class="fas fa-bell text-gray-500"></i>
                        @endswitch
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'Notification' }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $notification->data['message'] ?? '' }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!$notification->read_at)
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">New</span>
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="px-4 py-8 text-center">
                <i class="fas fa-bell-slash text-3xl text-gray-300 mb-2"></i>
                <div class="text-sm text-gray-500">No notifications</div>
            </div>
        @endforelse
        
        @if($notifications->count() > 0)
            <div class="border-t border-gray-200 mt-2 pt-2">
                <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-2 text-sm text-center text-blue-600 hover:text-blue-800 hover:bg-blue-50">
                    View all notifications
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark notification as read when clicked
    document.querySelectorAll('[data-notification-id]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            const notificationId = this.dataset.notificationId;
            if (notificationId) {
                fetch(`/admin/notifications/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });
            }
        });
    });
});
</script> 