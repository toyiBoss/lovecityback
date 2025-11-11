<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'recipientContact'=>'required|string'
        ]);

        $sender = auth()->user()->user_id ?? auth()->id();

        $inv = Invitation::create([
            'invitationId'=>Str::uuid(),
            'senderId'=>$sender,
            'recipientContact'=>$request->recipientContact,
            'code'=>Str::random(8),
            'status'=>'SENT',
            'timestamp'=>now()
        ]);

        return response()->json($inv, 201);
    }

    public function accept($code)
    {
        $inv = Invitation::where('code',$code)->firstOrFail();
        $inv->status = 'ACCEPTED';
        $inv->save();
        return response()->json(['message'=>'Accepted']);
    }
}
