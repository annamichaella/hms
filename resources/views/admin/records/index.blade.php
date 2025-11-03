@extends('layouts.app')

@section('title', 'Patient Records')
@section('page-title', 'Patient Records')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Patient Records</h1>
                <p class="text-sm text-gray-600 mt-1">Manage patient medical records</p>
            </div>
            <a href="{{ route('admin.records.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all duration-200 font-medium">
                <i class="fas fa-plus mr-2"></i>Add Record
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-search text-gray-400 mr-3"></i>
            <input type="text" id="search-records" placeholder="Search by patient name..." 
                   class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-professional border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto table-container">
        <table class="w-full min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Blood Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Allergies</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medical Conditions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($records as $record)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $record->user->full_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $record->blood_type ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($record->allergies ?? 'None', 50) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($record->medical_conditions ?? 'None', 50) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.records.show', $record) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/auto-search.js') }}"></script>
    <script>
        const searchInput = document.getElementById('search-records');
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const keyword = this.value.trim();
            
            searchTimeout = setTimeout(() => {
                fetch('{{ route("admin.records.search") }}?search_name=' + encodeURIComponent(keyword), {
                    headers: {'Accept': 'application/json'}
                })
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('tbody');
                    if (!data.success || data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No records found</td></tr>';
                        return;
                    }
                    
                    tbody.innerHTML = data.data.map(record => {
                        return `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${record.user?.full_name || 'N/A'}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${record.blood_type || 'N/A'}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    ${(record.allergies && record.allergies.length > 50) ? record.allergies.substring(0, 50) + '...' : (record.allergies || 'None')}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    ${(record.medical_conditions && record.medical_conditions.length > 50) ? record.medical_conditions.substring(0, 50) + '...' : (record.medical_conditions || 'None')}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="/admin/records/${record.id}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
            }, 300);
        });
    </script>
    @endpush
@endsection
