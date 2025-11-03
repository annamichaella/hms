@extends('layouts.app')

@section('title', 'Ward Details')
@section('page-title', 'Ward Details')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $ward->ward_name }}</h1>
                <p class="text-gray-600">Ward details and bed management</p>
            </div>
            <a href="{{ route('admin.wards.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Wards
            </a>
        </div>
    </div>

    <!-- Ward Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ward Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Ward Name</label>
                    <p class="text-sm text-gray-900">{{ $ward->ward_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Ward Type</label>
                    <p class="text-sm text-gray-900">{{ $ward->ward_type }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Floor</label>
                    <p class="text-sm text-gray-900">{{ $ward->floor ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Capacity</label>
                    <p class="text-sm text-gray-900">{{ $ward->capacity }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                        @if($ward->status == 'Active') bg-green-100 text-green-800
                        @elseif($ward->status == 'Maintenance') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $ward->status }}
                    </span>
                </div>
                @if($ward->description)
                <div class="md:col-span-2 lg:col-span-4">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
                    <p class="text-sm text-gray-900">{{ $ward->description }}</p>
                </div>
                @endif
            </div>
            <div class="mt-6 flex space-x-2">
                <a href="{{ route('admin.wards.edit', $ward) }}" class="flex-1 text-center bg-blue-50 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 text-sm">
                    Edit Ward
                </a>
                <button onclick="deleteWard({{ $ward->id }})" class="flex-1 text-center bg-red-50 text-red-600 px-3 py-2 rounded-lg hover:bg-red-100 text-sm">
                    Delete Ward
                </button>
            </div>
            
            <form id="delete-ward-form-{{ $ward->id }}" action="{{ route('admin.wards.destroy', $ward) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Beds Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Beds ({{ $ward->beds->count() }})</h2>
            <a href="{{ route('admin.wards.beds', $ward) }}" class="text-sm text-blue-600 hover:text-blue-900">
                Manage Beds
            </a>
        </div>
        <div class="overflow-x-auto table-container">
        <table class="w-full min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bed Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ward->beds as $bed)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $bed->bed_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $bed->bed_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    @if($bed->status == 'Available') bg-green-100 text-green-800
                                    @elseif($bed->status == 'Occupied') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $bed->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($bed->patient)
                                    {{ $bed->patient->full_name }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No beds found in this ward
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function deleteWard(id) {
            confirmAction('Are you sure you want to delete this ward? This action cannot be undone.', function() {
                document.getElementById('delete-ward-form-' + id).submit();
            });
        }
    </script>
    @endpush
@endsection

