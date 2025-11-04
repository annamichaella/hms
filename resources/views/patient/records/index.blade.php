@extends('layouts.patient')

@section('title', 'Medical Records')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Medical Records</h1>
        <p class="text-gray-600">View your complete medical history and health information</p>
    </div>

    @forelse($records as $record)
        <div class="patient-card p-8 mb-6 bg-gray-50">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-file-medical text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Medical Information</h2>
                    <p class="text-gray-600">Last updated: {{ $record->updated_at->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Blood Type -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-2xl border border-red-100">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-tint text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Blood Type</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $record->blood_type ?? 'Not specified' }}</p>
                    <p class="text-sm text-gray-600 mt-2">Your blood type for medical reference</p>
                </div>

                <!-- Allergies -->
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-6 rounded-2xl border border-yellow-100">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Allergies</h3>
                    </div>
                    <p class="text-lg font-medium text-gray-800">{{ $record->allergies ?? 'None recorded' }}</p>
                    <p class="text-sm text-gray-600 mt-2">Known allergies and reactions</p>
                </div>

                <!-- Medical Conditions -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-heartbeat text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Medical Conditions</h3>
                    </div>
                    <p class="text-lg font-medium text-gray-800">{{ $record->medical_conditions ?? 'None recorded' }}</p>
                    <p class="text-sm text-gray-600 mt-2">Current and past medical conditions</p>
                </div>

                <!-- Emergency Contact -->
                @if($record->emergency_contact_name)
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Emergency Contact</h3>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-800">{{ $record->emergency_contact_name }}</p>
                        @if($record->emergency_contact_phone)
                            <p class="text-base text-gray-600 mt-1">
                                <i class="fas fa-phone-alt mr-2"></i>{{ $record->emergency_contact_phone }}
                            </p>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Contact person in case of emergency</p>
                </div>
                @endif
            </div>

            @if($record->notes)
            <div class="mt-6 bg-gray-50 p-6 rounded-2xl border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-sticky-note text-blue-500 mr-2"></i>
                    Additional Notes
                </h3>
                <p class="text-gray-700 leading-relaxed">{{ $record->notes }}</p>
            </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-file-medical text-gray-400 text-4xl"></i>
            </div>
            <h3>No medical records found</h3>
            <p>Your medical information will appear here once your healthcare provider adds it to your record.</p>
            <p class="text-sm text-gray-500 mb-0">If you believe this is an error, please contact your healthcare provider.</p>
        </div>
    @endforelse
</div>
@endsection
