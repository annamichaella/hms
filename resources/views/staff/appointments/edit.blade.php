@extends('layouts.app')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@php
    $currentRoute = request()->route()->getName();
    $isActive = function($route) use ($currentRoute) {
        return strpos($currentRoute, $route) === 0 
            ? 'text-blue-700 bg-gradient-to-r from-blue-50 to-blue-100 border-l-3 border-blue-600 font-medium shadow-sm' 
            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
    };
@endphp

@section('sidebar')
    <div class="px-2 space-y-0.5">
        <a href="{{ route('staff.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.dashboard') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-tachometer-alt mr-2.5 text-sm w-4 text-center"></i> Dashboard
        </a>
        <a href="{{ route('staff.appointments.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.appointments') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-calendar-check mr-2.5 text-sm w-4 text-center"></i> Appointments
        </a>
        <a href="{{ route('staff.billings.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.billings') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-credit-card mr-2.5 text-sm w-4 text-center"></i> Billing
        </a>
        <a href="{{ route('staff.wards.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.wards') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-bed mr-2.5 text-sm w-4 text-center"></i> Wards
        </a>
        <a href="{{ route('staff.assignments') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.assignments') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-user-tie mr-2.5 text-sm w-4 text-center"></i> Assignments
        </a>
    </div>
    
    <style>
    .border-l-3 {
        border-left-width: 3px;
    }
    </style>
@endsection

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Edit Appointment</h1>
            <p class="text-gray-600 text-sm mt-1">Update appointment details</p>
        </div>
        <a href="{{ route('staff.appointments.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Appointments
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <strong>Please fix the following errors:</strong>
                        <ul class="list-disc list-inside mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-6">
        <form action="{{ route('staff.appointments.update', $appointment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1.5">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="patient_id" required 
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('patient_id') border-red-500 @enderror">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1.5">Doctor <span class="text-red-500">*</span></label>
                    <select name="doctor_id" id="doctor_id" required 
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('doctor_id') border-red-500 @enderror">
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->full_name }}
                                @if($doctor->specialization) - {{ $doctor->specialization }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-1.5">Appointment Date <span class="text-red-500">*</span></label>
                    <input type="date" name="appointment_date" id="appointment_date" 
                           value="{{ old('appointment_date', $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '') }}" 
                           required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('appointment_date') border-red-500 @enderror">
                    @error('appointment_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-1.5">Appointment Time <span class="text-red-500">*</span></label>
                    <input type="time" name="appointment_time" id="appointment_time" 
                           value="{{ old('appointment_time', $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') : '') }}" 
                           required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('appointment_time') border-red-500 @enderror">
                    @error('appointment_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required 
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1.5">Reason for Visit</label>
                <textarea name="reason" id="reason" rows="3" 
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('reason') border-red-500 @enderror"
                          placeholder="Enter reason for appointment (optional)...">{{ old('reason', $appointment->reason) }}</textarea>
                @error('reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('staff.appointments.index') }}" class="px-4 py-2.5 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>Update Appointment
                </button>
            </div>
        </form>
    </div>
@endsection

