@extends('layouts.app')

@section('title', 'Pending Members - Admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Pending Members</h1>
            <p class="text-gray-600">Approve or reject member applications</p>
        </div>

        @if($members->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($members as $member)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                    <div class="text-xs text-gray-400">
                                        Registered {{ $member->created_at->diffForHumans() }}
                                    </div>
                                    @if($member->kycDocuments->count() > 0)
                                    <div class="text-xs text-blue-600 mt-1">
                                        <i class="fas fa-id-card mr-1"></i>
                                        {{ $member->kycDocuments->count() }} KYC document(s) uploaded
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <form action="{{ route('admin.members.approve', $member) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.members.reject', $member) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @else
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-12 sm:px-6 text-center">
                <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No pending members</h3>
                <p class="mt-1 text-sm text-gray-500">All member applications have been processed.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection