<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KycDocument;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Web Methods
    public function dashboard()
    {
        $user = auth()->user();
        
        return view('user.dashboard', [
            'user' => $user,
            'wallet' => $user->wallet,
            'transactions' => $user->transactions()->latest()->take(5)->get()
        ]);
    }

    public function profile()
    {
        return view('user.profile', [
            'user' => auth()->user()
        ]);
    }

    public function kyc()
    {
        if (auth()->user()->status < 1) {
            abort(404);
        }
        $documents = auth()->user()->kycDocuments()->latest()->get();
        
        return view('user.kyc', [
            'documents' => $documents
        ]);
    }

    public function wallet()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->latest()->get();
        
        return view('user.wallet', [
            'wallet' => $user->wallet,
            'transactions' => $transactions
        ]);
    }

    public function idCard()
    {
        $user = auth()->user();
    
        return view('user.id-card', [
            'user' => $user
        ]);
        // if ($user->isApproved() || $user->isKycVerified()) {
        // }
        // return redirect()->route('user.dashboard')->with('error', 'Your account is not yet approved.');
        
    }

    // API Methods
    public function profileApi(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load(['wallet', 'kycDocuments']),
        ]);
    }

    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        // Delete old profile photo if exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new profile photo
        $image = Image::make($request->file('profile_photo'))->fit(200, 200)->encode('jpg', 85);
        $filename = 'profile_photos/' . Str::uuid() . '.jpg';
        
        Storage::disk('public')->put($filename, $image);
        
        $user->update(['profile_photo_path' => $filename]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Profile photo updated successfully',
                'profile_photo_path' => $filename,
                'user' => $user->fresh(),
            ]);
        }

        return back()->with('success', 'Profile photo updated successfully');
    }
}