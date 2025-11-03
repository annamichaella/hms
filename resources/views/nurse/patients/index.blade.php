@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')

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
        <h1 class="text-xl font-bold text-gray-800 tracking-tight">Patients</h1>
        <p class="text-gray-600 text-sm mt-1">View and manage patients</p>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-search text-gray-400 mr-3"></i>
            <input type="text" id="search-patients" placeholder="Search by patient name, email, or phone..." 
                   class="flex-1 px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-professional border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto table-container">
        <table class="w-full min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($patients as $patient)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $patient->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $patient->phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($patient->address ?? 'N/A', 30) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @php
                                $patientRecord = \App\Models\PatientRecord::where('user_id', $patient->id)->first();
                            @endphp
                            @if($patientRecord)
                                <a href="{{ route('nurse.patients.records', $patient->id) }}" class="text-blue-600 hover:text-blue-900">View Records</a>
                            @else
                                <span class="text-gray-400">No Records</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No patients found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/auto-search.js') }}"></script>
    <script>
        new AutoSearch({
            inputId: 'search-patients',
            searchUrl: '/nurse/patients/search',
            onSearch: function(data) {
                const tbody = document.querySelector('tbody');
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No patients found</td></tr>';
                    return;
                }
                
                tbody.innerHTML = data.map(patient => {
                    const address = patient.address ? (patient.address.length > 30 ? patient.address.substring(0, 30) + '...' : patient.address) : 'N/A';
                    const hasRecord = patient.has_record || false;
                    
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${patient.full_name || 'N/A'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${patient.email || 'N/A'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${patient.phone || 'N/A'}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                ${address}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                ${hasRecord ? 
                                    `<a href="/nurse/patients/${patient.id}/records" class="text-blue-600 hover:text-blue-800 transition-colors">View Records</a>` :
                                    `<span class="text-gray-400">No Records</span>`
                                }
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        });
    </script>
    @endpush
@endsection
