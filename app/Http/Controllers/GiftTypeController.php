<?php

namespace App\Http\Controllers;

use App\Models\GiftType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GiftTypeController extends Controller
{
    public function index()
    {
        $gifts = GiftType::where('isActive', true)->get();
        return response()->json($gifts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'costInCoins' => 'required|integer|min:0',
            'imageUrl' => 'nullable|url',
            'isActive' => 'nullable|boolean',
        ]);

        $gift = GiftType::create([
            'giftId' => (string) Str::uuid(),
            'name' => $request->name,
            'costInCoins' => $request->costInCoins,
            'imageUrl' => $request->imageUrl,
            'isActive' => $request->input('isActive', true),
        ]);

        return response()->json($gift, 201);
    }

    public function show($giftId)
    {
        $gift = GiftType::findOrFail($giftId);
        return response()->json($gift);
    }

    public function update(Request $request, $giftId)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'costInCoins' => 'nullable|integer|min:0',
            'imageUrl' => 'nullable|url',
            'isActive' => 'nullable|boolean',
        ]);

        $gift = GiftType::findOrFail($giftId);
        $gift->update($request->only(['name','costInCoins','imageUrl','isActive']));
        return response()->json($gift);
    }

    public function destroy($giftId)
    {
        $gift = GiftType::findOrFail($giftId);
        $gift->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
