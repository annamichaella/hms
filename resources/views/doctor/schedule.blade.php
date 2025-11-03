@extends('layouts.app')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

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
    <h1 class="text-xl font-bold text-gray-800">My Schedule</h1>
    <p class="text-gray-600">View and manage your weekly schedule.</p>
</div>

<!-- Add Schedule Form -->
<div class="mb-6 bg-white p-6 rounded border border-gray-200 shadow-sm">
    <h3 id="formTitle" class="text-base font-semibold mb-4">Add Schedule</h3>
    <form id="scheduleForm" class="flex flex-wrap gap-4">
        <input type="hidden" name="id" id="schedule_id">
        
        <div class="flex-1 min-w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
            <select name="day" id="day" required class="w-full border rounded p-2">
                <option value="">Select Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>
        
        <div class="flex-1 min-w-32">
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
            <input type="time" name="start_time" id="start_time" required class="w-full border rounded p-2">
        </div>
        
        <div class="flex-1 min-w-32">
            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
            <input type="time" name="end_time" id="end_time" required class="w-full border rounded p-2">
        </div>
        
        <div class="flex items-end">
            <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Add Schedule
            </button>
        </div>
    </form>
</div>

<!-- Search and Filter -->
<div class="mb-4 flex gap-2">
    <input type="text" id="searchInput" placeholder="Search schedules..." class="border rounded p-2 flex-1">
    <button onclick="searchSchedules()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
    <button onclick="resetSearch()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</button>
</div>

<!-- Schedule Table -->
<div class="bg-white p-6 rounded border border-gray-200 shadow-sm">
    <table class="w-full text-sm text-left text-gray-600">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Day</th>
                <th class="px-4 py-3">Start Time</th>
                <th class="px-4 py-3">End Time</th>
                <th class="px-4 py-3">Duration</th>
                <th class="px-4 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="scheduleTableBody">
            @forelse($schedules as $index => $schedule)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3">{{ $index + 1 }}</td>
                <td class="px-4 py-3 font-medium">{{ $schedule->day }}</td>
                <td class="px-4 py-3">{{ $schedule->start_time->format('g:i A') }}</td>
                <td class="px-4 py-3">{{ $schedule->end_time->format('g:i A') }}</td>
                <td class="px-4 py-3">{{ $schedule->start_time->diffInHours($schedule->end_time) }}h {{ $schedule->start_time->diffInMinutes($schedule->end_time) % 60 }}m</td>
                <td class="px-4 py-3 text-center">
                    <button data-id="{{ $schedule->id }}" data-day="{{ $schedule->day }}" data-start="{{ $schedule->start_time->format('H:i') }}" data-end="{{ $schedule->end_time->format('H:i') }}" 
                            class="edit-btn bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded mr-2">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button data-id="{{ $schedule->id }}" 
                            class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-plus text-4xl mb-2 block"></i>
                    No schedules found. Add your first schedule above.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
const scheduleForm = document.getElementById("scheduleForm");
const scheduleTableBody = document.getElementById("scheduleTableBody");
const searchInput = document.getElementById("searchInput");
const submitBtn = document.getElementById("submitBtn");
const formTitle = document.getElementById("formTitle");

// Add event listeners for edit and delete buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-btn')) {
        const btn = e.target.closest('.edit-btn');
        editSchedule(btn.dataset.id, btn.dataset.day, btn.dataset.start, btn.dataset.end);
    }
    if (e.target.closest('.delete-btn')) {
        const btn = e.target.closest('.delete-btn');
        deleteSchedule(btn.dataset.id);
    }
});

function editSchedule(id, day, startTime, endTime) {
    document.getElementById('schedule_id').value = id;
    document.getElementById('day').value = day;
    document.getElementById('start_time').value = startTime;
    document.getElementById('end_time').value = endTime;
    formTitle.textContent = "Update Schedule";
    submitBtn.textContent = "Update Schedule";
    
    document.getElementById('scheduleForm').scrollIntoView({ behavior: 'smooth' });
}

function deleteSchedule(id) {
    confirmAction('Are you sure you want to delete this schedule?', function() {
        fetch('{{ url("doctor/schedule") }}/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                showNotification(data.message || 'Schedule deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Delete failed: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Error deleting schedule', 'error');
        });
    });
}

function searchSchedules() {
    const search = searchInput.value;
    fetch('{{ route("doctor.schedule.search") }}?search=' + encodeURIComponent(search))
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                renderTable(data.data);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error searching schedules');
        });
}

function resetSearch() {
    searchInput.value = '';
    location.reload();
}

function renderTable(schedules) {
    if (schedules.length === 0) {
        scheduleTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-plus text-4xl mb-2 block"></i>
                    No schedules found. Add your first schedule above.
                </td>
            </tr>
        `;
        return;
    }

    scheduleTableBody.innerHTML = schedules.map((schedule, index) => {
        const startTime = new Date(`2000-01-01 ${schedule.start_time}`);
        const endTime = new Date(`2000-01-01 ${schedule.end_time}`);
        const durationMs = endTime - startTime;
        const hours = Math.floor(durationMs / (1000 * 60 * 60));
        const minutes = Math.floor((durationMs % (1000 * 60 * 60)) / (1000 * 60));
        const duration = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;
        
        return `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3">${index + 1}</td>
                <td class="px-4 py-3 font-medium">${schedule.day}</td>
                <td class="px-4 py-3">${startTime.toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true})}</td>
                <td class="px-4 py-3">${endTime.toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true})}</td>
                <td class="px-4 py-3">${duration}</td>
                <td class="px-4 py-3 text-center">
                    <button onclick="editSchedule(${schedule.id}, '${schedule.day}', '${schedule.start_time}', '${schedule.end_time}')" 
                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded mr-2">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteSchedule(${schedule.id})" 
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

scheduleForm.addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(scheduleForm);
    const scheduleId = formData.get('id');
    const url = scheduleId ? '{{ url("doctor/schedule") }}/' + scheduleId : '{{ route("doctor.schedule.store") }}';
    const method = scheduleId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            day: formData.get('day'),
            start_time: formData.get('start_time'),
            end_time: formData.get('end_time'),
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alert(data.message);
            scheduleForm.reset();
            formTitle.textContent = "Add Schedule";
            submitBtn.textContent = "Add Schedule";
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Operation failed'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Request failed. Check console.');
    });
});
</script>
@endsection
