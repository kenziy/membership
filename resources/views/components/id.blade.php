<div class="card-container flex flex-col lg:flex-row gap-8 items-start justify-center">
    <div class="flex-1 max-w-sm w-full">
        <div id="idCard" class="bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 transform hover:scale-[1.01] border border-gray-100">
            <div class="bg-slate-800 py-4 px-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold tracking-tight">DIGITAL ID</h2>
                    <p class="text-slate-300 text-sm font-light">Verified Identity & Access</p>
                </div>
            </div>
            <div class="p-6">
                <!-- Profile Section: Centered and Clean -->
                <div class="flex flex-col items-center justify-center space-y-4 mb-6">
                    <div class="flex-shrink-0">
                        <!-- PHP Blade Logic for Profile Photo -->
                        @if($user->profile_photo_path)
                            <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                 alt="Profile Photo" 
                                 class="h-24 w-24 rounded-full object-cover border-4 border-slate-100 shadow-md">
                        @else
                            <div class="h-24 w-24 rounded-full bg-slate-600 flex items-center justify-center text-white font-semibold text-2xl border-4 border-slate-100 shadow-md">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-extrabold text-gray-900 leading-tight">{{ $user->name }}</h3>
                    </div>
                </div>

                <!-- QR Code Section: Prominent Highlight -->
                <div class="bg-gray-50 p-6 rounded-xl border-4 border-slate-200 shadow-inner mt-6 mb-6">
                    <div class="text-center">
                        <!-- PHP Blade Logic for QR Code -->
                        @if($user->qr_code_path)
                            <img src="{{ Storage::url($user->qr_code_path) }}" 
                                 alt="QR Code" 
                                 class="mx-auto w-40 h-40 shadow-xl rounded-lg border-4 border-white">
                        @else
                            <div class="mx-auto w-40 h-40 bg-white flex items-center justify-center rounded-lg shadow-inner border-2 border-dashed border-gray-300">
                                <span class="text-gray-400 text-center text-xs p-2">QR Code<br>Not Available</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-center text-xs text-gray-500 uppercase tracking-widest mt-3">
                        <span class="font-mono text-gray-800 font-bold">{{ $user->member_id }}</span>
                    </div>
                </div>
                @if($user->isVIP())
                <div class="flex items-center justify-center">
                    <div class="bg-gradient-to-r from-yellow-400 to-amber-500 text-amber-900 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg border border-amber-300">
                        <i class="fas fa-crown mr-1"></i>VIP Member
                    </div>
                </div>
                @endif

                <!-- Footer Separator (Optional extra info) -->
                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between text-xs text-gray-400">
                    <span class="flex items-center text-green-700"><i class="fas fa-lock mr-1"></i> Secured Digitally</span>
                    <span class="flex items-center">Requires Validation</span>
                </div>
            </div>
            <div class="bg-slate-800/90 py-3 px-6 text-xs text-slate-300 flex justify-between">
                <p>Issued: {{ $user->created_at->format('d/m/Y') }}</p>
                <p>Valid thru: {{ $user->created_at->addYears(1)->format('d/m/Y') }}</p>
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
                        class="w-full flex justify-center items-center px-5 py-3 text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition shadow-lg">
                    <i class="fas fa-download mr-3"></i> Download ID Card (PNG)
                </button>
                <button onclick="window.print()" 
                        class="w-full flex justify-center items-center px-5 py-3 text-sm font-medium rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition border border-gray-200">
                    <i class="fas fa-print mr-3"></i> Print Card
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