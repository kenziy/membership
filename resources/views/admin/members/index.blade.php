@extends('layouts.app')

@section('title', 'Manage Members - Admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Manage Members</h1>
            <p class="text-gray-600">View and manage all member accounts</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-gray-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Members</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shield-alt text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">KYC Verified</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['kyc_verified'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-crown text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">VIP Members</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['vip'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-id-card text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">No KYC</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['no_kyc'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Filters & Search</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.members.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ $search }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   placeholder="Name, Email, or Member ID">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="kyc_verified" {{ $status == 'kyc_verified' ? 'selected' : '' }}>KYC Verified</option>
                                <option value="kyc_pending" {{ $status == 'kyc_pending' ? 'selected' : '' }}>KYC Pending</option>
                            </select>
                        </div>

                        <!-- KYC Status Filter -->
                        <div>
                            <label for="kyc_status" class="block text-sm font-medium text-gray-700">KYC Status</label>
                            <select name="kyc_status" id="kyc_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">All KYC Status</option>
                                <option value="no_kyc" {{ $kycStatus == 'no_kyc' ? 'selected' : '' }}>No KYC Submitted</option>
                                <option value="pending_review" {{ $kycStatus == 'pending_review' ? 'selected' : '' }}>KYC Pending Review</option>
                                <option value="has_kyc" {{ $kycStatus == 'has_kyc' ? 'selected' : '' }}>Has KYC Documents</option>
                            </select>
                        </div>

                        <!-- VIP Status Filter -->
                        <div>
                            <label for="vip_status" class="block text-sm font-medium text-gray-700">VIP Status</label>
                            <select name="vip_status" id="vip_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">All Members</option>
                                <option value="vip" {{ $vipStatus == 'vip' ? 'selected' : '' }}>VIP Only</option>
                                <option value="regular" {{ $vipStatus == 'regular' ? 'selected' : '' }}>Regular Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex space-x-3">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.members.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-times mr-2"></i>Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Members Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Members List</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">All registered members with their status and details.</p>
            </div>
            
            @if($members->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KYC</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VIP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($members as $member)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($member->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($member->profile_photo_path) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                        @if($member->member_id)
                                        <div class="text-xs text-gray-400 font-mono">{{ $member->member_id }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $member->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($member->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                       ($member->status === 'kyc_verified' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $member->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $member->kyc_status_badge_color === 'green' ? 'bg-green-100 text-green-800' : 
                                       ($member->kyc_status_badge_color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($member->kyc_status_badge_color === 'blue' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $member->kyc_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.members.toggle-vip', $member) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200 
                                                {{ $member->is_vip ? 'bg-purple-100 text-purple-800 hover:bg-purple-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                        <i class="fas {{ $member->is_vip ? 'fa-crown text-purple-500' : 'fa-user text-gray-500' }} mr-1"></i>
                                        {{ $member->is_vip ? 'VIP' : 'Regular' }}
                                    </button>
                                </form>
                                @if($member->is_vip && $member->vip_since)
                                <div class="text-xs text-gray-500 mt-1">
                                    Since {{ $member->vip_since->format('M j, Y') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($member->wallet?->balance ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $member->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.members.approve', $member) }}" 
                                       class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                       title="Approve Member">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="{{ route('admin.members.reject', $member) }}" 
                                       class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                       title="Reject Member">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a href="#" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $members->links() }}
            </div>
            @else
            <div class="px-4 py-12 text-center">
                <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No members found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filters.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Add confirmation for VIP toggle
document.addEventListener('DOMContentLoaded', function() {
    const vipButtons = document.querySelectorAll('form[action*="toggle-vip"] button');
    vipButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to change VIP status for this member?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection