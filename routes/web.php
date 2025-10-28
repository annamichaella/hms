<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Nurse\DashboardController as NurseDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientRecordController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Doctor\ScheduleController;
use App\Http\Controllers\Nurse\WardAssignmentController;
use App\Http\Controllers\Staff\AssignmentController;

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
        Route::resource('appointments', AppointmentController::class);
        Route::resource('wards', WardController::class);
        Route::resource('billings', BillingController::class);
    });

    // Doctor routes
    Route::prefix('doctor')->middleware(['role:doctor'])->group(function () {
        Route::get('/', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('doctor.appointments');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('doctor.appointments.show');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('doctor.appointments.update');
        Route::get('/patients', [UserController::class, 'patients'])->name('doctor.patients');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('doctor.schedule');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('doctor.schedule.store');
        Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])->name('doctor.schedule.update');
        Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('doctor.schedule.destroy');
        Route::get('/schedule/search', [ScheduleController::class, 'search'])->name('doctor.schedule.search');
    });

    // Nurse routes
    Route::prefix('nurse')->middleware(['role:nurse'])->group(function () {
        Route::get('/', [NurseDashboardController::class, 'index'])->name('nurse.dashboard');
        Route::get('/patients', [UserController::class, 'patients'])->name('nurse.patients');
        Route::get('/wards', [WardController::class, 'index'])->name('nurse.wards');
        Route::get('/ward-assignments', [WardAssignmentController::class, 'index'])->name('nurse.ward-assignments');
        Route::get('/ward-assignments/available-beds', [WardAssignmentController::class, 'getAvailableBeds'])->name('nurse.ward-assignments.available-beds');
        Route::get('/ward-assignments/patients', [WardAssignmentController::class, 'getPatients'])->name('nurse.ward-assignments.patients');
        Route::post('/ward-assignments/assign', [WardAssignmentController::class, 'assignPatient'])->name('nurse.ward-assignments.assign');
        Route::post('/ward-assignments/discharge', [WardAssignmentController::class, 'dischargePatient'])->name('nurse.ward-assignments.discharge');
        Route::get('/ward-assignments/stats', [WardAssignmentController::class, 'getWardStats'])->name('nurse.ward-assignments.stats');
    });

    // Patient routes
    Route::prefix('patient')->middleware(['role:patient'])->group(function () {
        Route::get('/', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('patient.appointments');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('patient.appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('patient.appointments.store');
        Route::get('/records', [PatientRecordController::class, 'index'])->name('patient.records');
        Route::get('/billing', [BillingController::class, 'index'])->name('patient.billing');
    });

    // Staff routes
    Route::prefix('staff')->middleware(['role:staff'])->group(function () {
        Route::get('/', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
        Route::resource('appointments', AppointmentController::class);
        Route::resource('billings', BillingController::class);
        Route::resource('wards', WardController::class);
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('staff.assignments');
        Route::post('/assignments/nurses', [AssignmentController::class, 'getNurses'])->name('staff.assignments.nurses');
        Route::post('/assignments/doctors', [AssignmentController::class, 'getDoctors'])->name('staff.assignments.doctors');
        Route::post('/assignments/assign', [AssignmentController::class, 'assignNurse'])->name('staff.assignments.assign');
        Route::post('/assignments/reassign-doctor', [AssignmentController::class, 'reassignDoctor'])->name('staff.assignments.reassign-doctor');
        Route::post('/assignments/assign-room', [AssignmentController::class, 'assignRoom'])->name('staff.assignments.assign-room');
        Route::post('/assignments/details', [AssignmentController::class, 'getAppointmentDetails'])->name('staff.assignments.details');
    });
});
