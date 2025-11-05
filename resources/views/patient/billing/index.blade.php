@extends('layouts.patient')

@section('title', 'Billing')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Bills & Payments</h1>
        <p class="text-gray-600">View your billing history and manage payments</p>
    </div>

    @if($bills->count() > 0)
        <!-- Billing Summary Cards -->
        <div class="py-8 px-4 sm:px-6 lg:px-8 bg-white rounded-2xl shadow-sm mb-6">
            @php
                $totalBills = $bills->count();
                $paidBills = $bills->where('status', 'paid')->count();
                $pendingBills = $bills->where('status', 'pending')->count();
                $totalAmount = $bills->sum('amount');
                $paidAmount = $bills->where('status', 'paid')->sum('amount');
                $pendingAmount = $bills->where('status', 'pending')->sum('amount');
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="patient-card p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 mb-1">Total Bills</p>
                        <p class="text-2xl font-bold text-gray-800">₱{{ number_format($totalAmount, 2) }}</p>
                        <p class="text-xs text-gray-600 mt-2">{{ $totalBills }} bill(s)</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-file-invoice-dollar text-white"></i>
                    </div>
                </div>
            </div>

            <div class="patient-card p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 mb-1">Paid</p>
                        <p class="text-2xl font-bold text-gray-800">₱{{ number_format($paidAmount, 2) }}</p>
                        <p class="text-xs text-gray-600 mt-2">{{ $paidBills }} bill(s)</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
            </div>

            <div class="patient-card p-6 bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700 mb-1">Pending</p>
                        <p class="text-2xl font-bold text-gray-800">₱{{ number_format($pendingAmount, 2) }}</p>
                        <p class="text-xs text-gray-600 mt-2">{{ $pendingBills }} bill(s)</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- Bills List -->
        <div class="space-y-4 py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 rounded-2xl mt-6">
            @foreach($bills as $bill)
                <div class="patient-card p-6 hover-soft">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
                                <i class="fas fa-receipt text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $bill->service }}</h3>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($bill->status == 'paid') bg-green-100 text-green-700
                                        @elseif($bill->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($bill->status == 'partial') bg-blue-100 text-blue-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </div>
                                @if($bill->doctor_name)
                                    <p class="text-gray-600 mb-2">
                                        <i class="fas fa-user-md text-blue-500 mr-2"></i>
                                        Dr. {{ $bill->doctor_name }}
                                    </p>
                                @endif
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="far fa-calendar mr-2 text-blue-500"></i>
                                        Billed: {{ $bill->billing_date->format('M d, Y') }}
                                    </span>
                                    @if($bill->due_date)
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar-check mr-2 text-blue-500"></i>
                                            Due: {{ $bill->due_date->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-800">₱{{ number_format($bill->amount, 2) }}</p>
                                <p class="text-xs text-gray-500">Total Amount</p>
                            </div>
                            @if($bill->status == 'pending')
                                <button class="btn-primary inline-flex items-center">
                                    <i class="fas fa-credit-card mr-2"></i>Pay Now
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-receipt text-gray-400 text-4xl"></i>
            </div>
            <h3>No bills found</h3>
            <p>Your billing information will appear here once you have any medical services or appointments.</p>
        </div>
    @endif
</div>
@endsection
