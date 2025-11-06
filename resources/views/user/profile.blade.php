@extends('layouts.app')

@section('title', 'My Profile - Membership App')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600">Manage your account information</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Member Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $user->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($user->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>
                            </div>

                            @if($user->member_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Member ID</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $user->member_id }}</p>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Registration Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Photo -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Profile Photo</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            @if($user->profile_photo_path)
                                <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                     alt="Profile Photo" 
                                     class="mx-auto h-32 w-32 rounded-full object-cover border-4 border-primary-200">
                            @else
                                <div class="mx-auto h-32 w-32 rounded-full bg-primary-100 flex items-center justify-center border-4 border-primary-200">
                                    <span class="text-primary-600 font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif

                            <form action="{{ route('user.profile.photo') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                @csrf
                                <div class="flex flex-col space-y-2">
                                    <input type="file" name="profile_photo" 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                           accept="image/*">
                                    <button type="submit" 
                                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-upload mr-2"></i>Update Photo
                                    </button>
                                </div>
                            </form>

                            @if($user->profile_photo_path)
                            <form action="{{ route('user.profile.photo') }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-sm text-red-600 hover:text-red-500 focus:outline-none">
                                    <i class="fas fa-trash mr-1"></i>Remove Photo
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white shadow rounded-lg mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Stats</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">KYC Documents</span>
                                <span class="text-sm font-medium text-gray-900">{{ $user->kycDocuments->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Wallet Balance</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($user->wallet?->balance ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Transactions</span>
                                <span class="text-sm font-medium text-gray-900">{{ $user->transactions->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection