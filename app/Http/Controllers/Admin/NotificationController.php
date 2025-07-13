<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $notifications = NotificationUser::with('notification')
            ->when($status === 'unread', fn($q) => $q->whereNull('read_at'))
            ->when($status === 'read', fn($q) => $q->whereNotNull('read_at'))
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();
        return Inertia::render('Admin/Notification/Index', [
            'notifications' => $notifications
        ]);
    }

    public function ntfs_count()
    {
        $ntfs_count = NotificationUser::where('user_id', auth()->id())->whereNull('read_at')->count();
        return response()->json([
            'ntfs_count' => $ntfs_count,
        ]);
    }

    public function ntfs_read($id)
    {
        NotificationUser::find($id)->update([
            'read_at' => now()
        ]);
        return response()->json([
            'success' => 200,
        ]);
    }
}
