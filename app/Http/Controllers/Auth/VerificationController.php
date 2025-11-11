<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    // send code to email or phone (here we just store code and return it for testing)
    public function sendCode(Request $request)
    {
        $request->validate([
            'contact' => 'required|string', // email or phone
            'channel' => 'required|in:email,phone'
        ]);

        $code = mt_rand(100000,999999); // 6 digits
        $vc = VerificationCode::create([
            'id' => (string) Str::uuid(),
            'userId' => auth()->user()->user_id ?? auth()->id(),
            'contact' => $request->contact,
            'channel' => $request->channel,
            'code' => (string)$code,
            'expires_at' => now()->addMinutes(10),
            'used' => false
        ]);

        // In production -> send email or SMS. Here return code for testing.
        return response()->json(['message'=>'Code created','code'=>$code],201);
    }

    public function verify(Request $request)
    {
        $request->validate(['contact'=>'required','code'=>'required']);
        $vc = VerificationCode::where('contact',$request->contact)
            ->where('code',$request->code)
            ->where('used',false)
            ->where('expires_at','>',now())
            ->first();

        if(!$vc) return response()->json(['message'=>'Invalid or expired code'],422);

        // mark used
        $vc->used = true; $vc->save();

        $user = auth()->user() ?? User::where('user_id',$vc->userId)->first();
        if($vc->channel === 'email') {
            $user->email_verified_at = now();
        } else {
            $user->phone_verified_at = now();
        }
        $user->save();

        return response()->json(['message'=>'Verified']);
    }
}
