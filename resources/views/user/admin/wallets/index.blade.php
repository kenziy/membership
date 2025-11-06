@extends('layouts.app')

@section('title', 'Wallet Management - Admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Wallet Management</h1>
            <p class="text-gray-600">Manage member wallet balances</p>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Member Wallets</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">All member wallets and their current balances.</p>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @foreach($wallets as $wallet)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($wallet->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $wallet->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $wallet->user->email }}</div>
                                        @if($wallet->user->member_id)
                                        <div class="text-xs text-gray-400">{{ $wallet->user->member_id }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <div class="text-lg font-semibold text-gray-900">
                                            ${{ number_format($wallet->balance, 2) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $wallet->transactions->count() }} transactions
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.wallets.add', $wallet->user) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <input type="number" name="amount" step="0.01" min="0.01" max="10000" 
                                               placeholder="Amount" 
                                               class="border border-gray-300 rounded-md px-3 py-1 text-sm w-24"
                                               required>
                                        <input type="text" name="description" 
                                               placeholder="Description" 
                                               class="border border-gray-300 rounded-md px-3 py-1 text-sm w-32">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-plus mr-1"></i>Add
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection