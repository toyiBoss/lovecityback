<?php

namespace App\Http\Controllers;

use App\Models\NotificationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->user_id ?? auth()->id();
        return NotificationItem::where('userId',$userId)->orderBy('timestamp','desc')->get();
    }

    public function markRead($id)
    {
        $notif = NotificationItem::findOrFail($id);
        $notif->isRead = true;
        $notif->save();
        return response()->json(['message'=>'Marked read']);
    }

    // Utilisé par le système interne pour créer
    public function store(Request $request)
    {
        $request->validate([
            'userId'=>'required',
            'type'=>'required',
            'message'=>'nullable|string',
            'relatedEntityId'=>'nullable|string',
        ]);

        $notif = NotificationItem::create([
            'notificationId'=>Str::uuid(),
            'userId'=>$request->userId,
            'type'=>$request->type,
            'message'=>$request->message,
            'relatedEntityId'=>$request->relatedEntityId,
            'timestamp'=>now(),
            'isRead'=>false
        ]);

        return response()->json($notif, 201);
    }
}
