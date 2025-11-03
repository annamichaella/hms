@extends('layouts.app')

@section('title', 'Staff Assignments')
@section('page-title', 'Staff Assignments')

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
        <a href="{{ route('staff.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.dashboard') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-tachometer-alt mr-2.5 text-sm w-4 text-center"></i> Dashboard
        </a>
        <a href="{{ route('staff.appointments.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.appointments') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-calendar-check mr-2.5 text-sm w-4 text-center"></i> Appointments
        </a>
        <a href="{{ route('staff.billings.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.billings') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-credit-card mr-2.5 text-sm w-4 text-center"></i> Billing
        </a>
        <a href="{{ route('staff.wards.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.wards') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-bed mr-2.5 text-sm w-4 text-center"></i> Wards
        </a>
        <a href="{{ route('staff.assignments') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('staff.assignments') }} rounded-md transition-all duration-200 group">
            <i class="fas fa-user-tie mr-2.5 text-sm w-4 text-center"></i> Assignments
        </a>
    </div>
    
    <style>
    .border-l-3 {
        border-left-width: 3px;
    }
    </style>
@endsection

@section('content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800 tracking-tight">Staff Assignments</h1>
    <p class="text-gray-600 text-sm mt-1">Assign doctors, nurses, and rooms to appointments</p>
</div>

<!-- Messages -->
<div id="message-container"></div>

<!-- Search Bar -->
<div class="bg-white rounded-lg shadow-professional border border-gray-100 p-4 mb-6">
    <div class="flex items-center">
        <i class="fas fa-search text-gray-400 mr-3"></i>
        <input type="text" id="search-assignments" placeholder="Search by patient name, doctor name, or date..." 
               class="flex-1 px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
    </div>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-lg shadow-professional border border-gray-100 mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Appointment Assignments</h2>
            <div class="flex space-x-2">
                <button onclick="filterAppointments('all')" class="filter-btn active px-4 py-2 text-sm font-medium rounded-lg bg-blue-100 text-blue-700">
                    All ({{ $appointments->count() }})
                </button>
                <button onclick="filterAppointments('pending')" class="filter-btn px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    Pending
                </button>
                <button onclick="filterAppointments('confirmed')" class="filter-btn px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    Confirmed
                </button>
                <button onclick="filterAppointments('incomplete')" class="filter-btn px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    Incomplete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Appointments Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($appointments as $apt)
    <div class="appointment-card bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow" 
         data-appointment-id="{{ $apt->id }}" 
         data-status="{{ $apt->status }}"
         data-nurse-assigned="{{ $apt->nurse ? 'true' : 'false' }}"
         data-room-assigned="false">
        
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            @if($apt->patient)
                            {{ $apt->patient->fname }} {{ $apt->patient->lname }}
                            @else
                                Unknown Patient
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $apt->appointment_date->format('M d, Y') }} 
                            at {{ $apt->appointment_time->format('g:i A') }}
                        </p>
                    </div>
                </div>
                <span class="inline-block px-3 py-1 text-xs rounded-full font-medium
                    @if($apt->status === 'confirmed') bg-green-100 text-green-800
                    @elseif($apt->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($apt->status === 'cancelled') bg-red-100 text-red-800
                    @elseif($apt->status === 'completed') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($apt->status) }}
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="px-6 py-4">
            <!-- Doctor Info -->
            <div class="mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            @if($apt->doctor)
                            Dr. {{ $apt->doctor->fname }} {{ $apt->doctor->lname }}
                            @else
                                No doctor assigned
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">Assigned Doctor</p>
                    </div>
                </div>
            </div>

            <!-- Nurse Assignment -->
            <div class="mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 {{ $apt->nurse ? 'bg-pink-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                        <i class="fas fa-user-nurse {{ $apt->nurse ? 'text-pink-600' : 'text-gray-400' }} text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium {{ $apt->nurse ? 'text-gray-900' : 'text-gray-400' }}">
                            @if($apt->nurse)
                                {{ $apt->nurse->fname }} {{ $apt->nurse->lname }}
                            @else
                                Not assigned
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">Nurse</p>
                    </div>
                </div>
            </div>

            <!-- Room Assignment -->
            <div class="mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bed text-gray-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">
                            Not assigned
                        </p>
                        <p class="text-xs text-gray-500">Room</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <button data-id="{{ $apt->id }}" 
                        class="assign-btn flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>Assign
                </button>
                <button data-id="{{ $apt->id }}" 
                        class="view-btn px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 text-lg">No appointments found.</p>
            <p class="text-gray-400 text-sm">Appointments will appear here once they are created.</p>
        </div>
    </div>
    @endforelse
