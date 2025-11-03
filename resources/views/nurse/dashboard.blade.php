@extends('layouts.app')

@section('title', 'Nurse Dashboard')
@section('page-title', 'Nurse Dashboard')

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
        <a href="{{ route('nurse.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('nurse.dashboard') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-tachometer-alt mr-2.5 text-sm w-4 text-center"></i> Dashboard
        </a>
        <a href="{{ route('nurse.patients') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('nurse.patients') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-user-injured mr-2.5 text-sm w-4 text-center"></i> Patients
        </a>
        <a href="{{ route('nurse.wards') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('nurse.wards') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-bed mr-2.5 text-sm w-4 text-center"></i> Wards
        </a>
        <a href="{{ route('nurse.ward-assignments') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('nurse.ward-assignments') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-procedures mr-2.5 text-sm w-4 text-center"></i> Ward Assignments
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
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-user-nurse text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-green-900">Welcome back, {{ Auth::user()->full_name }}!</h2>
                <p class="text-green-700">Here's an overview of ward management and patient assignments.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Wards</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total_wards'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-bed text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Beds</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total_beds'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-user-injured text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Occupied Beds</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['occupied_beds'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-bed text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Available Beds</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['available_beds'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Admissions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Recent Admissions</h3>
                <a href="{{ route('nurse.ward-assignments') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                    View All
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($stats['recent_admissions']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ward</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($stats['recent_admissions'] as $admission)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $admission->patient->full_name ?? 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $admission->bed->ward->ward_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $admission->bed->bed_number ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $admission->admission_date->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $admission->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-bed text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">No recent admissions</p>
                </div>
            @endif
        </div>
    </div>
@endsection
