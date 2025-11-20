<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Left Side -->
            <div class="flex items-center space-x-6">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
                    <x-application-logo class="h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>

                <!-- Primary Navigation (Desktop Only) -->
                <div class="hidden md:flex items-center space-x-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

            </div>

            <!-- Right Side (Desktop Only) -->
            <div class="hidden md:flex items-center space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none transition">
                            <span x-data="{ name: '{{ auth()->user()->name }}' }"
                                  x-text="name"
                                  x-on:profile-updated.window="name = $event.detail.name">
                            </span>

                            <svg class="ms-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.22 4.48a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="open = !open"
                    class="md:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-300 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    <!-- Mobile Menu Panel (Uses SAME MENU as Desktop) -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">

        <div class="px-4 py-3 space-y-2">

            <!-- Main Navigation (Same Links as Desktop) -->
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="block">
                {{ __('Dashboard') }}
            </x-nav-link>

            <!-- User Info -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="font-medium text-gray-800 dark:text-gray-200">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-gray-500 text-sm">
                    {{ auth()->user()->email }}
                </div>
            </div>

            <!-- Profile + Logout -->
            <x-nav-link :href="route('profile')" wire:navigate class="block">
                {{ __('Profile') }}
            </x-nav-link>

            <button wire:click="logout" class="w-full text-left">
                <x-nav-link class="block">
                    {{ __('Log Out') }}
                </x-nav-link>
            </button>

        </div>
    </div>
</nav>

