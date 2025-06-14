<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function show(DatabaseNotification $notification)
    {
        if (Auth::id() !== $notification->notifiable_id) {
            abort(403, 'Unauthorized action.');
        }
        $notification->markAsRead(); 
        return view('notifications.show', compact('notification'));
    }

    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        if (Auth::id() !== $notification->notifiable_id) {
            abort(403, 'Unauthorized action.');
        }
        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
