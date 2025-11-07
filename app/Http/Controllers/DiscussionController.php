<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiscussionController extends Controller
{
    /**
     * Crée ou retourne la discussion entre l'utilisateur authentifié et $otherUserId.
     * Enforce user1 < user2 lexicographique (unicité).
     */
    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,user_id'
        ]);

        $authUserId = auth()->user()->user_id ?? auth()->id();
        $otherUserId = $request->userId;

        if ($authUserId === $otherUserId) {
            return response()->json(['message' => 'Cannot create discussion with yourself'], 422);
        }

        $user1 = min($authUserId, $otherUserId);
        $user2 = max($authUserId, $otherUserId);

        $discussion = Discussion::where('user1Id', $user1)
            ->where('user2Id', $user2)
            ->first();

        if ($discussion) {
            return response()->json($discussion);
        }

        $discussion = Discussion::create([
            'discussionId' => (string) Str::uuid(),
            'user1Id' => $user1,
            'user2Id' => $user2,
            'status' => 'ACTIVE',
            'lastMessage' => null,
            'lastMessageTimestamp' => null,
            'unreadCount_U1' => 0,
            'unreadCount_U2' => 0,
        ]);

        return response()->json($discussion, 201);
    }

    /**
     * Lister les discussions de l'utilisateur authentifié
     */
    public function index()
    {
        $userId = auth()->user()->user_id ?? auth()->id();

        $discussions = Discussion::where('user1Id', $userId)
            ->orWhere('user2Id', $userId)
            ->orderBy('lastMessageTimestamp','desc')
            ->get();

        return response()->json($discussions);
    }

    public function show($discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);

        $userId = auth()->user()->user_id ?? auth()->id();
        if ($discussion->user1Id !== $userId && $discussion->user2Id !== $userId) {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        // charger messages relation si besoin (paginated)
        $discussion->load(['messages' => function($q){ $q->orderBy('timestamp','asc'); }]);

        return response()->json($discussion);
    }

    public function updateStatus(Request $request, $discussionId)
    {
        $request->validate(['status' => 'required|in:ACTIVE,BLOCKED,ARCHIVED']);

        $discussion = Discussion::findOrFail($discussionId);

        $userId = auth()->user()->user_id ?? auth()->id();
        if ($discussion->user1Id !== $userId && $discussion->user2Id !== $userId) {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        $discussion->status = $request->status;
        $discussion->save();

        return response()->json($discussion);
    }
}
