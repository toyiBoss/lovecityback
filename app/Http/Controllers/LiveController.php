<?php

namespace App\Http\Controllers;

use App\Models\Live;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LiveController extends Controller
{
    public function index()
    {
        $lives = Live::orderBy('startTime','desc')->get();
        return response()->json($lives);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'startTime' => 'nullable|date',
        ]);

        $hostId = auth()->user()->user_id ?? auth()->id();

        $live = Live::create([
            'liveId' => (string) Str::uuid(),
            'hostId' => $hostId,
            'title' => $request->title,
            'status' => 'SCHEDULED',
            'streamUrl' => $request->streamUrl ?? null,
            'viewersCount' => 0,
            'startTime' => $request->startTime ? now() : null,
        ]);

        return response()->json($live, 201);
    }

    public function show($liveId)
    {
        $live = Live::findOrFail($liveId);
        return response()->json($live);
    }

    public function update(Request $request, $liveId)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'status' => 'nullable|in:LIVE,ENDED,SCHEDULED',
            'streamUrl' => 'nullable|url',
        ]);

        $live = Live::findOrFail($liveId);
        $hostId = auth()->user()->user_id ?? auth()->id();
        if ($live->hostId !== $hostId) return response()->json(['message'=>'Forbidden'], 403);

        $live->update($request->only(['title','status','streamUrl']));
        return response()->json($live);
    }

    public function destroy($liveId)
    {
        $live = Live::findOrFail($liveId);
        $hostId = auth()->user()->user_id ?? auth()->id();
        if ($live->hostId !== $hostId) return response()->json(['message'=>'Forbidden'], 403);

        $live->delete();
        return response()->json(['message'=>'Deleted']);
    }

    // Contrôles simples pour start / end / inc / dec viewers (utiles côté Cloud Functions)
    public function start($liveId)
    {
        $live = Live::findOrFail($liveId);
        $hostId = auth()->user()->user_id ?? auth()->id();
        if ($live->hostId !== $hostId) return response()->json(['message'=>'Forbidden'], 403);

        $live->status = 'LIVE';
        $live->startTime = now();
        $live->save();
        return response()->json($live);
    }

    public function end($liveId)
    {
        $live = Live::findOrFail($liveId);
        $hostId = auth()->user()->user_id ?? auth()->id();
        if ($live->hostId !== $hostId) return response()->json(['message'=>'Forbidden'], 403);

        $live->status = 'ENDED';
        $live->save();
        return response()->json($live);
    }

    public function incViewer($liveId)
    {
        $live = Live::findOrFail($liveId);
        $live->viewersCount = $live->viewersCount + 1;
        $live->save();
        return response()->json(['viewersCount' => $live->viewersCount]);
    }

    public function decViewer($liveId)
    {
        $live = Live::findOrFail($liveId);
        $live->viewersCount = max(0, $live->viewersCount - 1);
        $live->save();
        return response()->json(['viewersCount' => $live->viewersCount]);
    }
}
