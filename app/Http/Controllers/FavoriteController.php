<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FavoriteController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->user_id ?? auth()->id();
        return Favorite::where('userId', $userId)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'targetId'=>'required|string'
        ]);

        $userId = auth()->user()->user_id ?? auth()->id();

        $fav = Favorite::firstOrCreate([
            'userId'=>$userId,
            'targetId'=>$request->targetId
        ],[
            'id'=>Str::uuid(),
            'timestamp'=>now()
        ]);

        return response()->json($fav, 201);
    }

    public function destroy($targetId)
    {
        $userId = auth()->user()->user_id ?? auth()->id();
        Favorite::where('userId',$userId)->where('targetId',$targetId)->delete();
        return response()->json(['message'=>'Removed']);
    }
}
