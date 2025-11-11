<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->user_id ?? auth()->id();
        $notes = Notification::where('userId',$userId)->orderBy('timestamp','desc')->get();
        return response()->json($notes);
    }

    public function markRead($notificationId)
    {
        $userId = auth()->user()->user_id ?? auth()->id();
        $n = Notification::where('notificationId',$notificationId)->where('userId',$userId)->firstOrFail();
        $n->isRead = true;
        $n->save();
        return response()->json($n);
    }

    // optional: create a notification (internal use)
    public function store(Request $request)
    {
        $request->validate([
            'userId'=>'required|uuid',
            'type'=>'required|string',
            'message'=>'required|string'
        ]);
        $n = Notification::create([
            'notificationId' => (string) Str::uuid(),
            'userId' => $request->userId,
            'type' => $request->type,
            'message' => $request->message,
            'relatedEntityId' => $request->relatedEntityId ?? null,
            'timestamp' => now(),
            'isRead' => false
        ]);
        return response()->json($n,201);
    }
}
