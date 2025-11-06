@extends('layouts.app')

@section('title', 'Pending KYC - Admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Pending KYC Documents</h1>
            <p class="text-gray-600">Review and verify identity documents</p>
        </div>

        @if($documents->count() > 0)
        <div class="grid grid-cols-1 gap-6">
            @foreach($documents as $document)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ $document->document_type }}
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Submitted by {{ $document->user->name }} ({{ $document->user->email }})
                            </p>
                            @if($document->document_number)
                            <p class="text-sm text-gray-600">
                                Document #: {{ $document->document_number }}
                            </p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('admin.kyc.approve', $document) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-check mr-1"></i>Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.kyc.reject', $document) }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="text" name="remarks" placeholder="Rejection reason" 
                                       class="mr-2 border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-times mr-1"></i>Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex justify-center">
                            <img src="{{ Storage::url($document->image_path) }}" 
                                 alt="{{ $document->document_type }}" 
                                 class="max-w-full h-auto max-h-96 rounded-lg shadow-md">
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <p>Uploaded: {{ $document->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-12 sm:px-6 text-center">
                <i class="fas fa-id-card text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No pending KYC documents</h3>
                <p class="mt-1 text-sm text-gray-500">All KYC documents have been reviewed.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection