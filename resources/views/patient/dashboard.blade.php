@extends('layouts.app')

@section('title', 'Patient Dashboard')
@section('page-title', 'Patient Dashboard')

@section('sidebar')
    <div class="px-4 space-y-2">
        <a href="{{ route('patient.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 bg-blue-50 rounded-lg">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
        </a>
        <a href="{{ route('patient.appointments') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-calendar-check mr-3"></i>
            My Appointments
        </a>
        <a href="{{ route('patient.records') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-file-medical mr-3"></i>
            Medical Records
        </a>
        <a href="{{ route('patient.billing') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-credit-card mr-3"></i>
            Billing
        </a>
    </div>
@endsection

@section('content')
    <!-- Welcome Message -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-user text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-blue-900">Welcome back, {{ Auth::user()->full_name }}!</h2>
                <p class="text-blue-700">Here's an overview of your health information and upcoming appointments.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Upcoming</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_appointments']->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-credit-card text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Bills</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_bills'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Appointments</h3>
                    <a href="{{ route('patient.appointments') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($stats['upcoming_appointments']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['upcoming_appointments'] as $appointment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        Dr. {{ $appointment->doctor->full_name ?? 'Unknown Doctor' }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $appointment->reason ?? 'General consultation' }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 mb-4">No upcoming appointments</p>
                        <a href="{{ route('patient.appointments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                            Book Appointment
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Medical Records Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Medical Records</h3>
            </div>
            <div class="p-6">
                @if($stats['patient_record'])
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Blood Type:</span>
                            <span class="text-sm text-gray-900">{{ $stats['patient_record']->blood_type ?? 'Not specified' }}</span>
                        </div>
                        <div class="flex items-start justify-between">
                            <span class="text-sm font-medium text-gray-600">Allergies:</span>
                            <span class="text-sm text-gray-900 text-right max-w-xs">
                                {{ $stats['patient_record']->allergies ?? 'None recorded' }}
                            </span>
                        </div>
                        <div class="flex items-start justify-between">
                            <span class="text-sm font-medium text-gray-600">Medical Conditions:</span>
                            <span class="text-sm text-gray-900 text-right max-w-xs">
                                {{ $stats['patient_record']->medical_conditions ?? 'None recorded' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Emergency Contact:</span>
                            <span class="text-sm text-gray-900">{{ $stats['patient_record']->emergency_contact_name ?? 'Not specified' }}</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('patient.records') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            View Full Records →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-medical text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 mb-4">No medical records found</p>
                        <p class="text-sm text-gray-400">Contact your healthcare provider to add your medical information.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection



