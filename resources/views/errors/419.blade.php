@extends('layouts.app')

@section('title', '491 - ' . env('APP_NAME'))
@section('content')


<div class="text-center max-w-md mx-auto">

    <!-- Title -->
    <h1 class="text-7xl font-extrabold text-orange-400">491</h1>
    <p class="text-xl text-gray-600 mt-2">Page Expired</p>

    <!-- Message -->
    <p class="text-gray-500 mt-4">
        The page you're looking for doesn't exist or may have been expired.
    </p>

    <!-- Home Button -->
    <a href="{{ url('/') }}"
       class="inline-block mt-6 px-6 py-3 bg-orange-400 text-white font-medium rounded-full shadow hover:bg-orange-700 transition">
        Go Back Home
    </a>
</div>

@endsection