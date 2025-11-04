@extends('layouts.patient')

@section('title', 'My Appointments')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">My Appointments</h1>
                <p class="text-gray-600">View and manage your scheduled appointments</p>
            </div>
            <a href="{{ route('patient.appointments.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 shadow-lg hover-soft">
                <i class="fas fa-plus mr-2"></i>
                Book New Appointment
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="patient-card p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-search text-gray-400 mr-3 text-lg"></i>
            <input type="text" id="search-appointments" placeholder="Search by doctor name or date..." 
                   class="flex-1 px-4 py-3 border-0 bg-transparent focus:outline-none focus:ring-0 text-gray-700 placeholder-gray-400">
        </div>
    </div>

    <!-- Appointments List -->
    @if($appointments->count() > 0)
        <div class="space-y-4">
            @foreach($appointments as $appointment)
                <div class="patient-card p-6 hover-soft">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
                                <i class="fas fa-calendar-check text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        Dr. {{ $appointment->doctor->full_name ?? 'Unknown Doctor' }}
                                    </h3>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($appointment->status == 'confirmed') bg-green-100 text-green-700
                                        @elseif($appointment->status == 'completed') bg-blue-100 text-blue-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                @if($appointment->reason)
                                    <p class="text-gray-600 mb-3">{{ $appointment->reason }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="far fa-calendar mr-2 text-blue-500"></i>
                                        {{ $appointment->appointment_date->format('F d, Y') }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="far fa-clock mr-2 text-blue-500"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if($appointment->status == 'pending')
                                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium transition-colors">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('patient.appointments.show', $appointment) }}" 
                               class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium transition-colors">
                                <i class="fas fa-eye mr-2"></i>View
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="patient-card p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No appointments yet</h3>
            <p class="text-gray-600 mb-8">Schedule your first appointment with one of our healthcare providers</p>
            <a href="{{ route('patient.appointments.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 shadow-lg hover-soft">
                <i class="fas fa-plus mr-2"></i>
                Book Your First Appointment
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script src="{{ asset('assets/js/auto-search.js') }}"></script>
<script>
    new AutoSearch({
        inputId: 'search-appointments',
        searchUrl: '/patient/appointments/search',
        onSearch: function(data) {
            const container = document.querySelector('.space-y-4');
            if (!container) return;
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="patient-card p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">No appointments found</h3>
                        <p class="text-gray-600 mb-8">Try adjusting your search terms</p>
                        <a href="{{ route('patient.appointments.create') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Book Appointment
                        </a>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = data.map(appointment => {
                const statusClass = appointment.status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                   appointment.status === 'confirmed' ? 'bg-green-100 text-green-700' :
                                   appointment.status === 'completed' ? 'bg-blue-100 text-blue-700' :
                                   'bg-red-100 text-red-700';
                const date = new Date(appointment.appointment_date);
                const time = appointment.appointment_time ? new Date('2000-01-01T' + appointment.appointment_time).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'}) : 'N/A';
                
                return `
                    <div class="patient-card p-6 hover-soft">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-start space-x-4 flex-1">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-md flex-shrink-0">
                                    <i class="fas fa-calendar-check text-white text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-800">Dr. ${appointment.doctor?.full_name || 'Unknown Doctor'}</h3>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                            ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
                                        </span>
                                    </div>
                                    ${appointment.reason ? `<p class="text-gray-600 mb-3">${appointment.reason}</p>` : ''}
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i class="far fa-calendar mr-2 text-blue-500"></i>
                                            ${date.toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'})}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="far fa-clock mr-2 text-blue-500"></i>
                                            ${time}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    });
</script>
@endpush
@endsection
