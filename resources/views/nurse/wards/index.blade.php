@extends('layouts.app')

@section('title', 'Wards')
@section('page-title', 'Wards')

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
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Wards</h1>
                <p class="text-gray-600 text-sm mt-1">View hospital wards and bed availability</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($wards as $ward)
            <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ $ward->ward_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $ward->ward_type }} - Floor {{ $ward->floor }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($ward->status == 'Active') bg-green-100 text-green-800
                        @elseif($ward->status == 'Maintenance') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $ward->status }}
                    </span>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Beds:</span>
                        <span class="font-medium">{{ $ward->total_beds ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Available:</span>
                        <span class="font-medium text-green-600">{{ $ward->available_beds ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Occupied:</span>
                        <span class="font-medium text-red-600">{{ $ward->occupied_beds ?? 0 }}</span>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('nurse.ward-assignments') }}?ward={{ $ward->id }}" 
                       class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-8 text-center">
                    <i class="fas fa-bed text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">No wards available</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection

