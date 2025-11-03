@extends('layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

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
        <a href="{{ route('patient.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('patient.dashboard') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-tachometer-alt mr-2.5 text-sm w-4 text-center"></i> Dashboard
        </a>
        <a href="{{ route('patient.appointments') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('patient.appointments') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-calendar-check mr-2.5 text-sm w-4 text-center"></i> My Appointments
        </a>
        <a href="{{ route('patient.records') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('patient.records') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-file-medical mr-2.5 text-sm w-4 text-center"></i> Medical Records
        </a>
        <a href="{{ route('patient.billing') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('patient.billing') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-credit-card mr-2.5 text-sm w-4 text-center"></i> Billing
        </a>
    </div>
    
    <style>
    .border-l-3 {
        border-left-width: 3px;
    }
    </style>
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('patient.appointments') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
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
                <h3 class="text-sm font-medium text-gray-500 mb-2">Doctor Information</h3>
                <div class="space-y-2">
                    <p class="text-gray-900"><strong>Name:</strong> {{ $appointment->doctor->full_name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><strong>Email:</strong> {{ $appointment->doctor->email ?? 'N/A' }}</p>
                    @if($appointment->doctor->specialization)
                    <p class="text-gray-600"><strong>Specialization:</strong> {{ $appointment->doctor->specialization }}</p>
                    @endif
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
    </div>
</div>
@endsection
