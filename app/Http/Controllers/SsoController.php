<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SsoController extends Controller
{
    /**
     * Initiate SSO Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'return_url' => 'required|url',
            'client_id' => 'required|string',
        ]);

        // Store the SSO session data
        session([
            'sso_return_url' => $request->return_url,
            'sso_client_id' => $request->client_id,
            'sso_state' => Str::random(40),
        ]);

        // If user is already logged in, redirect directly
        if (auth()->check()) {
            return $this->generateSsoRedirect(auth()->user());
        }

        return view('sso.login', [
            'client_id' => $request->client_id,
            'return_url' => $request->return_url,
        ]);
    }

    /**
     * Process SSO Login
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check if SSO session exists
        if (!session('sso_return_url') || !session('sso_client_id')) {
            return response()->json(['error' => 'Invalid SSO session'], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if ($user->status === 0) {
            return response()->json(['error' => 'Account pending approval'], 403);
        }

        if ($user->status === -1) {
            return response()->json(['error' => 'Account has been rejected'], 403);
        }

        // Login the user
        auth()->login($user);

        return $this->generateSsoRedirect($user);
    }

    /**
     * Initiate SSO Registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'return_url' => 'required|url',
            'client_id' => 'required|string',
        ]);

        // Store the SSO session data
        session([
            'sso_return_url' => $request->return_url,
            'sso_client_id' => $request->client_id,
            'sso_state' => Str::random(40),
        ]);

        return view('sso.register', [
            'client_id' => $request->client_id,
            'return_url' => $request->return_url,
        ]);
    }

    /**
     * Process SSO Registration
     */
    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if SSO session exists
        if (!session('sso_return_url') || !session('sso_client_id')) {
            return response()->json(['error' => 'Invalid SSO session'], 400);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'pending',
            ]);

            DB::commit();

            // Auto-login the user after registration
            auth()->login($user);

            return $this->generateSsoRedirect($user);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate SSO Redirect URL with token
     */
    private function generateSsoRedirect(User $user)
    {
        $returnUrl = session('sso_return_url');
        $clientId = session('sso_client_id');
        $state = session('sso_state');

        // Generate SSO token
        $ssoToken = $user->createToken('MembershipApp')->accessToken;
        // Build redirect URL with parameters
        $redirectUrl = $this->buildRedirectUrl($returnUrl, [
            'token' => $ssoToken,
            'state' => $state,
            'user_id' => $user->id,
            'member_id' => $user->member_id,
            'status' => 'success',
        ]);

        // Clear SSO session
        session()->forget(['sso_return_url', 'sso_client_id', 'sso_state']);

        if (request()->expectsJson()) {
            return response()->json([
                'redirect_url' => $redirectUrl,
                'token' => $ssoToken,
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Generate SSO Token
     */
    private function generateSsoToken(User $user, string $clientId): string
    {
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'member_id' => $user->member_id,
            'status' => $user->status,
            'is_vip' => $user->is_vip,
            'client_id' => $clientId,
            'timestamp' => now()->timestamp,
            'expires' => now()->addHours(1)->timestamp,
        ];

        $encodedPayload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $encodedPayload, config('app.key'));

        return $encodedPayload . '.' . $signature;
    }

    /**
     * Build Redirect URL with parameters
     */
    private function buildRedirectUrl(string $baseUrl, array $params): string
    {
        $separator = parse_url($baseUrl, PHP_URL_QUERY) ? '&' : '?';
        return $baseUrl . $separator . http_build_query($params);
    }

    /**
     * Verify SSO Token (for client applications)
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $tokenData = $this->parseAndVerifyToken($request->token);

            if (!$tokenData) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Check if token is expired
            if (now()->timestamp > $tokenData['expires']) {
                return response()->json(['error' => 'Token expired'], 401);
            }

            // Get user data
            $user = User::find($tokenData['user_id']);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json([
                'valid' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'member_id' => $user->member_id,
                    'status' => $user->status,
                    'is_vip' => $user->is_vip,
                    'profile_photo_url' => $user->profile_photo_path ? Storage::url($user->profile_photo_path) : null,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Token verification failed'], 500);
        }
    }

    /**
     * Parse and verify SSO token
     */
    private function parseAndVerifyToken(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 2) {
            return null;
        }

        list($encodedPayload, $signature) = $parts;

        // Verify signature
        $expectedSignature = hash_hmac('sha256', $encodedPayload, config('app.key'));

        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        $payload = json_decode(base64_decode($encodedPayload), true);

        if (!is_array($payload)) {
            return null;
        }

        return $payload;
    }

    /**
     * Get SSO configuration for clients
     */
    public function configuration()
    {
        return response()->json([
            'sso_endpoints' => [
                'login' => route('sso.login'),
                'register' => route('sso.register'),
                'verify' => route('sso.verify'),
            ],
            'required_parameters' => [
                'return_url' => 'The URL to redirect after authentication',
                'client_id' => 'Your application client identifier',
            ],
        ]);
    }
}