<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    public function balance(Request $request)
    {
        $wallet = $request->user()->wallet;

        if ($request->expectsJson()) {
            return response()->json([
                'balance' => $wallet ? $wallet->balance : 0,
            ]);
        }

        // For web, redirect to wallet page
        return redirect()->route('user.wallet');
    }

    public function transactions(Request $request)
    {
        $transactions = $request->user()->transactions()->latest()->get();

        if ($request->expectsJson()) {
            return response()->json([
                'transactions' => $transactions,
            ]);
        }

        // For web, redirect to wallet page
        return redirect()->route('user.wallet');
    }
}