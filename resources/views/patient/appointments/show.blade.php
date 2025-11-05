@extends('layouts.patient')

@section('title', 'Appointment Details')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('patient.appointments') }}" 
               class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md hover:shadow-lg transition-shadow text-gray-600 hover:text-blue-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Appointment Details</h1>
                <p class="text-gray-600">View your appointment information</p>
            </div>
        </div>
    </div>

    <div class="patient-card p-8 bg-gray-50">
        <!-- Doctor Information -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100 mb-6">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-md text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Dr. {{ $appointment->doctor->full_name ?? 'Unknown Doctor' }}</h2>
                    @if($appointment->doctor->specialization)
                        <p class="text-gray-600">{{ $appointment->doctor->specialization }}</p>
                    @endif
                </div>
            </div>
            @if($appointment->doctor->email)
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    {{ $appointment->doctor->email }}
                </div>
            @endif
        </div>

        <!-- Appointment Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                        <i class="far fa-calendar-alt text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Appointment Date</h3>
                </div>
                <p class="text-2xl font-bold text-gray-800">{{ $appointment->appointment_date->format('F d, Y') }}</p>
                <p class="text-sm text-gray-600 mt-2">{{ $appointment->appointment_date->format('l') }}</p>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                        <i class="far fa-clock text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Appointment Time</h3>
                </div>
                <p class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                <p class="text-sm text-gray-600 mt-2">Please arrive 15 minutes early</p>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-100 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Appointment Status</h3>
                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full
                        @if($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                        @elseif($appointment->status == 'confirmed') bg-green-100 text-green-700
                        @elseif($appointment->status == 'completed') bg-blue-100 text-blue-700
                        @else bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
                <div class="w-16 h-16 rounded-full flex items-center justify-center
                    @if($appointment->status == 'pending') bg-yellow-100
                    @elseif($appointment->status == 'confirmed') bg-green-100
                    @elseif($appointment->status == 'completed') bg-blue-100
                    @else bg-red-100
                    @endif">
                    <i class="fas 
                        @if($appointment->status == 'pending') fa-clock text-yellow-600
                        @elseif($appointment->status == 'confirmed') fa-check-circle text-green-600
                        @elseif($appointment->status == 'completed') fa-check-double text-blue-600
                        @else fa-times-circle text-red-600
                        @endif text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Reason for Visit -->
        @if($appointment->reason)
        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-sticky-note text-blue-500 mr-2"></i>
                Reason for Visit
            </h3>
            <p class="text-gray-700 leading-relaxed">{{ $appointment->reason }}</p>
        </div>
        @endif

        <!-- Assigned Nurse -->
        @if($appointment->nurse)
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-2xl border border-indigo-100 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-nurse text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Assigned Nurse</h3>
                    <p class="text-gray-700">{{ $appointment->nurse->full_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
            @if($appointment->status == 'pending')
                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to cancel this appointment?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full btn-danger">
                        <i class="fas fa-times mr-2"></i>Cancel Appointment
                    </button>
                </form>
            @endif
            @include('partials.back-button', ['href' => route('patient.appointments'), 'label' => 'Back to Appointments'])
        </div>
    </div>
</div>
@endsection
