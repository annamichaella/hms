@extends('layouts.app')

@section('title', isset($record) ? 'Patient Record' : 'Patient Information')
@section('page-title', isset($record) ? 'Patient Record' : 'Patient Information')

@section('sidebar')
    @php
        $user = Auth::user();
        $role = $user->role;
    @endphp
    
    @if($role === 'doctor')
        <div class="px-4 space-y-2">
            <a href="{{ route('doctor.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
            </a>
            <a href="{{ route('doctor.appointments') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-calendar-check mr-3"></i> My Appointments
            </a>
            <a href="{{ route('doctor.patients') }}" class="flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg">
                <i class="fas fa-user-injured mr-3"></i> My Patients
            </a>
            <a href="{{ route('doctor.schedule') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-calendar-alt mr-3"></i> My Schedule
            </a>
        </div>
    @elseif($role === 'nurse')
        <div class="px-4 space-y-2">
            <a href="{{ route('nurse.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
            </a>
            <a href="{{ route('nurse.patients') }}" class="flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg">
                <i class="fas fa-user-injured mr-3"></i> Patients
            </a>
            <a href="{{ route('nurse.wards') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-bed mr-3"></i> Wards
            </a>
            <a href="{{ route('nurse.ward-assignments') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-procedures mr-3"></i> Ward Assignments
            </a>
        </div>
    @elseif($role === 'admin')
        <div class="px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-home mr-3"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-users mr-3"></i> Users Management
            </a>
            <a href="{{ route('admin.records.index') }}" class="flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg">
                <i class="fas fa-user-injured mr-3"></i> Patient Records
            </a>
            <a href="{{ route('admin.appointments.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-calendar-check mr-3"></i> Appointments
            </a>
            <a href="{{ route('admin.wards.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-bed mr-3"></i> Ward Management
            </a>
            <a href="{{ route('admin.billings.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-credit-card mr-3"></i> Billing
            </a>
        </div>
    @endif
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Patient Medical Record</h1>
                <p class="text-gray-600">
                    @if(isset($record))
                        Medical information for {{ $record->user->full_name }}
                    @elseif(isset($patient))
                        Medical information for {{ $patient->full_name }}
                    @endif
                </p>
            </div>
            @if(Auth::user()->role === 'doctor')
                <a href="{{ route('doctor.patients') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Patients
                </a>
            @elseif(Auth::user()->role === 'nurse')
                <a href="{{ route('nurse.patients') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Patients
                </a>
            @elseif(Auth::user()->role === 'admin')
                <a href="{{ route('admin.records.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Records
                </a>
            @endif
        </div>
    </div>

    @if(isset($message))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if(isset($record) && $record)
        <!-- Patient Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Patient Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                        <p class="text-sm text-gray-900">{{ $record->user->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $record->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                        <p class="text-sm text-gray-900">{{ $record->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Address</label>
                        <p class="text-sm text-gray-900">{{ $record->user->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Medical Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Blood Type</label>
                        <p class="text-sm text-gray-900">{{ $record->blood_type ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Emergency Contact Name</label>
                        <p class="text-sm text-gray-900">{{ $record->emergency_contact_name ?? 'Not specified' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Emergency Contact Phone</label>
                        <p class="text-sm text-gray-900">{{ $record->emergency_contact_phone ?? 'Not specified' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Allergies</label>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $record->allergies ?? 'None recorded' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Medical Conditions</label>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $record->medical_conditions ?? 'None recorded' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif(isset($patient))
        <!-- Patient Information (No Records) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Patient Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                        <p class="text-sm text-gray-900">{{ $patient->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $patient->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                        <p class="text-sm text-gray-900">{{ $patient->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Address</label>
                        <p class="text-sm text-gray-900">{{ $patient->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-12 text-center">
                <i class="fas fa-file-medical text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Medical Records Found</h3>
                <p class="text-gray-600">This patient does not have any medical records in the system yet.</p>
            </div>
        </div>
    @endif
@endsection

