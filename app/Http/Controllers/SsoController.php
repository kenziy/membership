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
            'username' => 'required',
            'password' => 'required|string',
        ]);

        // Check if SSO session exists
        if (!session('sso_return_url') || !session('sso_client_id')) {
            return response()->json(['error' => 'Invalid SSO session'], 400);
        }

        $user = User::where('username', $request->username)->first();

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

        if (auth()->check()) {
            return $this->generateSsoRedirect(auth()->user());
        }

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
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'phone_number'  => 'max:20',

            'location_region_code'      => 'max:225',
            'location_province_code'    => 'max:225',
            'location_city_code'        => 'max:225',
            'location_barangay_code'    => 'max:225',
            'location_barangay_street'  => 'max:225',
        ]);

        // Check if SSO session exists
        if (!session('sso_return_url') || !session('sso_client_id')) {
            return response()->json(['error' => 'Invalid SSO session'], 400);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'username'      => $request->username,
                'email'         => $request->email,
                'phone_number'  => $request->phone_number,
                'password'      => Hash::make($request->password),
                'status'        => env('DEFAULT_MEMBER_STATUS'),

                'location_region_code'       => $request->location_region_code,
                'location_province_code'     => $request->location_province_code,
                'location_city_code'         => $request->location_city_code,
                'location_barangay_code'     => $request->location_barangay_code,
                'location_barangay_street'   => $request->location_barangay_street,
            ]);

            DB::commit();

            if (env('DEFAULT_MEMBER_STATUS')) {
                $user->approveMember();
            }

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
     * Build Redirect URL with parameters
     */
    private function buildRedirectUrl(string $baseUrl, array $params): string
    {
        $separator = parse_url($baseUrl, PHP_URL_QUERY) ? '&' : '?';
        return $baseUrl . $separator . http_build_query($params);
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