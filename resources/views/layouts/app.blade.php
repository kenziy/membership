<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Membership App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ url('css/aether.css') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b" x-data="{ mobileMenuOpen: false, userMenuOpen: false, userMenuOpenMobile: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and main nav -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}">
                            <img src="https://www.aetherpay.ai/images/logo.svg" />
                        </a>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Dashboard
                                </a>
                                <a href="{{ route('user.id-card') }}" class="{{ request()->routeIs('user.id-card') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Digital ID
                                </a>
                                <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Members
                                </a>
                                <a href="{{ route('admin.kyc.pending') }}" class="{{ request()->routeIs('admin.kyc.*') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    KYC Review
                                </a>
                            @else
                                <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.*') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Dashboard
                                </a>

                                @if(Auth()->user()->status > 0 )
                                    <a href="{{ route('user.kyc') }}" class="{{ request()->routeIs('user.kyc') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Verification
                                    </a>
                                @endif
                            @if(Auth()->user()->status > 1 )
                                    <a href="{{ route('user.id-card') }}" class="{{ request()->routeIs('user.id-card') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Digital ID
                                    </a>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- User menu -->
                @auth
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="ml-3 relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <div class="h-8 w-8 rounded-full bg-aether flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>

                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false" 
                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->member_id)
                                <p class="text-xs text-gray-400">{{ auth()->user()->member_id }}</p>
                                @endif
                            </div>
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i>Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-aether text-white hover:bg-orange-700 px-4 py-2 rounded-md text-sm font-medium">Register</a>
                </div>
                @endauth

                <!-- Mobile menu button -->
                <div class="sm:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 mr-4">
                        <i class="fas fa-bars"></i>
                    </button>
                    @auth
                        <button @click="userMenuOpenMobile = !userMenuOpenMobile">
                            <div class="h-8 w-8 rounded-full bg-aether flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                    @endauth

                </div>
                @auth
                    <div x-show="userMenuOpenMobile" @click.away="userMenuOpenMobile = false" 
                         class="origin-top-right absolute right-0 mt-[60px] w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="px-4 py-2 border-b">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            @if(auth()->user()->member_id)
                            <p class="text-xs text-gray-400">{{ auth()->user()->member_id }}</p>
                            @endif
                        </div>
                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" class="sm:hidden border-t">
            <div class="pt-2 pb-3 space-y-1">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-primary-50 border-primary-500 text-primary-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.members.pending') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-users mr-2"></i>Members
                        </a>
                        <a href="{{ route('admin.kyc.pending') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-id-card mr-2"></i>KYC Review
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="bg-primary-50 border-primary-500 text-primary-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('user.id-card') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-id-badge mr-2"></i>Digital ID
                        </a>
                        <a href="{{ route('user.kyc') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-id-card mr-2"></i>KYC
                        </a>
                        <a href="{{ route('user.wallet') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            <i class="fas fa-wallet mr-2"></i>Wallet
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Login</a>
                    <a href="{{ route('register') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- footer -->
    <footer class="w-full bg-white text-gray-800 border-t mt-10">
        <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Logo + Description -->
            <div class="col-span-1 md:col-span-2 space-y-4">
                <img src="https://www.aetherpay.ai/images/logo.svg" 
                     alt="AetherPay Logo"
                     class="w-40">

                <p class="text-sm leading-relaxed">
                    A next-generation digital payment solution and decentralized banking system 
                    built in the Philippines, powered by blockchain technology.
                </p>
            </div>

            <!-- Useful Links -->
            <div>
                <h3 class="font-semibold text-lg mb-3">Company</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-aether">About Us</a></li>
                    <li><a href="#" class="hover:text-aether">Contact</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="font-semibold text-lg mb-3">Legal</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-aether">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-aether">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="w-full border-t py-4 text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} AetherPay. All rights reserved.
        </div>
    </footer>



    <!-- Notifications -->
    <div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-50">
        <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
            <!-- Notification panel, dynamically show/hide based on state -->
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">Success!</p>
                            <p class="mt-1 text-sm text-gray-500">{{ session('success') }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div x-data="{ show: true }" x-show="show" class="max-w-sm w-full bg-red-50 shadow-lg rounded-lg pointer-events-auto ring-1 ring-red-500 ring-opacity-5 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-red-800">Error!</p>
                            @foreach($errors->all() as $error)
                            <p class="mt-1 text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="bg-red-50 rounded-md inline-flex text-red-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Alpine.js components
        document.addEventListener('alpine:init', () => {
            Alpine.data('uploadForm', () => ({
                files: [],
                fileDragging: null,
                fileDropping: null,
                
                removeFile(index) {
                    this.files.splice(index, 1);
                },
                
                drop(e) {
                    let removed, add;
                    const files = [...e.dataTransfer.files];
                    
                    removed = files.splice(this.fileDragging, 1);
                    files.splice(this.fileDropping, 0, ...removed);
                    
                    this.files = files;
                    this.fileDropping = null;
                    this.fileDragging = null;
                },
                
                dragenter(e) {
                    let targetElem = e.target.closest('[draggable]');
                    this.fileDropping = targetElem.getAttribute('data-index');
                },
                
                dragstart(e) {
                    this.fileDragging = e.target.closest('[draggable]').getAttribute('data-index');
                    e.dataTransfer.effectAllowed = 'move';
                }
            }));
        });
    </script>
</body>
</html>