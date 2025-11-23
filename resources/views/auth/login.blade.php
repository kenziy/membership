@extends('layouts.app')

@section('title', 'Login - Membership App')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        
        <!-- Logo / Icon -->
        <div class="flex flex-col items-center">
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-sm text-gray-500 text-center">
                Secure Single Sign-On Portal
            </p>
        </div>

        <!-- Form -->
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input id="username" name="username" type="text" autocomplete="username" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-500 focus:ring-aether focus:border-aether sm:text-sm"
                           placeholder="Username" value="{{ old('username') }}">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-500 focus:ring-aether focus:border-aether sm:text-sm"
                           placeholder="Password">
                </div>
            </div>

            <!-- Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3 px-4 text-sm font-medium rounded-lg text-white bg-aether hover:opacity-90 focus:ring-2 focus:ring-offset-2 focus:ring-aether transition">
                    Sign in
                </button>
            </div>

            <!-- Register -->
            <div class="text-center pt-2">
                <a href="{{ route('register') }}" class="font-medium aether hover:opacity-90">
                    Don't have an account? Sign up
                </a>
            </div>
        </form>
    </div>
</div>


@endsection