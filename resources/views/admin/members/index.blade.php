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
                                {!! $member->status() !!}
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $member->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                @if($member->status < 1)
                                    <form action="{{ route('admin.members.approve', $member) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.members.reject', $member) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </button>
                                </form>
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