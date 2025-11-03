@extends('layouts.app')

@section('title', 'Edit Ward')
@section('page-title', 'Edit Ward')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Edit Ward: {{ $ward->ward_name }}</h1>
                <p class="text-gray-600">Update ward information</p>
            </div>
            <a href="{{ route('admin.wards.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Wards
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.wards.update', $ward) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ward Name *</label>
                    <input type="text" name="ward_name" value="{{ old('ward_name', $ward->ward_name) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ward_name') border-red-500 @enderror"
                           placeholder="e.g., Ward A">
                    @error('ward_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ward Type *</label>
                    <select name="ward_type" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ward_type') border-red-500 @enderror">
                        <option value="">Select Type</option>
                        <option value="General" {{ old('ward_type', $ward->ward_type) == 'General' ? 'selected' : '' }}>General</option>
                        <option value="ICU" {{ old('ward_type', $ward->ward_type) == 'ICU' ? 'selected' : '' }}>ICU</option>
                        <option value="Emergency" {{ old('ward_type', $ward->ward_type) == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="Surgery" {{ old('ward_type', $ward->ward_type) == 'Surgery' ? 'selected' : '' }}>Surgery</option>
                        <option value="Pediatric" {{ old('ward_type', $ward->ward_type) == 'Pediatric' ? 'selected' : '' }}>Pediatric</option>
                        <option value="Maternity" {{ old('ward_type', $ward->ward_type) == 'Maternity' ? 'selected' : '' }}>Maternity</option>
                    </select>
                    @error('ward_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Floor *</label>
                    <input type="text" name="floor" value="{{ old('floor', $ward->floor) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('floor') border-red-500 @enderror"
                           placeholder="e.g., 1st Floor">
                    @error('floor')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $ward->capacity) }}" min="1" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('capacity') border-red-500 @enderror"
                           placeholder="Number of beds">
                    @error('capacity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="Active" {{ old('status', $ward->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Maintenance" {{ old('status', $ward->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Closed" {{ old('status', $ward->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.wards.index') }}" 
                   class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update Ward
                </button>
            </div>
        </form>
    </div>
@endsection

