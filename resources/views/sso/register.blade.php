@extends('layouts.app')

@section('title', 'Login - Membership App')

@section('content')
    
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <div class="max-w-md w-full">

            <!-- Header -->
            <div class="text-center mb-10">

                <h1 class="text-3xl font-extrabold text-gray-900">Registration</h1>
                <p class="text-gray-600 mt-2">
                    Create an account for 
                    <span class="font-semibold text-orange-600">{{ $client_id }}</span>
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <form id="registerForm" class="space-y-6">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input 
                            type="text" id="name" name="name" required
                            placeholder="Enter your full name"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                            Address
                        </label>
                        <input 
                            type="text" id="address" name="address" required
                            placeholder="Enter your full address"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Username
                        </label>
                        <input 
                            type="text" id="username" name="username" required
                            placeholder="Enter your username"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input 
                            type="password" id="password" name="password" required
                            placeholder="Create a password"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password
                        </label>
                        <input 
                            type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Confirm your password"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input 
                            type="email" id="email" name="email" required
                            placeholder="Enter your email"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number
                        </label>
                        <input 
                            type="text" id="phone_number" name="phone_number" required
                            placeholder="Enter your phone number"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Info Box -->
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle aether mt-0.5"></i>
                            <p class="ml-3 text-sm text-orange-700">
                                Your account will be created and you'll be automatically signed in.  
                                Account approval is required before full access is granted.
                            </p>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit" 
                            class="w-full py-3 px-4 rounded-lg bg-aether hover:bg-orange-700 
                                   text-white font-semibold shadow-md transition-all flex items-center justify-center">
                            Create Account
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-1">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('sso.login', ['return_url' => $return_url, 'client_id' => $client_id]) }}"
                               class="font-semibold text-orange-600 hover:aether transition">
                                Sign in
                            </a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            submitButton.disabled = true;

            try {
                const response = await fetch('{{ route("sso.process-register") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = '{{ $return_url }}?token=' + data.token;
                    }
                } else {
                    throw new Error(data.error || 'Registration failed');
                }
            } catch (error) {
                alert('Error: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });

        // Password confirmation validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');

        function validatePassword() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    </script>
@endsection