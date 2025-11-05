@extends('layouts.app')

@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

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
    <!-- Welcome Message -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-user-tie text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-yellow-900">Welcome back, {{ Auth::user()->full_name }}!</h2>
                <p class="text-yellow-700">Manage appointments, billing, and assignments from here.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Appointments</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['pending_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-credit-card text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bills</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total_bills'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Bills</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_bills'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">Recent Appointments</h3>
                    <a href="{{ route('staff.appointments.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($stats['recent_appointments']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_appointments'] as $appointment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $appointment->patient->full_name ?? 'Unknown Patient' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        with Dr. {{ $appointment->doctor->full_name ?? 'Unknown Doctor' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No recent appointments</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Bills -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">Recent Bills</h3>
                    <a href="{{ route('staff.billings.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($stats['recent_bills']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_bills'] as $bill)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $bill->patient_name }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $bill->service }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $bill->billing_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">₱{{ number_format($bill->amount, 2) }}</p>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($bill->status == 'paid') bg-green-100 text-green-800
                                        @elseif($bill->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($bill->status == 'partial') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-invoice text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No recent bills</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
