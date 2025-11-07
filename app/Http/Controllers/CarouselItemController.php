<?php

namespace App\Http\Controllers;
use App\Models\HasFactory;
use App\Models\CarouselItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarouselItemController extends Controller
{
    public function index()
    {
        $items = CarouselItem::orderBy('order','asc')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'imageUrl' => 'nullable|url',
            'linkUrl' => 'nullable|url',
            'order' => 'nullable|integer|min:1',
            'isActive' => 'nullable|boolean',
            'targetCountryId' => 'nullable|string|size:2',
        ]);

        $item = CarouselItem::create([
            'itemId' => (string) Str::uuid(),
            'title' => $request->title,
            'imageUrl' => $request->imageUrl,
            'linkUrl' => $request->linkUrl,
            'order' => $request->input('order', 1),
            'isActive' => $request->input('isActive', true),
            'targetCountryId' => $request->targetCountryId,
        ]);

        return response()->json($item, 201);
    }

    public function show($itemId)
    {
        $item = CarouselItem::findOrFail($itemId);
        return response()->json($item);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'imageUrl' => 'nullable|url',
            'linkUrl' => 'nullable|url',
            'order' => 'nullable|integer|min:1',
            'isActive' => 'nullable|boolean',
            'targetCountryId' => 'nullable|string|size:2',
        ]);

        $item = CarouselItem::findOrFail($itemId);
        $item->update($request->only(['title','imageUrl','linkUrl','order','isActive','targetCountryId']));

        return response()->json($item);
    }

    public function destroy($itemId)
    {
        $item = CarouselItem::findOrFail($itemId);
        $item->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
