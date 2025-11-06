<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Verification - Membership App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            @if($user->isVIP())
            <!-- VIP Header -->
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-full flex items-center justify-center mb-4 shadow-lg border-4 border-amber-300">
                <i class="fas fa-crown text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">VIP Member Verification</h1>
            <p class="text-gray-600 mt-2">Premium membership status</p>
            @else
            <!-- Regular Header -->
            <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-id-card text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Member Verification</h1>
            <p class="text-gray-600 mt-2">Verify membership status</p>
            @endif
        </div>

        <!-- Verification Card -->
        <div class="@if($user->isVIP()) bg-gradient-to-br from-amber-50 to-yellow-50 border-2 border-amber-200 @else bg-white @endif rounded-2xl shadow-xl p-8 relative overflow-hidden">
            <!-- VIP Badge -->

            <!-- Member ID Display -->
            <div class="text-center mb-6 @if($isVip ?? false) mt-4 @endif">
                <p class="text-sm text-gray-500 uppercase tracking-wider">Member ID</p>
                <p class="text-lg font-mono font-bold @if($isVip ?? false) text-amber-900 bg-amber-100 @else text-gray-900 bg-gray-100 @endif py-2 px-4 rounded-lg">{{ $memberId }}</p>
            </div>

            <!-- Verification Result -->
            <div class="text-center">
                @if($exists)
                    @if($user->isVIP())
                    <!-- VIP Valid Member -->
                    <div class="mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-yellow-100 to-amber-200 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-amber-300 shadow-lg">
                            <i class="fas fa-crown text-amber-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-amber-800 mb-2">VIP Member Verified</h3>
                        <p class="text-amber-700 font-medium">This ID is verified and active.</p>
                    </div>

                    @else
                    <!-- Regular Valid Member -->
                    <div class="mb-6">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-green-800 mb-2">Valid Member</h3>
                        <p class="text-green-600">This ID is verified and active.</p>
                    </div>

                    @endif
                @else
                    <!-- Invalid Member -->
                    <div class="mb-6">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-red-800 mb-2">Invalid Member</h3>
                        <p class="text-red-600">This membership ID could not be verified.</p>
                    </div>

                    <!-- Possible Reasons -->
                    <div class="bg-red-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-red-700 text-left">
                            This could be because:
                        </p>
                        <ul class="text-sm text-red-600 text-left mt-2 space-y-1">
                            <li class="flex items-center">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                Invalid membership ID
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                Membership not yet approved
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                Membership may have expired
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} All rights reserved.
            </p>
            <p class="text-xs text-green-400 mt-1">
                Secure verification system
            </p>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.rounded-2xl');
            card.style.transform = 'translateY(10px)';
            card.style.opacity = '0';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, 100);
        });

        // Auto-close after 30 seconds if valid (optional)
        @if($exists)
        setTimeout(() => {
            if (confirm('Verification complete. Close this window?')) {
                window.close();
            }
        }, 30000);
        @endif
    </script>
</body>
</html>