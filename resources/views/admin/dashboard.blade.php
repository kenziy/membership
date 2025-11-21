@extends('layouts.app')

@section('title', 'Admin Dashboard - Membership App')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600">System overview and quick actions</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Pending Members -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-yellow-500 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Members</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $pendingMembersCount }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.members.pending') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                            View all
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending KYC -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-id-card text-orange-500 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending KYC</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $pendingKycCount }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.kyc.pending') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                            Review documents
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Members -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-check text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Members</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalMembersCount }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Member Registrations</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($recentMembers as $member)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            {!! $member->status() !!}
                            <span class="text-xs text-gray-500">{{ $member->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection