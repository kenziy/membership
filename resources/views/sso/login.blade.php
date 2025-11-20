@extends('layouts.app')

@section('title', 'Login - Membership App')

@section('content')
    
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-gray-900">Sign in</h1>
                <p class="text-gray-600 mt-1">
                    Sign in to continue to <span class="font-semibold aether">{{ $client_id }}</span>
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <form id="loginForm" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input 
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="Enter your email"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input 
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Enter your password"
                            class="w-full py-3 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        >
                    </div>

                    <!-- Button -->
                    <div>
                        <button 
                            type="submit"
                            class="w-full py-3 px-4 rounded-lg bg-aether hover:bg-orange-700 
                                   text-white font-semibold shadow-md transition-all"
                        >
                            Sign In
                        </button>
                    </div>

                    <!-- Signup Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('sso.register', ['return_url' => $return_url, 'client_id' => $client_id]) }}" 
                               class="font-semibold aether hover:text-orange-500 transition">
                                Sign up
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
            submitButton.disabled = true;

            try {
                const response = await fetch('{{ route("sso.process-login") }}', {
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
                    throw new Error(data.error || 'Login failed');
                }
            } catch (error) {
                alert('Error: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
    </script>
@endsection