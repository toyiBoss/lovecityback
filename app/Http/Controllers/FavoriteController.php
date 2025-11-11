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
        $items = Favorite::where('userId', $userId)->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'targetId' => 'required|uuid'
        ]);
        $userId = auth()->user()->user_id ?? auth()->id();
        // unique check
        $exists = Favorite::where('userId',$userId)->where('targetId',$request->targetId)->first();
        if($exists) return response()->json(['message'=>'Already favorited'],409);

        $fav = Favorite::create([
            'userId' => $userId,
            'targetId' => $request->targetId,
            'timestamp' => now()
        ]);
        return response()->json($fav,201);
    }

    public function destroy($targetId)
    {
        $userId = auth()->user()->user_id ?? auth()->id();
        $fav = Favorite::where('userId',$userId)->where('targetId',$targetId)->firstOrFail();
        $fav->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
