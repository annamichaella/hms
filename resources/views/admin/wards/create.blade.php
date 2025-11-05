@extends('layouts.app')

@section('title', 'Add Ward')
@section('page-title', 'Add Ward')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Add New Ward</h1>
                <p class="text-gray-600">Create a new hospital ward</p>
            </div>
            @include('partials.back-button', ['href' => route('admin.wards.index'), 'label' => 'Back to Wards'])
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.wards.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ward Name *</label>
                    <input type="text" name="ward_name" value="{{ old('ward_name') }}" required 
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
                        <option value="General" {{ old('ward_type') == 'General' ? 'selected' : '' }}>General</option>
                        <option value="ICU" {{ old('ward_type') == 'ICU' ? 'selected' : '' }}>ICU</option>
                        <option value="Emergency" {{ old('ward_type') == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="Surgery" {{ old('ward_type') == 'Surgery' ? 'selected' : '' }}>Surgery</option>
                        <option value="Pediatric" {{ old('ward_type') == 'Pediatric' ? 'selected' : '' }}>Pediatric</option>
                        <option value="Maternity" {{ old('ward_type') == 'Maternity' ? 'selected' : '' }}>Maternity</option>
                    </select>
                    @error('ward_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Floor *</label>
                    <input type="text" name="floor" value="{{ old('floor') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('floor') border-red-500 @enderror"
                           placeholder="e.g., 1st Floor">
                    @error('floor')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity *</label>
                    <input type="number" name="capacity" min="1" value="{{ old('capacity') }}" required 
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
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.wards.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Save Ward
                </button>
            </div>
        </form>
    </div>
@endsection

