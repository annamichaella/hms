@extends('layouts.patient')

@section('title', 'Patient Dashboard')

@section('content')
<div class="fade-in">
    <!-- Hero Welcome Section -->
    <section class="mb-16">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Welcome back,
                    <span class="text-blue-600">{{ Auth::user()->fname }}!</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Your health is our priority. Here's an overview of your appointments, 
                    medical records, and health information all in one place.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('patient.appointments.create') }}" class="btn-primary inline-flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Book an Appointment
                    </a>
                    <a href="{{ route('patient.appointments') }}" class="btn-outline inline-flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        View Appointments
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex flex-wrap items-center gap-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check text-blue-500 mr-2 text-lg"></i>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_appointments'] }}</p>
                            <p class="text-xs text-gray-600">Total Appointments</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-green-500 mr-2 text-lg"></i>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['upcoming_appointments']->count() }}</p>
                            <p class="text-xs text-gray-600">Upcoming</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-receipt text-purple-500 mr-2 text-lg"></i>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_bills'] }}</p>
                            <p class="text-xs text-gray-600">Pending Bills</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image/Illustration -->
            <div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl p-8 shadow-2xl">
                        <div class="bg-white rounded-2xl p-8 shadow-lg">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                    <i class="fas fa-heartbeat text-white text-4xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">Your Health Dashboard</h3>
                                <p class="text-gray-600 mb-6">Access your medical information anytime, anywhere</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 rounded-xl p-4">
                                        <i class="fas fa-user-md text-blue-600 text-2xl mb-2"></i>
                                        <p class="text-sm font-medium text-gray-800">Expert Doctors</p>
                                    </div>
                                    <div class="bg-green-50 rounded-xl p-4">
                                        <i class="fas fa-shield-alt text-green-600 text-2xl mb-2"></i>
                                        <p class="text-sm font-medium text-gray-800">Secure & Private</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features/Quick Access Section -->
    <section class="mb-16 py-12 px-4 sm:px-6 lg:px-8 bg-white rounded-2xl shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    Quick Access
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to manage your healthcare, all in one convenient place.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Appointments Card -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">My Appointments</h3>
                <p class="text-gray-600 mb-6">
                    View, schedule, and manage your appointments with our healthcare providers.
                </p>
                <a href="{{ route('patient.appointments') }}" 
                   class="text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center">
                    View Appointments <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Medical Records Card -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-file-medical text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Medical Records</h3>
                <p class="text-gray-600 mb-6">
                    Access your complete medical history, test results, and health information.
                </p>
                <a href="{{ route('patient.records') }}" 
                   class="text-green-600 hover:text-green-700 font-semibold inline-flex items-center">
                    View Records <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Billing Card -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-credit-card text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Billing & Payments</h3>
                <p class="text-gray-600 mb-6">
                    View your bills, payment history, and manage your account payments.
                </p>
                <a href="{{ route('patient.billing') }}" 
                   class="text-purple-600 hover:text-purple-700 font-semibold inline-flex items-center">
                    View Bills <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Appointments Section -->
    <section class="mb-16 py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 rounded-2xl">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-calendar-check mr-3"></i>
                        Upcoming Appointments
                    </h2>
                    <a href="{{ route('patient.appointments') }}" 
                       class="text-white/90 hover:text-white text-sm font-medium underline">
                        View All
                    </a>
                </div>
                </div>
                <div class="p-8">
                @if($stats['upcoming_appointments']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['upcoming_appointments'] as $appointment)
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:border-blue-300 transition-all">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4 flex-1">
                                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-user-md text-blue-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                                Dr. {{ $appointment->doctor->full_name ?? 'Unknown Doctor' }}
                                            </h3>
                                            <p class="text-gray-600 mb-3">{{ $appointment->reason ?? 'General consultation' }}</p>
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <i class="far fa-calendar mr-2 text-blue-500"></i>
                                                    {{ $appointment->appointment_date->format('F d, Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="far fa-clock mr-2 text-blue-500"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full
                                        @if($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($appointment->status == 'confirmed') bg-green-100 text-green-700
                                        @else bg-blue-100 text-blue-700
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No upcoming appointments</h3>
                        <p class="text-gray-600 mb-6">Schedule your next visit with one of our healthcare providers</p>
                        <a href="{{ route('patient.appointments.create') }}" class="btn-primary inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Book Appointment
                        </a>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Medical Records Summary Section -->
    @if($stats['patient_record'])
    <section class="py-12 px-4 sm:px-6 lg:px-8 bg-white rounded-2xl shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-file-medical mr-3"></i>
                        Medical Records Summary
                    </h2>
                </div>
                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-3">
                            <i class="fas fa-tint text-red-500 text-xl"></i>
                            <h3 class="font-semibold text-gray-800">Blood Type</h3>
                        </div>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['patient_record']->blood_type ?? 'Not specified' }}</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-3">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                            <h3 class="font-semibold text-gray-800">Allergies</h3>
                        </div>
                        <p class="text-gray-800">{{ $stats['patient_record']->allergies ?? 'None recorded' }}</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-3">
                            <i class="fas fa-heartbeat text-red-500 text-xl"></i>
                            <h3 class="font-semibold text-gray-800">Medical Conditions</h3>
                        </div>
                        <p class="text-gray-800">{{ $stats['patient_record']->medical_conditions ?? 'None recorded' }}</p>
                    </div>
                    
                    @if($stats['patient_record']->emergency_contact_name)
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-3">
                            <i class="fas fa-phone text-blue-500 text-xl"></i>
                            <h3 class="font-semibold text-gray-800">Emergency Contact</h3>
                        </div>
                        <p class="font-semibold text-gray-800">{{ $stats['patient_record']->emergency_contact_name }}</p>
                        @if($stats['patient_record']->emergency_contact_phone)
                            <p class="text-sm text-gray-600 mt-1">{{ $stats['patient_record']->emergency_contact_phone }}</p>
                        @endif
                    </div>
                    @endif
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('patient.records') }}" 
                           class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold">
                            View Full Medical Records
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
