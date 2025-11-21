<div class="card-container flex flex-col lg:flex-row gap-8 items-start justify-center scrollOnMobile">

    <div id="card-holder">
        <div class="flex-1 max-w-sm w-full rounded-2xl md:rounded-3xl overflow-hidden" id="card">
            <div class="id-card" id="idCard">

                <!-- 1. ABSTRACT BACKGROUND SHAPES (Gold Diagonals) -->
                <div class="diagonal-shape shape-1"></div>
                <div class="diagonal-shape shape-2"></div>
                <div class="diagonal-shape shape-3"></div>
                <div class="diagonal-shape shape-4"></div>
                <div class="diagonal-shape shape-5"></div>

                <!-- 2. CARD CONTENT -->
                <div class="relative z-10 w-full h-full p-6 flex flex-col justify-top">
                    <div class="flex items-center space-x-2">
                        <img src="{{ url('/images/logo.svg') }}" />
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <div class="rounded-full profile-ring flex-shrink-0">
                            <div class="rounded-full bg-gray-200 m-[-4px] p-1.5 flex items-center justify-center">
                                @if($user->profile_photo_path)
                                    <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                         alt="Profile Photo"
                                         class="rounded-full" style="width: 80px; height: 80px;">
                                @else
                                    <img src="https://placehold.co/80x80?text={{ substr($user->name, 0, 1) }}" class="rounded-full" style="width: 80px; height: 80px;">
                                @endif
                            </div>
                        </div>

                        <!-- Name and ID -->
                        <div class="flex-grow ml-6">
                            <div class="text-2xl md:text-3xl font-extrabold leading-none mb-1 tracking-tight uppercase">
                                {{ $user->name }}
                            </div>
                            <div class="text-sm opacity-70 font-medium">
                                {{ $user->member_id }}
                            </div>
                        </div>
                    </div>
                    @if($user->isVIP())
                        <div class="flex items-center justify-center">
                            <div class="bg-gradient-to-r from-yellow-400 to-amber-500 text-amber-900 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg border border-amber-300">
                                <i class="fas fa-crown mr-1"></i>VIP Member
                            </div>
                        </div>
                    @endif
                    <!-- QR Code Placeholder -->
                    <div class="z-10">
                        @if($user->qr_code_path)
                            <img src="{{ Storage::url($user->qr_code_path) }}" 
                                 alt="QR Code" 
                                 class="mx-auto w-20 h-20 shadow-xl rounded-lg border-4 border-white z-10 absolute bottom-5 right-5">
                        @else
                            <img src="https://placehold.co/80x80?text=QR Code\nNot Available" 
                                 alt="QR Code" 
                                 class="mx-auto w-20 h-20 shadow-xl rounded-lg border-4 border-white z-10 absolute bottom-5 right-5">
                        @endif
                    </div>

                    <!-- Bottom Section: Title Bar -->
                    <div class="absolute bottom-0 left-0 w-full h-12 flex items-center justify-start" style="background-color: var(--gold); transform: translateY(12px) skewY(-3deg);">

                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 max-w-sm w-full no-print">
        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8 border border-gray-100">
            <h3 class="text-xl font-semibold text-gray-900 mb-5">Card Actions</h3>
            
            <!-- Download Buttons -->
            <div class="space-y-3 mb-6">
                <!-- Keep the original function call -->
                <button onclick="downloadIDCard()" 
                        class="w-full flex justify-center items-center px-5 py-3 text-sm font-bold rounded-xl text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:ring-blue-200 transition shadow-lg">
                    <i class="fas fa-download mr-3"></i> Download ID Card (PNG)
                </button>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-3">
                <a href="{{ route('user.dashboard') }}" 
                   class="flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
                <p class="text-center text-xs text-gray-500 pt-3">
                    For verification issues, please <a href="#" class="text-blue-600 hover:text-blue-700 hover:underline font-medium">Contact Support</a>.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Placeholder function for demonstration. In a real Laravel/PHP environment, this would handle image generation.
    function downloadIDCard() {
        console.log("Download function triggered. You would typically use a library like html2canvas here.");
        // Example alert replacement:
        const cardElement = document.getElementById('idCard');
        if (cardElement) {
            // In a real app, you'd use a custom modal or message box here instead of alert().
            const msgBox = document.createElement('div');
            msgBox.innerHTML = '<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"><div class="bg-white p-6 rounded-lg shadow-2xl max-w-sm text-center"><h4 class="text-xl font-bold mb-3">Download Initiated</h4><p class="text-gray-600 mb-4">The ID card is being prepared for download. In a live application, a file would now download.</p><button onclick="this.parentElement.parentElement.remove()" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Close</button></div></div>';
            document.body.appendChild(msgBox);
        }
    }
</script>