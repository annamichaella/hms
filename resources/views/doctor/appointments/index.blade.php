@extends('layouts.app')

@section('title', 'My Appointments')
@section('page-title', 'My Appointments')

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
        <a href="{{ route('doctor.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('doctor.dashboard') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-tachometer-alt mr-2.5 text-sm w-4 text-center"></i> Dashboard
        </a>
        <a href="{{ route('doctor.appointments') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('doctor.appointments') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-calendar-check mr-2.5 text-sm w-4 text-center"></i> My Appointments
        </a>
        <a href="{{ route('doctor.patients') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('doctor.patients') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-user-injured mr-2.5 text-sm w-4 text-center"></i> My Patients
        </a>
        <a href="{{ route('doctor.schedule') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('doctor.schedule') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-calendar-alt mr-2.5 text-sm w-4 text-center"></i> My Schedule
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
        <h1 class="text-xl font-bold text-gray-800">My Appointments</h1>
        <p class="text-gray-600">View, manage, and update your appointments</p>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-search text-gray-400 mr-3"></i>
            <input type="text" id="search-appointments" placeholder="Search by patient name or date..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto table-container">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $appointment->appointment_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($appointment->reason ?? 'N/A', 30) }}
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
                            <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <form action="{{ route('doctor.appointments.update', $appointment) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs border rounded px-2 py-1">
                                    <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
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
        new AutoSearch({
            inputId: 'search-appointments',
            searchUrl: '/doctor/appointments/search',
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${date.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${time}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                ${appointment.reason && appointment.reason.length > 30 ? appointment.reason.substring(0, 30) + '...' : (appointment.reason || 'N/A')}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                    ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/doctor/appointments/${appointment.id}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <form action="/doctor/appointments/${appointment.id}" method="POST" class="inline">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <select name="status" onchange="this.form.submit()" class="text-xs border rounded px-2 py-1">
                                        <option value="pending" ${appointment.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="confirmed" ${appointment.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                        <option value="completed" ${appointment.status === 'completed' ? 'selected' : ''}>Completed</option>
                                        <option value="cancelled" ${appointment.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        });
    </script>
    @endpush
@endsection
