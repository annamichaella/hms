@extends('layouts.app')

@section('title', 'Ward Assignments')
@section('page-title', 'Ward Assignments')

@section('sidebar')
<nav class="space-y-1">
    <a href="{{ route('nurse.dashboard') }}" class="flex items-center px-3 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white">
        <i class="fas fa-home mr-3 text-sm"></i> Dashboard
    </a>
    <a href="{{ route('nurse.patients') }}" class="flex items-center px-3 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white">
        <i class="fas fa-user-injured mr-3 text-sm"></i> My Patients
    </a>
    <a href="{{ route('nurse.ward-assignments') }}" class="flex items-center px-3 py-2 rounded text-white bg-pink-600">
        <i class="fas fa-bed mr-3 text-sm"></i> Ward Assignments
    </a>
</nav>
@endsection

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Ward Assignments</h1>
            <p class="text-gray-600">Manage patient bed assignments and discharges</p>
        </div>
        <button onclick="openAssignModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Assign Patient
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-bed text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Available Beds</p>
                <p class="text-2xl font-bold text-gray-900" id="availableBeds">{{ $stats['available_beds'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
                <i class="fas fa-user-injured text-red-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Occupied Beds</p>
                <p class="text-2xl font-bold text-gray-900" id="occupiedBeds">{{ $stats['occupied_beds'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i class="fas fa-hospital text-blue-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Beds</p>
                <p class="text-2xl font-bold text-gray-900" id="totalBeds">{{ $stats['total_beds'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Beds Table -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Bed Status</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ward</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="bedsTableBody" class="bg-white divide-y divide-gray-200">
                @forelse($beds as $bed)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $bed['ward_name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $bed['bed_number'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $bed['bed_type'] }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            @if($bed['status'] === 'Available') bg-green-100 text-green-800
                            @elseif($bed['status'] === 'Occupied') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $bed['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($bed['fname'] && $bed['lname'])
                            {{ $bed['fname'] }} {{ $bed['lname'] }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($bed['status'] === 'Occupied')
                            <button data-id="{{ $bed['id'] }}" class="discharge-btn text-red-600 hover:text-red-900">Discharge</button>
                        @else
                            <button data-id="{{ $bed['id'] }}" class="assign-bed-btn text-blue-600 hover:text-blue-900">Assign</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No beds found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Assign Patient Modal -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Assign Patient to Bed</h3>
            </div>
            <form id="assignForm" class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Bed</label>
                    <select id="bedSelect" name="bed_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Available Bed</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Patient</label>
                    <select id="patientSelect" name="patient_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Patient</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admission Reason</label>
                    <textarea name="admission_reason" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter reason for admission..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAssignModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions
function openAssignModal() {
    loadAvailableBeds();
    loadPatients();
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}

// Add event listeners for bed assignment buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.assign-bed-btn')) {
        const btn = e.target.closest('.assign-bed-btn');
        assignToBed(btn.dataset.id);
    }
    if (e.target.closest('.discharge-btn')) {
        const btn = e.target.closest('.discharge-btn');
        dischargePatient(btn.dataset.id);
    }
});

// Load available beds for dropdown
function loadAvailableBeds() {
    fetch('{{ route("nurse.ward-assignments.available-beds") }}')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('bedSelect');
                select.innerHTML = '<option value="">Select Available Bed</option>';
                data.data.forEach(bed => {
                    select.innerHTML += `<option value="${bed.id}">${bed.ward_name} - Bed ${bed.bed_number} (${bed.bed_type})</option>`;
                });
            }
        });
}

// Load patients for dropdown
function loadPatients() {
    fetch('{{ route("nurse.ward-assignments.patients") }}')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('patientSelect');
                select.innerHTML = '<option value="">Select Patient</option>';
                data.data.forEach(patient => {
                    select.innerHTML += `<option value="${patient.id}">${patient.fname} ${patient.lname}</option>`;
                });
            }
        });
}

// Assign patient to specific bed
function assignToBed(bedId) {
    document.getElementById('bedSelect').value = bedId;
    loadPatients();
    document.getElementById('assignModal').classList.remove('hidden');
}

// Form submission
document.getElementById('assignForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);

    fetch('{{ route("nurse.ward-assignments.assign") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeAssignModal();
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    });
});

// Discharge patient
function dischargePatient(bedId) {
    if (!confirm('Are you sure you want to discharge this patient?')) {
        return;
    }

    fetch('{{ route("nurse.ward-assignments.discharge") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ bed_id: bedId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    });
}
</script>
@endsection
