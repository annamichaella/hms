@extends('layouts.app')

@section('title', 'Create New Bill')
@section('page-title', 'Create New Bill')

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
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Create New Bill</h1>
            <p class="text-gray-600 text-sm mt-1">Fill in the details to create a new billing record</p>
        </div>
        @include('partials.back-button', ['href' => route('staff.billings.index'), 'label' => 'Back to Billings'])
    </div>

    <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-6">
        <form action="{{ route('staff.billings.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-1.5">Patient Name <span class="text-red-500">*</span></label>
                    <input type="text" name="patient_name" id="patient_name" value="{{ old('patient_name') }}" 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('patient_name') border-red-500 @enderror" required>
                    @error('patient_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="doctor_name" class="block text-sm font-medium text-gray-700 mb-1.5">Doctor Name</label>
                    <input type="text" name="doctor_name" id="doctor_name" value="{{ old('doctor_name') }}" 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('doctor_name') border-red-500 @enderror">
                    @error('doctor_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="service" class="block text-sm font-medium text-gray-700 mb-1.5">Service <span class="text-red-500">*</span></label>
                    <input type="text" name="service" id="service" value="{{ old('service') }}" 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('service') border-red-500 @enderror" required>
                    @error('service')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount') }}" 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('amount') border-red-500 @enderror" required>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="billing_date" class="block text-sm font-medium text-gray-700 mb-1.5">Billing Date <span class="text-red-500">*</span></label>
                    <input type="date" name="billing_date" id="billing_date" value="{{ old('billing_date', date('Y-m-d')) }}" 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('billing_date') border-red-500 @enderror" required>
                    @error('billing_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1.5">Due Date</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('staff.billings.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Create Bill
                </button>
            </div>
        </form>
    </div>
@endsection

