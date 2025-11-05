@extends('layouts.app')

@section('title', 'Appointments')
@section('page-title', 'Appointments Management')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Appointments Management</h1>
                <p class="text-gray-600">View and manage all appointments</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="search-appointments" placeholder="Search by patient name, doctor name, or date..." class="search-input">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto table-container">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($appointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Dr. {{ $appointment->doctor->full_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $appointment->appointment_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No appointments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/auto-search.js') }}"></script>
    <script>
        const appointmentsTable = @json($appointments);
        
        new AutoSearch({
            inputId: 'search-appointments',
            searchUrl: '/admin/appointments/search',
            onSearch: function(data) {
                const tbody = document.querySelector('tbody');
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No appointments found</td></tr>';
                    return;
                }
                
                tbody.innerHTML = data.map(appointment => {
                    const statusClass = appointment.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       appointment.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                       appointment.status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                       'bg-red-100 text-red-800';
                    const date = new Date(appointment.appointment_date);
                    const time = appointment.appointment_time ? new Date('2000-01-01T' + appointment.appointment_time).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'}) : 'N/A';
                    
                    return `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${appointment.patient?.full_name || 'N/A'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Dr. ${appointment.doctor?.full_name || 'N/A'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${date.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${time}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                    ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/admin/appointments/${appointment.id}" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        });
    </script>
    @endpush
@endsection
