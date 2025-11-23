<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublicController extends Controller
{
    public function verifyMember(string $memberId)
    {
        $user = User::where('member_id', $memberId)
            ->whereIn('status', [1, 2])
            ->first();

        return view('public.verify', [
            'exists' => $user !== null,
            'memberId' => $memberId,
            'user' => $user // Only for internal use, not displayed
        ]);
    }

    public function verifyMemberApi(string $memberId): JsonResponse
    {
        $user = User::where('member_id', $memberId)
            ->whereIn('status', ['approved', 'kyc_verified'])
            ->first();

        return response()->json([
            'exists' => $user !== null,
            'member_id' => $memberId,
            'status' => $user ? 'valid' : 'invalid'
        ]);
    }
}