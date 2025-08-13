<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();
        /** @phpstan-ignore-next-line */
        $query = $user->notifications();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('data->type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->latest()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @phpstan-ignore-next-line */
        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @phpstan-ignore-next-line */
        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @phpstan-ignore-next-line */
        $notification = $user->notifications()->findOrFail($id);

        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Clear all notifications.
     */
    public function clearAll(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @phpstan-ignore-next-line */
        $user->notifications()->delete();

        return back()->with('success', 'All notifications cleared.');
    }

    /**
     * Get unread notification count for AJAX requests.
     */
    public function getUnreadCount(): \Illuminate\Http\JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @phpstan-ignore-next-line */
        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }
}
