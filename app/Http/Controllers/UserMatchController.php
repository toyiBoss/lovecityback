<?php

namespace App\Http\Controllers;

use App\Models\UserMatch;
use Illuminate\Http\Request;

class UserMatchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,user_id'
        ]);

        $authUserId = auth()->id();
        $otherUserId = $request->userId;

        if ($authUserId === $otherUserId) {
            return response()->json(['message' => "Impossible de matcher avec toi-même"], 422);
        }

        $user1 = min($authUserId, $otherUserId);
        $user2 = max($authUserId, $otherUserId);

        $existingMatch = UserMatch::where('user1Id', $user1)
            ->where('user2Id', $user2)
            ->first();

        if ($existingMatch) {
            return response()->json(['message' => 'Match déjà existant'], 409);
        }

        $match = UserMatch::create([
            'user1Id' => $user1,
            'user2Id' => $user2,
            'timestamp' => now(),
            'lastMessageTimestamp' => now(),
            'status' => 'pending',
        ]);

        return response()->json($match, 201);
    }

    public function index()
    {
        $userId = auth()->id();

        return UserMatch::where('user1Id', $userId)
            ->orWhere('user2Id', $userId)
            ->get();
    }

    public function accept($matchId)
    {
        $match = UserMatch::findOrFail($matchId);

        if ($match->status !== 'pending') {
            return response()->json(['message' => 'Match déjà traité'], 409);
        }

        $match->update(['status' => 'accepted']);

        return response()->json(['message' => 'Match accepté']);
    }

    public function reject($matchId)
    {
        $match = UserMatch::findOrFail($matchId);

        if ($match->status !== 'pending') {
            return response()->json(['message' => 'Match déjà traité'], 409);
        }

        $match->update(['status' => 'rejected']);

        return response()->json(['message' => 'Match refusé']);
    }
}
