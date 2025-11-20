@extends('layouts.app')

@section('title', 'Dashboard - Membership App')

@section('content')


<div x-data="{ progress: {{ auth()->user()->status }} }" class="w-full flex justify-center mt-10">

    <ol class="items-center w-full max-w-3xl mx-auto 
               space-y-4 sm:flex sm:space-x-8 sm:space-y-2 rtl:space-x-reverse">

        <!-- STEP 1 -->
        <li class="flex items-center space-x-3 rtl:space-x-reverse"
            :class="progress >= 0 ? 'text-orange-600' : 'text-gray-400'">

            <span class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0"
                :class="progress >= 0 ? 'bg-orange-100' : 'bg-gray-200'">
                <span class="text-lg font-bold"
                    :class="progress >= 0 ? 'text-orange-600' : 'text-gray-400'">1</span>
            </span>

            <span>
                <h3 class="font-medium leading-tight">Waiting for Approval</h3>
                <p class="text-sm text-gray-500">Your registration is being reviewed.</p>
            </span>
        </li>

        <!-- STEP 2 -->
        <li class="flex items-center space-x-3 rtl:space-x-reverse"
            :class="progress >= 1 ? 'text-orange-600' : 'text-gray-400'">

            <span class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0"
                :class="progress >= 1 ? 'bg-orange-100' : 'bg-gray-200'">
                <span class="text-lg font-bold"
                    :class="progress >= 1 ? 'text-orange-600' : 'text-gray-400'">2</span>
            </span>

            <span>
                <h3 class="font-medium leading-tight">Waiting for Verification</h3>
                <p class="text-sm text-gray-500">Please complete your Identity verification.</p>
            </span>
        </li>

        <!-- STEP 3 -->
        <li class="flex items-center space-x-3 rtl:space-x-reverse"
            :class="progress == 2 ? 'text-orange-600' : 'text-gray-400'">

            <span class="flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0"
                :class="progress == 2 ? 'bg-orange-100' : 'bg-gray-200'">
                <span class="text-lg font-bold"
                    :class="progress == 2 ? 'text-orange-600' : 'text-gray-400'">3</span>
            </span>

            <span>
                <h3 class="font-medium leading-tight">Process Complete</h3>
                <p class="text-sm text-gray-500">Your account is now fully activated.</p>
            </span>
        </li>
    </ol>
</div>


<div x-data="{ progress: {{ auth()->user()->status }} }" class="max-w-xl mx-auto mt-10">

    <!-- MESSAGE PANEL -->
    <div class="p-6 rounded-xl border bg-white shadow-sm">

        <!-- WAITING FOR APPROVAL -->
        <template x-if="progress === 0">
            <div>
                <h2 class="text-xl font-semibold text-orange-600">Waiting for Approval</h2>
                <p class="mt-2 text-gray-600">
                    Thank you for registering. Your application is currently under review.  
                    Please wait while our team verifies your information.  
                </p>
                <p class="mt-2 text-gray-500 text-sm">
                    Approval usually takes a short time. You will be notified once completed.
                </p>
            </div>
        </template>

        <!-- WAITING FOR VERIFICATION -->
        <template x-if="progress === 1">
            <div>
                <h2 class="text-xl font-semibold text-orange-600">Verification Required</h2>
                <p class="mt-2 text-gray-600">
                    Your account has been approved. Please complete the verification process by uploading the required documents.
                </p>

                <a href="{{ route('user.kyc') }}"
                   class="inline-block mt-4 px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    Upload Documents
                </a>

                <p class="mt-2 text-gray-500 text-sm">
                    Make sure to upload a valid ID and any supporting documents needed for identity verification.
                </p>
            </div>
        </template>

        <!-- PROCESS COMPLETE -->
        <template x-if="progress === 2">
            <div>
                <h2 class="text-xl font-semibold text-orange-600">Welcome! 🎉</h2>
                <p class="mt-2 text-gray-600">
                    Congratulations! Your application has been fully approved and verified.
                </p>
                <p class="mt-2 text-gray-500 text-sm">
                    You now have full access to your AetherPay account and all available services.
                </p>

                <a href="/dashboard"
                   class="inline-block mt-4 px-5 py-2 bg-orange-600 text-white rounded-lg hover:bg-blue-700">
                    Go to Dashboard
                </a>
            </div>
        </template>

        <!-- APPLICATION REJECTED -->
        <template x-if="progress === 4">
            <div>
                <h2 class="text-xl font-semibold text-red-600">Application Rejected</h2>
                <p class="mt-2 text-gray-600">
                    We're sorry, but your application did not meet the requirements at this time.
                </p>
                <p class="mt-2 text-gray-500 text-sm">
                    You may review your submitted information or contact support for assistance.
                </p>

                <a href="/support"
                   class="inline-block mt-4 px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Contact Support
                </a>
            </div>
        </template>
    </div>

</div>






@endsection