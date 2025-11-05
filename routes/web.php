<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
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
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\StaffController;

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

// Social authentication routes
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('facebook.callback');
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('users/stats', [UserController::class, 'getStats'])->name('admin.users.stats');
        // AJAX routes for users
        Route::post('users/get', [UserController::class, 'show'])->name('admin.users.get');
        Route::post('users/search', [UserController::class, 'index'])->name('admin.users.search');
        Route::get('users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        
        // Appointments routes
        Route::get('appointments', [AppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::get('appointments/create', [AppointmentController::class, 'create'])->name('admin.appointments.create');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('admin.appointments.store');
        Route::get('appointments/search', [AppointmentController::class, 'search'])->name('admin.appointments.search');
        Route::post('appointments/search', [AppointmentController::class, 'search'])->name('admin.appointments.search.post');
        Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('admin.appointments.show');
        Route::get('appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('admin.appointments.edit');
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('admin.appointments.update');
        Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('admin.appointments.destroy');
        
        // Wards routes
        Route::get('wards', [WardController::class, 'index'])->name('admin.wards.index');
        Route::get('wards/create', [WardController::class, 'create'])->name('admin.wards.create');
        Route::post('wards', [WardController::class, 'store'])->name('admin.wards.store');
        Route::get('wards/stats', [WardController::class, 'getStats'])->name('admin.wards.stats');
        Route::get('wards/available-beds', [WardController::class, 'getAvailableBeds'])->name('admin.wards.available-beds');
        Route::post('wards/beds', [WardController::class, 'storeBed'])->name('admin.wards.beds.store');
        Route::post('wards/assign-patient', [WardController::class, 'assignPatient'])->name('admin.wards.assign-patient');
        Route::post('wards/discharge-patient', [WardController::class, 'dischargePatient'])->name('admin.wards.discharge-patient');
        Route::get('wards/{ward}', [WardController::class, 'show'])->name('admin.wards.show');
        Route::get('wards/{ward}/edit', [WardController::class, 'edit'])->name('admin.wards.edit');
        Route::put('wards/{ward}', [WardController::class, 'update'])->name('admin.wards.update');
        Route::delete('wards/{ward}', [WardController::class, 'destroy'])->name('admin.wards.destroy');
        Route::get('wards/{ward}/beds', [WardController::class, 'getBeds'])->name('admin.wards.beds');
        Route::put('wards/beds/{bed}', [WardController::class, 'updateBed'])->name('admin.wards.beds.update');
        Route::delete('wards/beds/{bed}', [WardController::class, 'destroyBed'])->name('admin.wards.beds.destroy');
        
        // Billings routes
        Route::get('billings', [BillingController::class, 'index'])->name('admin.billings.index');
        Route::get('billings/create', [BillingController::class, 'create'])->name('admin.billings.create');
        Route::post('billings', [BillingController::class, 'store'])->name('admin.billings.store');
        Route::get('billings/search', [BillingController::class, 'search'])->name('admin.billings.search');
        Route::post('billings/search', [BillingController::class, 'search'])->name('admin.billings.search.post');
        Route::get('billings/status/{status}', [BillingController::class, 'getBillsByStatus'])->name('admin.billings.status');
        Route::get('billings/stats', [BillingController::class, 'getStats'])->name('admin.billings.stats');
        Route::post('billings/get', [BillingController::class, 'show'])->name('admin.billings.get');
        Route::get('billings/{billing}', [BillingController::class, 'show'])->name('admin.billings.show');
        Route::get('billings/{billing}/edit', [BillingController::class, 'edit'])->name('admin.billings.edit');
        Route::put('billings/{billing}', [BillingController::class, 'update'])->name('admin.billings.update');
        Route::put('billings/{billing}/status', [BillingController::class, 'updateStatus'])->name('admin.billings.update-status');
        Route::delete('billings/{billing}', [BillingController::class, 'destroy'])->name('admin.billings.destroy');
        
        // Records routes
        Route::get('records', [PatientRecordController::class, 'index'])->name('admin.records.index');
        Route::get('records/create', [PatientRecordController::class, 'create'])->name('admin.records.create');
        Route::post('records', [PatientRecordController::class, 'store'])->name('admin.records.store');
        Route::get('records/search', [PatientRecordController::class, 'search'])->name('admin.records.search');
        Route::post('records/search', [PatientRecordController::class, 'search'])->name('admin.records.search.post');
        Route::post('records/get', [PatientRecordController::class, 'show'])->name('admin.records.get');
        Route::get('records/{record}', [PatientRecordController::class, 'show'])->name('admin.records.show');
        Route::get('records/{record}/edit', [PatientRecordController::class, 'edit'])->name('admin.records.edit');
        Route::put('records/{record}', [PatientRecordController::class, 'update'])->name('admin.records.update');
        Route::delete('records/{record}', [PatientRecordController::class, 'destroy'])->name('admin.records.destroy');
        Route::post('records/get-patients', function() {
            $patients = \App\Models\User::where('role', 'patient')->orderBy('fname')->get();
            return response()->json(['success' => true, 'data' => $patients]);
        })->name('admin.records.get-patients');
        Route::get('departments', [DepartmentController::class, 'index'])->name('admin.departments.index');
        Route::get('staff', [StaffController::class, 'index'])->name('admin.staff.index');
    });

    // Doctor routes
    Route::prefix('doctor')->middleware(['role:doctor'])->group(function () {
        Route::get('/', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('doctor.appointments');
        Route::get('/appointments/today', [AppointmentController::class, 'getTodaysAppointments'])->name('doctor.appointments.today');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('doctor.appointments.show');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('doctor.appointments.update');
        Route::get('/appointments/search', [AppointmentController::class, 'search'])->name('doctor.appointments.search');
        Route::get('/patients', [UserController::class, 'patients'])->name('doctor.patients');
        Route::get('/patients/search', [UserController::class, 'patients'])->name('doctor.patients.search');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('doctor.schedule');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('doctor.schedule.store');
        Route::post('/schedule/fetch', [ScheduleController::class, 'fetch'])->name('doctor.schedule.fetch');
        Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])->name('doctor.schedule.update');
        Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('doctor.schedule.destroy');
        Route::get('/schedule/search', [ScheduleController::class, 'search'])->name('doctor.schedule.search');
        Route::post('/schedule/search', [ScheduleController::class, 'search'])->name('doctor.schedule.search.post');
        Route::get('/patients/{patient}/records', [PatientRecordController::class, 'getByPatientId'])->name('doctor.patients.records');
    });

    // Nurse routes
    Route::prefix('nurse')->middleware(['role:nurse'])->group(function () {
        Route::get('/', [NurseDashboardController::class, 'index'])->name('nurse.dashboard');
        Route::get('/patients', [UserController::class, 'patients'])->name('nurse.patients');
        Route::get('/patients/search', [UserController::class, 'patients'])->name('nurse.patients.search');
        Route::get('/wards', [WardController::class, 'index'])->name('nurse.wards');
        Route::get('/ward-assignments', [WardAssignmentController::class, 'index'])->name('nurse.ward-assignments');
        Route::get('/ward-assignments/available-beds', [WardAssignmentController::class, 'getAvailableBeds'])->name('nurse.ward-assignments.available-beds');
        Route::get('/ward-assignments/patients', [WardAssignmentController::class, 'getPatients'])->name('nurse.ward-assignments.patients');
        Route::post('/ward-assignments/assign', [WardAssignmentController::class, 'assignPatient'])->name('nurse.ward-assignments.assign');
        Route::post('/ward-assignments/discharge', [WardAssignmentController::class, 'dischargePatient'])->name('nurse.ward-assignments.discharge');
        Route::get('/ward-assignments/stats', [WardAssignmentController::class, 'getWardStats'])->name('nurse.ward-assignments.stats');
        Route::get('/patients/{patient}/records', [PatientRecordController::class, 'getByPatientId'])->name('nurse.patients.records');
    });

    // Patient routes
    Route::prefix('patient')->middleware(['role:patient'])->group(function () {
        Route::get('/', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('patient.appointments');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('patient.appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('patient.appointments.store');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('patient.appointments.show');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('patient.appointments.cancel');
        Route::get('/records', [PatientRecordController::class, 'index'])->name('patient.records');
        Route::get('/billing', [BillingController::class, 'index'])->name('patient.billing');
    });

    // Staff routes
    Route::prefix('staff')->middleware(['role:staff'])->group(function () {
        Route::get('/', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
        Route::get('appointments', [AppointmentController::class, 'index'])->name('staff.appointments.index');
        Route::get('appointments/create', [AppointmentController::class, 'create'])->name('staff.appointments.create');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('staff.appointments.store');
        Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('staff.appointments.show');
        Route::get('appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('staff.appointments.edit');
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('staff.appointments.update');
        Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('staff.appointments.destroy');
        Route::get('appointments/search', [AppointmentController::class, 'search'])->name('staff.appointments.search');
        Route::get('billings', [BillingController::class, 'index'])->name('staff.billings.index');
        Route::get('billings/create', [BillingController::class, 'create'])->name('staff.billings.create');
        Route::post('billings', [BillingController::class, 'store'])->name('staff.billings.store');
        Route::get('billings/{billing}', [BillingController::class, 'show'])->name('staff.billings.show');
        Route::get('billings/{billing}/edit', [BillingController::class, 'edit'])->name('staff.billings.edit');
        Route::put('billings/{billing}', [BillingController::class, 'update'])->name('staff.billings.update');
        Route::delete('billings/{billing}', [BillingController::class, 'destroy'])->name('staff.billings.destroy');
        Route::get('billings/search', [BillingController::class, 'search'])->name('staff.billings.search');
        Route::put('billings/{billing}/status', [BillingController::class, 'updateStatus'])->name('staff.billings.update-status');
        Route::get('billings/stats', [BillingController::class, 'getStats'])->name('staff.billings.stats');
        Route::get('wards', [WardController::class, 'index'])->name('staff.wards.index');
        Route::get('wards/create', [WardController::class, 'create'])->name('staff.wards.create');
        Route::post('wards', [WardController::class, 'store'])->name('staff.wards.store');
        Route::get('wards/{ward}', [WardController::class, 'show'])->name('staff.wards.show');
        Route::get('wards/{ward}/edit', [WardController::class, 'edit'])->name('staff.wards.edit');
        Route::put('wards/{ward}', [WardController::class, 'update'])->name('staff.wards.update');
        Route::delete('wards/{ward}', [WardController::class, 'destroy'])->name('staff.wards.destroy');
        Route::get('wards/{ward}/beds', [WardController::class, 'getBeds'])->name('staff.wards.beds');
        Route::post('wards/beds', [WardController::class, 'storeBed'])->name('staff.wards.beds.store');
        Route::put('wards/beds/{bed}', [WardController::class, 'updateBed'])->name('staff.wards.beds.update');
        Route::delete('wards/beds/{bed}', [WardController::class, 'destroyBed'])->name('staff.wards.beds.destroy');
        Route::post('wards/assign-patient', [WardController::class, 'assignPatient'])->name('staff.wards.assign-patient');
        Route::post('wards/discharge-patient', [WardController::class, 'dischargePatient'])->name('staff.wards.discharge-patient');
        Route::get('records', [PatientRecordController::class, 'index'])->name('staff.records.index');
        Route::get('records/create', [PatientRecordController::class, 'create'])->name('staff.records.create');
        Route::post('records', [PatientRecordController::class, 'store'])->name('staff.records.store');
        Route::get('records/{record}', [PatientRecordController::class, 'show'])->name('staff.records.show');
        Route::get('records/{record}/edit', [PatientRecordController::class, 'edit'])->name('staff.records.edit');
        Route::put('records/{record}', [PatientRecordController::class, 'update'])->name('staff.records.update');
        Route::delete('records/{record}', [PatientRecordController::class, 'destroy'])->name('staff.records.destroy');
        Route::get('records/search', [PatientRecordController::class, 'search'])->name('staff.records.search');
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('staff.assignments');
        Route::post('/assignments/nurses', [AssignmentController::class, 'getNurses'])->name('staff.assignments.nurses');
        Route::post('/assignments/doctors', [AssignmentController::class, 'getDoctors'])->name('staff.assignments.doctors');
        Route::post('/assignments/assign', [AssignmentController::class, 'assignNurse'])->name('staff.assignments.assign');
        Route::post('/assignments/reassign-doctor', [AssignmentController::class, 'reassignDoctor'])->name('staff.assignments.reassign-doctor');
        Route::post('/assignments/assign-room', [AssignmentController::class, 'assignRoom'])->name('staff.assignments.assign-room');
        Route::post('/assignments/details', [AssignmentController::class, 'getAppointmentDetails'])->name('staff.assignments.details');
    });
});
