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

        if (is_null($user)) {
            abort(404);
        }

        return view('public.verify', [
            'exists'    => $user !== null,
            'memberId'  => $memberId,
            'address'   => $user->getAddress(),
            'user'      => $user
        ]);
    }

    public function verifyMemberApi(string $memberId): JsonResponse
    {
        $user = User::where('member_id', $memberId)
            ->whereIn('status', [1, 2])
            ->first();

        return response()->json([
            'exists'    => $user !== null,
            'member_id' => $memberId,
            'address'   => $user->getAddress(),
            'status'    => $user ? 'valid' : 'invalid'
        ]);
    }
}