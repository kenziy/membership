<?php

namespace App\Http\Controllers;

use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class KycController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        $user = auth()->user();

        // Create and resize image
        $image = Image::read($request->file('image'))
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encodeByExtension('jpg', quality: 85);

        // Generate a unique file name
        $filename = 'kyc_docs/' . $user->id . '/' . Str::uuid() . '.jpg';

        // Store in public disk
        Storage::disk('public')->put($filename, (string) $image);

        // Save record in database
        $kycDocument = KycDocument::create([
            'user_id' => $user->id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'image_path' => $filename,
        ]);

        // Update user status if needed
        if ($user->status === 'approved') {
            $user->update(['status' => 'kyc_pending']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'KYC document uploaded successfully',
                'document' => $kycDocument,
            ], 201);
        }

        return back()->with('success', 'KYC document uploaded successfully');
    }

    public function myDocuments(Request $request): JsonResponse
    {
        $documents = $request->user()->kycDocuments()->latest()->get();

        return response()->json([
            'documents' => $documents,
        ]);
    }
}