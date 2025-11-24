@extends('layouts.app')

@section('title', 'My Profile - Membership App')

@section('content')
    <div class="flex items-center justify-center p-6" x-data="{ isVIP: {{ $user->isVip() }} }">
        <div class="max-w-xl w-full">
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden 
                border-4 transition-all"
                :class="isVIP ? 'border-yellow-400 shadow-yellow-300' : 'border-gray-200'">

                <!-- Header -->
                <div class="relative">

                    <img src="https://www.shutterstock.com/image-illustration/glowing-candlestick-forex-chart-on-600nw-2340526363.jpg"
                         class="w-full h-40 object-cover opacity-70" alt="Cover">

                    <template x-if="isVIP">
                        <div class="absolute top-3 right-3 bg-yellow-400 text-yellow-900 px-4 py-1 rounded-full text-xs font-bold shadow">
                            ⭐ VIP MEMBER
                        </div>
                    </template>

                    <!-- Avatar -->
                    <div class="absolute -bottom-10 left-6">
                        <img src="{{ $user->profile_photo_path ?Storage::url($user->profile_photo_path) : 'https://placehold.co/80x80?text=' . substr($user->first_name, 0, 1) }}"
                        alt="Profile Photo"
                        class="h-24 w-24 rounded-full border-4 border-white shadow-lg object-cover">
                    </div>
                </div>

                <div class="pt-14 px-6 pb-8">

                    <!-- Name and ID -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">{{ $user->first_name . ' ' . $user->last_name }}</h1>
                            <p class="text-sm text-gray-500 mt-1">ID No: <span class="font-bold">{{ $user->member_id }}</span></p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <h2 class="text-sm font-semibold text-gray-600">Address</h2>
                        <p class="text-gray-800 text-base mt-1">
                            {{ $address['region'] .', '. $address['province'] .', '. $address['city'] . ', ' . $address['barangay'] }}
                        </p>
                    </div>

                    <!-- Additional Profile Info -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="bg-gray-50 p-4 rounded-xl shadow-inner">
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="font-medium text-gray-800">{{ $user->email }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl shadow-inner">
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="font-medium text-gray-800">{{ $user->phone_number }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl shadow-inner">
                            <p class="text-xs text-gray-500">Joined</p>
                            <p class="font-medium text-gray-800">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl shadow-inner">
                            <p class="text-xs text-gray-500">Status</p>
                            <p class="font-medium text-gray-800">{!! $user->status() !!}</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

