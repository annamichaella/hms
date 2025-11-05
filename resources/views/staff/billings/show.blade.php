@extends('layouts.app')

@section('title', 'Bill Details')
@section('page-title', 'Bill Details')

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
    <div class="mb-6">
        <a href="{{ route('staff.billings.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
            <i class="fas fa-arrow-left mr-2"></i>Back to Billings
        </a>
        <h1 class="text-xl font-bold text-gray-800 tracking-tight">Bill Details</h1>
    </div>

    <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Patient Name</label>
                <p class="text-sm text-gray-900">{{ $billing->patient_name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Doctor Name</label>
                <p class="text-sm text-gray-900">{{ $billing->doctor_name ?: 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Service</label>
                <p class="text-sm text-gray-900">{{ $billing->service }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Amount</label>
                <p class="text-sm font-semibold text-gray-900">₱{{ number_format($billing->amount, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Billing Date</label>
                <p class="text-sm text-gray-900">{{ $billing->billing_date->format('M d, Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Due Date</label>
                <p class="text-sm text-gray-900">{{ $billing->due_date ? $billing->due_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <span class="px-2 py-1 text-xs font-medium rounded-full
                    @if($billing->status == 'paid') bg-green-100 text-green-800
                    @elseif($billing->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($billing->status == 'partial') bg-blue-100 text-blue-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($billing->status) }}
                </span>
            </div>
            @if($billing->notes)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                <p class="text-sm text-gray-900">{{ $billing->notes }}</p>
            </div>
            @endif
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('staff.billings.index') }}" class="px-4 py-2.5 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                Back
            </a>
            <a href="{{ route('staff.billings.edit', $billing) }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all duration-200 font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Bill
            </a>
        </div>
    </div>
@endsection

