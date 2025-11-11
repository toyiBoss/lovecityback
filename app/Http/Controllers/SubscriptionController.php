<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return response()->json(Subscription::all());
    }

    public function show($id)
    {
        return response()->json(Subscription::findOrFail($id));
    }

    // Optionnel (réservé admin)
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'durationDays'=>'required|integer',
            'price'=>'required|numeric',
            'features'=>'nullable|array',
            'storeProductId'=>'nullable|string'
        ]);

        $sub = Subscription::create($request->only([
            'name', 'durationDays','price','features','storeProductId'
        ]));

        return response()->json($sub, 201);
    }
}
