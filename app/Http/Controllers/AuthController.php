<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|unique:users,phone_number',
            'password' => 'nullable|string|min:6|confirmed',
            'auth_provider' => 'nullable|string',
            'first_name' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'birth_date' => 'nullable|date',
            'country_id' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:100',
            'photo_url' => 'nullable|url',
        ]);

        $user = User::create([
            'user_id' => Str::uuid(),
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'auth_provider' => $validatedData['auth_provider'],
            'first_name' => $validatedData['first_name'],
            'bio' => $validatedData['bio'],
            'gender' => $validatedData['gender'],
            'birth_date' => $validatedData['birth_date'],
            'country_id' => $validatedData['country_id'],
            'city' => $validatedData['city'],
            'photo_url' => $validatedData['photo_url'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            // 'phone_number' => 'required_without:email|string',
            'email' => 'required_without:phone_number|string',
            'password' => 'required|string',
        ]);

        // récupérer l'utilisateur par email ou phone_number
        $user = User::where('email', $request->email)
            ->orWhere('phone_number', $request->phone_number)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['token' => $token], 200);
    }



    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
