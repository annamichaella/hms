@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('sidebar')
    <div class="px-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 bg-blue-50 rounded-lg">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-users mr-3"></i>
            Users
        </a>
        <a href="{{ route('admin.appointments.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-calendar-check mr-3"></i>
            Appointments
        </a>
        <a href="{{ route('admin.wards.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-bed mr-3"></i>
            Wards
        </a>
        <a href="{{ route('admin.billings.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-credit-card mr-3"></i>
            Billing
        </a>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Appointments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-credit-card text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Billing Records</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_billings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-bed text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Wards</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_wards'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Appointments</h3>
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
                                        {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}
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
                    <p class="text-gray-500 text-center py-4">No recent appointments</p>
                @endif
            </div>
        </div>

        <!-- Users by Role -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Users by Role</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($stats['users_by_role'] as $role => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3
                                    @if($role == 'admin') bg-red-500
                                    @elseif($role == 'doctor') bg-blue-500
                                    @elseif($role == 'nurse') bg-green-500
                                    @elseif($role == 'staff') bg-yellow-500
                                    @else bg-gray-500
                                    @endif">
                                </div>
                                <span class="text-sm font-medium text-gray-900 capitalize">{{ $role }}s</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection



