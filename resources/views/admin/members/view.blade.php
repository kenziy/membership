@extends('layouts.app')

@section('title', 'Manage Members - Admin')

@section('content')


<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Manage User Profile</h1>

    <!-- Profile Card -->
    <div class="bg-white shadow rounded-lg p-6">

        <!-- User Header -->
        <div class="flex items-center space-x-4 mb-8">

            @if($user->profile_photo_path)
	            <img
	                src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : '' }}"
	                class="w-24 h-24 rounded-full object-cover border"
	                id="imagePreview"
	            />
            @else
                <div class="h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center border-4 border-aether">
                    <span class="text-aether font-bold text-3xl">{{ substr($user->first_name, 0, 1) }}</span>
                </div>
            @endif

            <div>
                <h2 class="text-xl font-semibold">{{ $user->first_name .' '. $user->last_name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
                <p class="text-sm text-gray-400 mt-1">
                    <a href="{{ url('/verify/' . $user->member_id) }}" target="_blank">{{ $user->member_id }}</a>
                </p>
            </div>
        </div>

        <!-- Update Form -->
        <form method="POST" action="{{ route('admin.members.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            <!-- Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input
                        type="text"
                        name="first_name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2"
                        value="{{ old('first_name', $user->first_name) }}"
                        required
                    >
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input
                        type="text"
                        name="last_name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2"
                        value="{{ old('last_name', $user->last_name) }}"
                        required
                    >
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input
                        type="text"
                        name="username"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2"
                        value="{{ old('username', $user->username) }}"
                        required
                    >
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input
                        type="text"
                        name="address"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2"
                        value="{{ old('address', $user->address) }}"
                        required
                    >
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input
                        type="text"
                        name="phone_number"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border"
                        value="{{ old('phone_number', $user->phone_number) }}"
                        required
                    >
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Approved</option>
                        <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>Fully Verified</option>
                        <option value="-1" {{ $user->status == -1 ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">VIP</label>
                    <select name="is_vip" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                        <option value="0" {{ $user->is_vip == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $user->is_vip == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
                
            </div>

            <x-address
                :regionCode="$user->location_region_code"
                :provinceCode="$user->location_province_code"
                :cityCode="$user->location_city_code"
                :barangayCode="$user->location_barangay_code"
                :streetValue="$user->location_barangay_street"
            />

            <!-- Buttons -->
            <div class="flex justify-between mt-6">
                <a href="{{ route('admin.members.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">
                    Back
                </a>

                <button
                    type="submit"
                    class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700"
                >
                    Update User
                </button>
            </div>
        </form>

    </div>

    <div class="mt-5">
        <x-id :user="$user" />
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('imagePreview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>


@endsection