<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    // Récupérer le profil de l'utilisateur connecté
    public function show()
    {
        $profile = UserProfile::where('user_id', auth()->id())->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json($profile);
    }

    // Créer ou mettre à jour le profil
    public function update(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $profile = UserProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'bio' => $request->bio,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'country' => $request->country,
                'city' => $request->city,
            ]
        );

        return response()->json($profile, 201);
    }
}