</div>

@push('scripts')
<script src="{{ asset('assets/js/auto-search.js') }}"></script>
<script>
    // Store original appointments data
    const originalAppointments = @json($appointments);
    
    new AutoSearch({
        inputId: 'search-assignments',
        searchUrl: '{{ route("staff.assignments") }}',
        onSearch: function(data) {
            const grid = document.querySelector('.grid');
            if (!grid) return;
            
            if (data.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full">
                        <div class="bg-white rounded-lg shadow-professional border border-gray-100 p-8 text-center">
                            <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500 text-lg">No appointments found.</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            // Filter appointment cards based on search results
            const appointmentIds = data.map(a => a.id);
            const cards = grid.querySelectorAll('.appointment-card');
            
            cards.forEach(card => {
                const appointmentId = parseInt(card.dataset.appointmentId);
                if (appointmentIds.includes(appointmentId)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush

<!-- Assignment Modal -->
<div id="assignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Assign Staff</h3>
            </div>
            
            <div class="p-6">
                <form id="assignmentForm">
                    <input type="hidden" id="modalAppointmentId" name="appointment_id">
                    
                    <!-- Assignment Type -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assignment Type</label>
                        <select id="assignmentType" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select assignment type...</option>
                            <option value="nurse">Assign Nurse</option>
                            <option value="doctor">Reassign Doctor</option>
                            <option value="room">Assign Room</option>
                        </select>
                    </div>
                    
                    <!-- Dynamic Content -->
                    <div id="assignmentContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    
                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="assignmentNotes" name="notes" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Add any notes about this assignment..."></textarea>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignmentModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Show messages
function showMessage(message, type) {
    const messageContainer = document.getElementById('message-container');
    const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    
    messageContainer.innerHTML = `
        <div class="border px-4 py-3 rounded mb-4 ${alertClass}">
            ${message}
        </div>
    `;
    
    setTimeout(function() {
        messageContainer.innerHTML = '';
    }, 5000);
}

// Add event listeners for assign and view buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.assign-btn')) {
        const btn = e.target.closest('.assign-btn');
        openAssignmentModal(btn.dataset.id);
    }
    if (e.target.closest('.view-btn')) {
        const btn = e.target.closest('.view-btn');
        viewAppointmentDetails(btn.dataset.id);
    }
});

// Open assignment modal
function openAssignmentModal(appointmentId) {
    document.getElementById('modalAppointmentId').value = appointmentId;
    document.getElementById('assignmentModal').classList.remove('hidden');
    document.getElementById('assignmentType').value = '';
    document.getElementById('assignmentContent').innerHTML = '';
    document.getElementById('assignmentNotes').value = '';
}

// Close assignment modal
function closeAssignmentModal() {
    document.getElementById('assignmentModal').classList.add('hidden');
}

// Handle assignment type change
document.getElementById('assignmentType').addEventListener('change', function() {
    const type = this.value;
    const content = document.getElementById('assignmentContent');
    
    if (type === 'nurse') {
        loadNurses(content);
    } else if (type === 'doctor') {
        loadDoctors(content);
    } else if (type === 'room') {
        loadRooms(content);
    } else {
        content.innerHTML = '';
    }
});

// Load nurses
function loadNurses(container) {
    fetch('{{ route("staff.assignments.nurses") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let html = '<div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-2">Select Nurse</label>';
            html += '<select name="nurse_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">';
            html += '<option value="">Choose a nurse...</option>';
            data.data.forEach(nurse => {
                html += `<option value="${nurse.id}">${nurse.fname} ${nurse.lname}</option>`;
            });
            html += '</select></div>';
            container.innerHTML = html;
        }
    });
}

// Load doctors
function loadDoctors(container) {
    fetch('{{ route("staff.assignments.doctors") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let html = '<div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor</label>';
            html += '<select name="doctor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">';
            html += '<option value="">Choose a doctor...</option>';
            data.data.forEach(doctor => {
                html += `<option value="${doctor.id}">Dr. ${doctor.fname} ${doctor.lname}</option>`;
            });
            html += '</select></div>';
            container.innerHTML = html;
        }
    });
}

