@extends('layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('sidebar')
    <div class="px-4 space-y-2">
        <a href="{{ route('doctor.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
        </a>
        <a href="{{ route('doctor.appointments') }}" class="flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg">
            <i class="fas fa-calendar-check mr-3"></i>
            My Appointments
        </a>
        <a href="{{ route('doctor.patients') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-user-injured mr-3"></i>
            My Patients
        </a>
        <a href="{{ route('doctor.schedule') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-calendar-alt mr-3"></i>
            My Schedule
        </a>
    </div>
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('doctor.appointments') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Appointment Details</h2>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Patient Information</h3>
                <div class="space-y-2">
                    <p class="text-gray-900"><strong>Name:</strong> {{ $appointment->patient->full_name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><strong>Email:</strong> {{ $appointment->patient->email ?? 'N/A' }}</p>
                    <p class="text-gray-600"><strong>Phone:</strong> {{ $appointment->patient->phone ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Appointment Information</h3>
                <div class="space-y-2">
                    <p class="text-gray-900"><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y') }}</p>
                    <p class="text-gray-600"><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                    <p class="text-gray-600">
                        <strong>Status:</strong> 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        @if($appointment->reason)
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Reason for Visit</h3>
            <p class="text-gray-900">{{ $appointment->reason }}</p>
        </div>
        @endif
        
        @if($appointment->nurse)
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Assigned Nurse</h3>
            <p class="text-gray-900">{{ $appointment->nurse->full_name ?? 'N/A' }}</p>
        </div>
        @endif
        
        <div class="mt-6 flex gap-4">
            <form action="{{ route('doctor.appointments.update', $appointment) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700">Update Status:</label>
                    <select name="status" onchange="this.form.submit()" class="border rounded px-3 py-2 text-sm">
                        <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
