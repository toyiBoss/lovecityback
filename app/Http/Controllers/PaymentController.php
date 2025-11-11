<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type'=>'required|in:SUBSCRIPTION,COINS_PURCHASE',
            'amount'=>'required|numeric',
            'currency'=>'required|string',
            'productId'=>'required|string',
            'receiptData'=>'nullable|string'
        ]);

        $userId = auth()->user()->user_id ?? auth()->id();

        $payment = Payment::create([
            'paymentId'=> Str::uuid(),
            'userId'=> $userId,
            'type'=> $request->type,
            'amount'=> $request->amount,
            'currency'=> $request->currency,
            'productId'=> $request->productId,
            'receiptData'=> $request->receiptData,
            'timestamp'=> now(),
            'status'=> 'SUCCESS' // plus tard on met PENDING + vérification Store
        ]);

        return response()->json($payment, 201);
    }

    public function indexByUser($userId)
    {
        return response()->json(Payment::where('userId',$userId)->orderBy('timestamp','desc')->get());
    }
}
