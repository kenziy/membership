@extends('layouts.app')

@section('title', 'My Profile - Membership App')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600">Manage and update your account information</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column - Profile and Actions -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Profile Photo -->
            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Photo</h3>

                <div class="text-center">
                    @if($user->profile_photo_path)
                        <img src="{{ Storage::url($user->profile_photo_path) }}" 
                             alt="Profile Photo"
                             class="mx-auto h-32 w-32 rounded-full object-cover border-4 border-aether">
                    @else
                        <div class="mx-auto h-32 w-32 rounded-full bg-gray-100 flex items-center justify-center border-4 border-aether">
                            <span class="text-aether font-bold text-3xl">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <!-- Upload Form -->
                    <form action="{{ route('user.profile.photo') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                        @csrf

                        <div class="space-y-3">
                            <input type="file" name="profile_photo"
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-600 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-aether/10 file:text-aether font-medium cursor-pointer">

                            <button type="submit"
                                    class="w-full bg-aether text-white py-2.5 rounded-lg font-medium hover:bg-aether/90 transition">
                                <i class="fas fa-upload mr-2"></i>Update Photo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Membership Status</h3>

                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                    @if($user->status === 'approved') bg-green-100 text-green-800
                    @elseif($user->status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($user->status) }}
                </span>

                @if($user->member_id)
                <p class="mt-4 text-sm">
                    <span class="font-medium text-gray-700">Member ID:</span>
                    <span class="font-mono text-gray-900">{{ $user->member_id }}</span>
                </p>
                @endif

                <p class="mt-2 text-sm">
                    <span class="font-medium text-gray-700">Joined:</span>
                    {{ $user->created_at->format('F j, Y') }}
                </p>
            </div>
        </div>


        <!-- Right Column - Editable Profile Fields -->
        <div class="lg:col-span-2">

            <div class="bg-white shadow rounded-xl">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Edit Profile</h3>
                    <p class="text-gray-600 text-sm">Update your personal details below</p>
                </div>

                <form action="{{ route('user.profile.update') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Full Name -->
                        <div>
                            <label class="text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-aether border p-2" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" value="{{ $user->email }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 border p-2" disabled>
                        </div>

                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="name" value="{{ $user->address }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-aether border p-2" />
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4">
                        <button type="submit"
                                class="bg-aether text-white px-6 py-2.5 rounded-lg font-medium hover:bg-aether/90 transition">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
