<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    // Web Methods
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect('/');
    }

    // Unified Login Method
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        if ($user->status === 0) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account pending approval'], 403);
            }
            return back()->withErrors(['email' => 'Account pending approval'])->withInput();
        }

        if ($user->status === -1) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account has been rejected'], 403);
            }
            return back()->withErrors(['email' => 'Account has been rejected'])->withInput();
        }

        // Login the user
        auth()->login($user, $request->remember ?? false);

        if ($request->expectsJson()) {
            $token = $user->createToken('MembershipApp')->accessToken;
            return response()->json([
                'user' => $user,
                'access_token' => $token,
            ]);
        }

        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return redirect()->route('user.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
    }

    // Unified Register Method
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 0,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photoPath = $this->storeProfilePhoto($request->file('profile_photo'));
            $userData['profile_photo_path'] = $photoPath;
        }

        $user = User::create($userData);

        if ($request->expectsJson()) {
            $token = $user->createToken('MembershipApp')->accessToken;
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'message' => 'Registration successful. Waiting for admin approval.'
            ], 201);
        }

        // Auto-login for web users
        auth()->login($user);

        return redirect()->route('user.dashboard')->with('success', 'Registration successful! Your account is pending admin approval.');
    }

    private function storeProfilePhoto($file): string
    {
        $image = Image::make($file)->fit(200, 200)->encode('jpg', 85);
        $filename = 'profile_photos/' . Str::uuid() . '.jpg';
        
        Storage::disk('public')->put($filename, $image);
        
        return $filename;
    }
}