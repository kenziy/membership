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
                    <span class="text-aether font-bold text-3xl">{{ substr($user->name, 0, 1) }}</span>
                </div>
            @endif

            <div>
                <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
                <p class="text-sm text-gray-400 mt-1">User ID: {{ $user->id }}</p>
            </div>
        </div>

        <!-- Update Form -->
        <form method="POST" action="{{ route('admin.members.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    type="text"
                    name="name"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    value="{{ old('name', $user->name) }}"
                    required
                >
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    name="email"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    value="{{ old('email', $user->email) }}"
                    required
                >
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Approved</option>
                    <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>Fully Verified</option>
                    <option value="-1" {{ $user->status == -1 ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Upload Photo -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Profile Photo</label>

                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="mt-1 block w-full"
                    onchange="previewImage(event)"
                >

                <p class="text-xs text-gray-400 mt-1">Recommended: Square 400x400px</p>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between mt-6">
                <a href="{{ route('admin.members.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">
                    Back
                </a>

                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                    Update User
                </button>
            </div>
        </form>

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