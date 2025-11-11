<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        $userId = auth()->user()->user_id ?? auth()->id();
        return Setting::firstOrCreate(['userId'=>$userId]);
    }

    public function update(Request $request)
    {
        $userId = auth()->user()->user_id ?? auth()->id();

        $setting = Setting::firstOrCreate(['userId'=>$userId]);
        $setting->update($request->only([
            'searchGender','minAge','maxAge','maxDistanceKm','isVisible','notificationPrefs'
        ]));

        return response()->json($setting);
    }
}
