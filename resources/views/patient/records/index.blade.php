@extends('layouts.app')

@section('title', 'Medical Records')
@section('page-title', 'My Medical Records')

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
        <h1 class="text-xl font-bold text-gray-800">My Medical Records</h1>
        <p class="text-gray-600">View your medical history and records</p>
    </div>

    @forelse($records as $record)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                    <p class="text-gray-900">{{ $record->blood_type ?? 'Not specified' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                    <p class="text-gray-900">{{ $record->allergies ?? 'None recorded' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Medical Conditions</label>
                    <p class="text-gray-900">{{ $record->medical_conditions ?? 'None recorded' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact</label>
                    <p class="text-gray-900">{{ $record->emergency_contact_name ?? 'Not specified' }}</p>
                    @if($record->emergency_contact_phone)
                        <p class="text-sm text-gray-600">{{ $record->emergency_contact_phone }}</p>
                    @endif
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500">
                Last updated: {{ $record->updated_at->format('M d, Y') }}
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-file-medical text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-500 mb-4">No medical records found</p>
            <p class="text-sm text-gray-400">Contact your healthcare provider to add your medical information.</p>
        </div>
    @endforelse
@endsection
