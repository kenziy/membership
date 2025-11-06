@extends('layouts.app')

@section('title', 'KYC Verification - Membership App')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">KYC Verification</h1>
            <p class="text-gray-600">Upload your identification documents for verification</p>
        </div>

        <!-- Status Alert -->
        @if(auth()->user()->isKycVerified())
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">KYC Verified</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Your identity has been successfully verified.</p>
                    </div>
                </div>
            </div>
        </div>
        @elseif(auth()->user()->hasPendingKyc())
        <div class="rounded-md bg-yellow-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">KYC Under Review</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Your documents are being reviewed by our team. This usually takes 1-2 business days.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Upload Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Upload Document</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('user.kyc.upload') }}" method="POST" enctype="multipart/form-data" id="kycForm">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="document_type" class="block text-sm font-medium text-gray-700">Document Type *</label>
                                <select id="document_type" name="document_type" required 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                    <option value="">Select document type</option>
                                    <option value="Passport">Passport</option>
                                    <option value="Driver's License">Driver's License</option>
                                    <option value="National ID">National ID</option>
                                    <option value="Utility Bill">Utility Bill</option>
                                    <option value="Bank Statement">Bank Statement</option>
                                </select>
                            </div>

                            <div>
                                <label for="document_number" class="block text-sm font-medium text-gray-700">Document Number (Optional)</label>
                                <input type="text" id="document_number" name="document_number" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                       placeholder="e.g., AB123456">
                            </div>

                            <div x-data="{ isDragging: false }" 
                                 @drop.prevent="isDragging = false; handleFileDrop($event)"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 class="mt-1">
                                <label class="block text-sm font-medium text-gray-700">Document Image *</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md transition-colors duration-200"
                                     :class="isDragging ? 'border-primary-400 bg-primary-50' : 'border-gray-300'">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                                <span>Upload a file</span>
                                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" required @change="previewFile">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG up to 5MB</p>
                                        <div id="filePreview" class="hidden mt-2">
                                            <img id="previewImage" class="mx-auto h-32 object-contain rounded">
                                            <p id="fileName" class="text-sm text-gray-600 mt-1"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="submit" 
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                    <i class="fas fa-upload mr-2"></i>Upload Document
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Uploaded Documents -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">My Documents</h3>
                </div>
                <div class="p-6">
                    @if($documents->count() > 0)
                    <div class="space-y-4">
                        @foreach($documents as $document)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-file-image text-gray-400 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $document->document_type }}</p>
                                    <p class="text-sm text-gray-500">
                                        Uploaded {{ $document->created_at->format('M j, Y') }}
                                    </p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $document->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($document->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                    @if($document->remarks)
                                    <p class="text-sm text-red-600 mt-1">{{ $document->remarks }}</p>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ Storage::url($document->image_path) }}" target="_blank" 
                               class="text-primary-600 hover:text-primary-500 transition-colors duration-200">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-folder-open text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No documents uploaded yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handleFileDrop(event) {
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        const fileInput = document.getElementById('image');
        fileInput.files = files;
        previewFile({ target: fileInput });
    }
}

function previewFile(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            fileName.textContent = file.name;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

// Initialize Alpine.js for drag and drop
document.addEventListener('alpine:init', () => {
    Alpine.data('kycUpload', () => ({
        isDragging: false,
        
        handleFileDrop(event) {
            this.isDragging = false;
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                const fileInput = document.getElementById('image');
                fileInput.files = files;
                this.previewFile({ target: fileInput });
            }
        },
        
        previewFile(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('filePreview');
            const previewImage = document.getElementById('previewImage');
            const fileName = document.getElementById('fileName');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    fileName.textContent = file.name;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
    }));
});
</script>
@endsection