// Load rooms
function loadRooms(container) {
    let html = '<div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>';
    html += '<input type="number" name="room_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter room number...">';
    html += '</div>';
    container.innerHTML = html;
}

// Handle form submission
document.getElementById('assignmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const appointmentId = document.getElementById('modalAppointmentId').value;
    const type = document.getElementById('assignmentType').value;
    const notes = document.getElementById('assignmentNotes').value;
    
    let formData = {
        appointment_id: appointmentId,
        notes: notes
    };
    
    if (type === 'nurse') {
        formData.assign_nurse = 1;
        formData.nurse_id = document.querySelector('select[name="nurse_id"]').value;
    } else if (type === 'doctor') {
        formData.reassign_doctor = 1;
        formData.doctor_id = document.querySelector('select[name="doctor_id"]').value;
    } else if (type === 'room') {
        formData.assign_room = 1;
        formData.room_id = document.querySelector('input[name="room_id"]').value;
    }
    
    fetch('{{ route("staff.assignments.assign") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            closeAssignmentModal();
            setTimeout(function() {
                location.reload();
            }, 1500);
        } else {
            showMessage(data.error, 'error');
        }
    });
});

// Filter appointments
function filterAppointments(filter) {
    const cards = document.querySelectorAll('.appointment-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-blue-100', 'text-blue-700');
        btn.classList.add('text-gray-600', 'hover:bg-gray-100');
    });
    
    event.target.classList.add('active', 'bg-blue-100', 'text-blue-700');
    event.target.classList.remove('text-gray-600', 'hover:bg-gray-100');
    
    // Filter cards
    cards.forEach(card => {
        const status = card.getAttribute('data-status');
        const nurseAssigned = card.getAttribute('data-nurse-assigned') === 'true';
        const roomAssigned = card.getAttribute('data-room-assigned') === 'true';
        
        let show = false;
        
        switch(filter) {
            case 'all':
                show = true;
                break;
            case 'pending':
                show = status === 'pending';
                break;
            case 'confirmed':
                show = status === 'confirmed';
                break;
            case 'incomplete':
                show = !nurseAssigned || !roomAssigned;
                break;
        }
        
        if (show) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// View appointment details
function viewAppointmentDetails(appointmentId) {
    fetch('{{ route("staff.assignments.details") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ appointment_id: appointmentId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const apt = data.data;
            let details = `Appointment Details:\n\n`;
            details += `Patient: ${apt.patient_fname} ${apt.patient_lname}\n`;
            details += `Doctor: Dr. ${apt.doctor_fname} ${apt.doctor_lname}\n`;
            details += `Nurse: ${apt.nurse_fname ? apt.nurse_fname + ' ' + apt.nurse_lname : 'Not assigned'}\n`;
            details += `Room: ${apt.room_id ? 'Room ' + apt.room_id : 'Not assigned'}\n`;
            details += `Date: ${apt.appointment_date}\n`;
            details += `Time: ${apt.appointment_time}\n`;
            details += `Status: ${apt.status}\n`;
            if (apt.reason) {
                details += `Reason: ${apt.reason}`;
            }
            alert(details);
        }
    });
}
</script>
@endsection
