<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // request reset (by email)
    public function requestReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );
        // in production -> send email with token link. For testing, return token
        return response()->json(['message'=>'token created','token'=>$token]);
    }

    // reset
    public function reset(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'token'=>'required|string',
            'password'=>'required|confirmed|min:6'
        ]);

        $row = DB::table('password_reset_tokens')->where('email',$request->email)->first();
        if(!$row || $row->token !== $request->token) {
            return response()->json(['message'=>'Invalid token'],422);
        }

        // optional: check expiry
        $user = User::where('email',$request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        // delete token
        DB::table('password_reset_tokens')->where('email',$request->email)->delete();

        return response()->json(['message'=>'Password reset successful']);
    }
}
