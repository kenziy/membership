@extends('layouts.app')

@section('title', 'Register - Membership App')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">

    <div class="max-w-md w-full">

        <!-- Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Create Your Account
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Join our membership program
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <form class="space-y-6" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">

                    <!-- Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input 
                            id="first_name" name="first_name" type="text" required
                            placeholder="Enter your first name"
                            value="{{ old('first_name') }}"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input 
                            id="last_name" name="last_name" type="text" required
                            placeholder="Enter your last name"
                            value="{{ old('last_name') }}"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input 
                            id="address" name="address" type="text" required
                            placeholder="Enter your full address"
                            value="{{ old('address') }}"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input 
                            id="username" name="username" type="text" required
                            placeholder="Enter your username"
                            value="{{ old('username') }}"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input 
                            id="password" name="password" type="password" required
                            placeholder="••••••••"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input 
                            id="password_confirmation" name="password_confirmation" type="password" required
                            placeholder="••••••••"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input 
                            id="email" name="email" type="email" required
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input 
                            id="phone_number" name="phone_number" type="text" required
                            placeholder="Enter your phone number"
                            value="{{ old('phone_number') }}"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>


                    

                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-lg bg-aether hover:bg-orange-700 
                               text-white font-semibold shadow-md transition-all flex items-center justify-center">
                        Create Account
                    </button>
                </div>

                <!-- Already Have Account -->
                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" 
                       class="font-semibold aether hover:text-orange-500 transition">
                        Already have an account? Sign in
                    </a>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection