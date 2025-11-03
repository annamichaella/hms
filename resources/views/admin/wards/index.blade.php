@extends('layouts.app')

@section('title', 'Ward Management')
@section('page-title', 'Ward Management')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Ward Management</h1>
                <p class="text-gray-600">Manage hospital wards and bed assignments</p>
            </div>
            <a href="{{ route('admin.wards.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add Ward
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($wards as $ward)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $ward->ward_name }}</h3>
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
                <div class="flex space-x-2">
                    <a href="{{ route('admin.wards.show', $ward) }}" class="flex-1 text-center bg-blue-50 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 text-sm">
                        View Details
                    </a>
                    <a href="{{ route('admin.wards.edit', $ward) }}" class="flex-1 text-center bg-gray-50 text-gray-600 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm">
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500">No wards found</p>
            </div>
        @endforelse
    </div>
@endsection
