<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\KycDocument;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

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
    }

    public function idCardSvG()
    {
        $user = auth()->user();
        $rawID =  '';

        $pdf = Pdf::loadView('id', [
            'user' => $user
        ]);
        return $pdf->stream();

    }

    // API Methods
    public function profileApi(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load(['wallet', 'kycDocuments']),
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        // Validate inputs
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|unique:users,username,' . $user->id,
            'email'         => 'required|email |unique:users,email,' . $user->id,
            'address'       => 'max:500',
            'phone_number'  => 'max:20',
        ]);

        // Update user
        $user->update($validated);
        return back()->with('success', 'Profile updated successfully.');
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


        $image = Image::read($request->file('profile_photo'))
            ->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encodeByExtension('webp', quality: 85);

        $filename = 'profile_photos/' . Str::uuid() . '.webp';
        
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