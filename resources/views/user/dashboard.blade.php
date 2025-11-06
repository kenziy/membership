@extends('layouts.app')

@section('title', 'Dashboard - Membership App')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Here's your membership overview</p>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Membership Status -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-id-card text-{{ auth()->user()->isApproved() ? 'green' : 'yellow' }}-400 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Membership Status</dt>
                                <dd class="text-lg font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', auth()->user()->status) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member ID -->
            @if(auth()->user()->member_id)
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-fingerprint text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Member ID</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ auth()->user()->member_id }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Wallet Balance -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-wallet text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Wallet Balance</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format(auth()->user()->wallet?->balance ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KYC Status -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-{{ auth()->user()->isKycVerified() ? 'green' : 'orange' }}-400 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">KYC Status</dt>
                                <dd class="text-lg font-medium text-gray-900 capitalize">
                                    {{ auth()->user()->isKycVerified() ? 'Verified' : (auth()->user()->hasPendingKyc() ? 'Pending' : 'Not Started') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Digital ID Card -->
            @if(auth()->user()->isApproved())
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-qrcode text-primary-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Digital ID Card</h3>
                            <p class="mt-1 text-sm text-gray-500">Access your digital membership card</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('user.id-card') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                            <i class="fas fa-download mr-2"></i>View ID Card
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- KYC Verification -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-id-card text-orange-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">KYC Verification</h3>
                            <p class="mt-1 text-sm text-gray-500">Submit identification documents</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('user.kyc') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                            <i class="fas fa-upload mr-2"></i>Upload Documents
                        </a>
                    </div>
                </div>
            </div>

            <!-- Wallet -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-wallet text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Wallet</h3>
                            <p class="mt-1 text-sm text-gray-500">View balance and transactions</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('user.wallet') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-eye mr-2"></i>View Wallet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        @if(auth()->user()->transactions->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h3>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach(auth()->user()->transactions->take(5) as $transaction)
                    <li>
                        <div class="px-4 py-4 flex items-center sm:px-6">
                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <div class="flex text-sm">
                                        <p class="font-medium text-primary-600 truncate">{{ $transaction->description }}</p>
                                        <p class="ml-1 flex-shrink-0 font-normal text-gray-500">in wallet</p>
                                    </div>
                                    <div class="mt-2 flex">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1.5"></i>
                                            <p>{{ $transaction->created_at->format('M j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0 sm:mt-0 sm:ml-5">
                                    <div class="flex items-center text-sm {{ $transaction->type === 'add' ? 'text-green-600' : 'text-red-600' }}">
                                        <span class="font-medium">{{ $transaction->type === 'add' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection