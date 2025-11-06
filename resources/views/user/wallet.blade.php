@extends('layouts.app')

@section('title', 'Wallet - Membership App')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Wallet</h1>
            <p class="text-gray-600">View your balance and transaction history</p>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-100 text-sm">Current Balance</p>
                    <p class="text-white text-3xl font-bold">${{ number_format($wallet->balance ?? 0, 2) }}</p>
                </div>
                <div class="text-primary-100">
                    <i class="fas fa-wallet text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Transaction History</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">All your credit transactions.</p>
            </div>
            @if($transactions->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center 
                                        {{ $transaction->type === 'add' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        <i class="fas {{ $transaction->type === 'add' ? 'fa-plus' : 'fa-minus' }} text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->description }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $transaction->created_at->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium {{ $transaction->type === 'add' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'add' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                </div>
                                <div class="text-xs text-gray-500 capitalize">{{ $transaction->type }}</div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="px-4 py-12 text-center">
                <i class="fas fa-exchange-alt text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No transactions yet</h3>
                <p class="mt-1 text-sm text-gray-500">Your transaction history will appear here.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection