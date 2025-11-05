# CRUD Operations Documentation

This document explains how each CRUD (Create, Read, Update, Delete) functionality works in the Hospital Management System (HMS) project.

---

## 1. User Management (UserController)

### CREATE (`store`)
- **Location**: `app/Http/Controllers/UserController.php`
- **Route**: `POST /admin/users`
- **Access**: Admin only
- **Validation**:
  - Required: `fname`, `lname`, `email` (unique), `password` (min 6 chars), `role` (admin/staff/doctor/nurse/patient)
  - Optional: `mname`, `phone`, `address`, `specialization`, `department`
- **Process**:
  1. Validates input data
  2. Hashes password using `Hash::make()`
  3. Creates user via `User::create()`
  4. Returns JSON response for AJAX requests or redirects to users list
- **Response**: Success message and created user data

### READ (`index`)
- **Location**: `app/Http/Controllers/UserController.php`
- **Route**: `GET /admin/users`
- **Access**: Admin only
- **Features**:
  - Lists all users with search and role filtering
  - Search by first name, last name, or email (LIKE query)
  - Filter by role (admin, staff, doctor, nurse, patient)
  - Ordered by creation date (newest first)
- **Response**: JSON for AJAX requests or renders `admin.users.index` view

### READ (`show`)
- **Location**: `app/Http/Controllers/UserController.php`
- **Route**: `GET /admin/users/{user}` or `POST /admin/users/get`
- **Access**: Admin only
- **Features**:
  - Supports both GET and POST requests
  - Accepts user ID from URL parameter or request body
  - Loads user by ID
- **Response**: JSON for AJAX requests or renders `admin.users.show` view

### UPDATE (`update`)
- **Location**: `app/Http/Controllers/UserController.php`
- **Route**: `PUT /admin/users/{user}`
- **Access**: Admin only
- **Validation**:
  - Same as create, but email uniqueness excludes current user
  - Password is optional (only updated if provided)
  - Merges existing values if fields are empty
- **Process**:
  1. Validates input
  2. Updates user fields
  3. Hashes password if provided
  4. Saves changes
- **Response**: JSON or redirects back with success message

### DELETE (`destroy`)
- **Location**: `app/Http/Controllers/UserController.php`
- **Route**: `DELETE /admin/users/{user}`
- **Access**: Admin only
- **Safety Checks**:
  - Checks for related appointments (`patientAppointments()`, `doctorAppointments()`)
  - Checks for patient records (`patientRecord()`)
  - Prevents deletion if dependencies exist
- **Process**:
  1. Validates no related records exist
  2. Deletes user if safe
  3. Returns error if dependencies found
- **Response**: JSON or redirects to users list

### Additional Methods

**`getStats()`**
- Returns user statistics: total users, users by role, recent users (last 30 days)
- Route: `GET /admin/users/stats`
- Response: JSON with statistics

**`patients()`**
- Lists patients (filtered by doctor's appointments if accessed by doctor)
- Routes: `GET /doctor/patients`, `GET /nurse/patients`
- Features: Search functionality, role-based filtering
- Response: JSON or renders role-specific view

---

## 2. Appointments (AppointmentController)

### CREATE (`store`)
- **Location**: `app/Http/Controllers/AppointmentController.php`
- **Routes**: 
  - `POST /admin/appointments` (Admin)
  - `POST /staff/appointments` (Staff)
  - `POST /patient/appointments` (Patient)
- **Access**: Admin, Staff, Patient
- **Validation**:
  - Role-based: Patients use their own ID, Staff/Admin must select patient
  - `doctor_id` must exist and have role "doctor"
  - `appointment_date` must be today or future date
  - `appointment_time` required
  - `reason` optional (max 500 chars)
- **Process**:
  1. Validates input based on user role
  2. Creates appointment with status "pending"
  3. Links patient, doctor, and optional nurse
- **Response**: JSON or redirects to appropriate appointments list

### READ (`index`)
- **Location**: `app/Http/Controllers/AppointmentController.php`
- **Routes**: Multiple role-based routes
- **Access**: Admin, Staff, Doctor, Patient
- **Role-Based Filtering**:
  - Admin/Staff: All appointments
  - Doctor: Only their own appointments
  - Patient: Only their own appointments
- **Features**:
  - Eager loads relationships (patient, doctor, nurse)
  - Sorted by appointment date and time
- **Response**: JSON or renders role-specific view

### READ (`show`)
- **Location**: `app/Http/Controllers/AppointmentController.php`
- **Route**: `GET /{role}/appointments/{appointment}`
- **Access**: Admin, Staff, Doctor, Patient
- **Features**: Loads appointment with all relationships (patient, doctor, nurse, assignedBy)
- **Response**: JSON or renders role-specific view

### UPDATE (`update`)
- **Location**: `app/Http/Controllers/AppointmentController.php`
- **Route**: `PUT /{role}/appointments/{appointment}`
- **Access**: Admin, Staff, Doctor
- **Validation**:
  - Status: pending, confirmed, completed, cancelled
  - Staff can update patient_id and doctor_id
  - Date/time optional
- **Process**: Updates appointment fields based on user role permissions
- **Response**: JSON or redirects back with success message

### DELETE (`destroy`)
- **Location**: `app/Http/Controllers/AppointmentController.php`
- **Route**: `DELETE /{role}/appointments/{appointment}`
- **Access**: Admin, Staff, Patient (for their own)
- **Process**: Sets status to "cancelled" (soft delete pattern)
- **Response**: JSON or redirects back

### Additional Methods

**`getTodaysAppointments()`**
- Returns today's appointments for a doctor
- Route: `GET /doctor/appointments/today`
- Response: JSON with count and appointment data

**`search()`**
- Search appointments by patient name or date
- Routes: `GET/POST /{role}/appointments/search`
- Features: Role-aware search (doctors see their patients, etc.)
- Response: JSON with filtered appointments

---

## 3. Billing (BillingController)

### CREATE (`store`)
- **Location**: `app/Http/Controllers/BillingController.php`
- **Routes**: 
  - `POST /admin/billings` (Admin)
  - `POST /staff/billings` (Staff)
- **Access**: Admin, Staff
- **Validation**:
  - Required: `patient_name`, `service`, `amount` (numeric, min 0), `billing_date`
  - Optional: `doctor_name`, `due_date`, `notes`, `status`
- **Process**:
  1. Validates input
  2. Creates billing record
  3. Status defaults to "pending" if not provided
- **Response**: JSON or redirects to billings list

### READ (`index`)
- **Location**: `app/Http/Controllers/BillingController.php`
- **Routes**: Multiple role-based routes
- **Access**: Admin, Staff, Patient
- **Role-Based Filtering**:
  - Admin/Staff: All bills
  - Patient: Only their own bills (matched by patient_name)
- **Features**: Sorted by billing date (newest first)
- **Response**: JSON or renders role-specific view

### READ (`show`)
- **Location**: `app/Http/Controllers/BillingController.php`
- **Route**: `GET /{role}/billings/{billing}` or `POST /admin/billings/get`
- **Access**: Admin, Staff, Patient
- **Features**: Supports both GET and POST requests
- **Response**: JSON or renders role-specific view

### UPDATE (`update`)
- **Location**: `app/Http/Controllers/BillingController.php`
- **Route**: `PUT /{role}/billings/{billing}`
- **Access**: Admin, Staff
- **Validation**: Same as create, but all fields optional (using "sometimes" rule)
- **Process**: Updates bill fields
- **Response**: JSON or redirects to billings list

### UPDATE (`updateStatus`)
- **Location**: `app/Http/Controllers/BillingController.php`
- **Route**: `PUT /{role}/billings/{billing}/status`
- **Access**: Admin, Staff
- **Purpose**: Specifically for updating payment status
- **Validation**: Status must be pending, partial, paid, or overdue
- **Features**: Can also update payment_method
- **Response**: JSON or redirects back

### DELETE (`destroy`)
- **Location**: `app/Http/Controllers/BillingController.php`
- **Route**: `DELETE /{role}/billings/{billing}`
- **Access**: Admin, Staff
- **Process**: Deletes billing record
- **Response**: JSON or redirects to billings list

### Additional Methods

**`search()`**
- Search bills by patient name, doctor name, service, status, or date range
- Routes: `GET/POST /{role}/billings/search`
- Response: JSON with filtered bills

**`getBillsByStatus()`**
- Filter bills by status
- Route: `GET /admin/billings/status/{status}`
- Response: JSON with filtered bills

**`getStats()`**
- Returns billing statistics: total count, total amount, paid amount, pending amount
- Route: `GET /{role}/billings/stats`
- Response: JSON with statistics

---

## 4. Wards & Beds (WardController)

### Ward Operations

#### CREATE (`store`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Routes**: 
  - `POST /admin/wards` (Admin)
  - `POST /staff/wards` (Staff)
- **Access**: Admin, Staff
- **Validation**:
  - Required: `ward_name`, `ward_type` (General/ICU/Emergency/Surgery/Pediatric/Maternity), `floor`, `capacity` (integer, min 0)
  - Optional: `status` (defaults to "Active")
- **Process**: Creates ward record
- **Response**: JSON or redirects to wards list

#### READ (`index`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Routes**: Multiple role-based routes
- **Access**: Admin, Staff, Nurse
- **Features**:
  - Lists wards with bed counts (total, occupied, available)
  - Uses `withCount()` for efficient aggregation
  - Sorted by ward name
- **Response**: JSON or renders role-specific view

#### READ (`show`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Route**: `GET /{role}/wards/{ward}`
- **Access**: Admin, Staff, Nurse
- **Features**: Loads ward with beds and patient relationships
- **Response**: JSON or renders view

#### UPDATE (`update`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Route**: `PUT /{role}/wards/{ward}`
- **Access**: Admin, Staff
- **Validation**: Same as create
- **Process**: Updates ward fields
- **Response**: JSON or redirects to wards list

#### DELETE (`destroy`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Route**: `DELETE /{role}/wards/{ward}`
- **Access**: Admin, Staff
- **Safety Checks**: Prevents deletion if ward has occupied beds
- **Process**: 
  1. Checks for occupied beds
  2. Returns error if beds are occupied
  3. Deletes ward if safe
- **Response**: JSON or redirects to wards list

### Bed Operations

#### CREATE (`storeBed`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Route**: `POST /{role}/wards/beds`
- **Access**: Admin, Staff
- **Validation**:
  - Required: `ward_id` (must exist), `bed_number`, `bed_type` (Standard/ICU/Private/Semi-Private)
- **Process**: Creates bed with status "Available"
- **Response**: JSON only (AJAX endpoint)

#### READ (`getBeds`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Route**: `GET /{role}/wards/{ward}/beds`
- **Access**: Admin, Staff
- **Features**: Lists beds for a ward with relationships
- **Response**: JSON

#### UPDATE (`updateBed`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Route**: `PUT /{role}/wards/beds/{bed}`
- **Access**: Admin, Staff
- **Validation**:
  - Required: `bed_number`, `bed_type`, `status` (Available/Occupied/Maintenance/Reserved)
- **Process**: Updates bed fields
- **Response**: JSON only (AJAX endpoint)

#### DELETE (`destroyBed`)
- **Location**: `app/Http/Controllers/WardController.php`
- **Route**: `DELETE /{role}/wards/beds/{bed}`
- **Access**: Admin, Staff
- **Safety Checks**: Prevents deletion if bed is occupied
- **Process**: Deletes bed if not occupied
- **Response**: JSON only (AJAX endpoint)

### Additional Methods

**`assignPatient()`**
- Assigns patient to a bed
- Route: `POST /{role}/wards/assign-patient`
- **Process**:
  1. Validates bed is available
  2. Uses database transaction for data integrity
  3. Updates bed status to "Occupied"
  4. Creates PatientAdmission record
  5. Sets admission_date
- **Response**: JSON

**`dischargePatient()`**
- Discharges patient from bed
- Route: `POST /{role}/wards/discharge-patient`
- **Process**:
  1. Uses database transaction
  2. Sets bed status to "Available"
  3. Clears patient_id and admission_date
  4. Updates PatientAdmission status to "Discharged"
  5. Sets discharge_date
- **Response**: JSON

**`getAvailableBeds()`**
- Returns available beds in active wards
- Route: `GET /{role}/wards/available-beds`
- **Features**: Filters by status and ward status
- **Response**: JSON

**`getStats()`**
- Returns ward/bed statistics
- Route: `GET /admin/wards/stats`
- **Statistics**: Total wards, total beds, available beds, occupied beds, maintenance beds
- **Response**: JSON

---

## 5. Patient Records (PatientRecordController)

### CREATE (`store`)
- **Location**: `app/Http/Controllers/PatientRecordController.php`
- **Routes**: 
  - `POST /admin/records` (Admin)
  - `POST /staff/records` (Staff)
- **Access**: Admin, Staff
- **Validation**:
  - Required: `user_id` (must exist in users table)
  - Optional: `blood_type` (A+, A-, B+, B-, AB+, AB-, O+, O-), `allergies`, `medical_conditions`, `emergency_contact_name`, `emergency_contact_phone`
- **Process**: Creates patient record
- **Response**: JSON or redirects to records list

### READ (`index`)
- **Location**: `app/Http/Controllers/PatientRecordController.php`
- **Routes**: Multiple role-based routes
- **Access**: Admin, Staff, Doctor, Patient
- **Role-Based Filtering**:
  - Admin/Staff/Doctor: All records
  - Patient: Only their own records
- **Features**:
  - Eager loads user relationship
  - Sorted by update date (newest first)
- **Response**: JSON or renders role-specific view

### READ (`show`)
- **Location**: `app/Http/Controllers/PatientRecordController.php`
- **Route**: `GET /{role}/records/{record}` or `POST /admin/records/get`
- **Access**: Admin, Staff, Doctor, Patient
- **Features**: Supports both GET and POST requests, loads with user relationship
- **Response**: JSON or renders view

### UPDATE (`update`)
- **Location**: `app/Http/Controllers/PatientRecordController.php`
- **Route**: `PUT /{role}/records/{record}`
- **Access**: Admin, Staff
- **Validation**: Same fields as create (all optional)
- **Process**: Updates record fields
- **Response**: JSON or redirects to records list

### DELETE (`destroy`)
- **Location**: `app/Http/Controllers/PatientRecordController.php`
- **Route**: `DELETE /{role}/records/{record}`
- **Access**: Admin, Staff
- **Process**: Deletes patient record
- **Response**: JSON or redirects to records list

### Additional Methods

**`search()`**
- Search patient records by patient name, email, blood type, or department
- Routes: `GET/POST /{role}/records/search`
- **Features**: Searches across related user table
- **Response**: JSON with filtered records

**`getByPatientId()`**
- Get record for a specific patient (used by doctors/nurses)
- Route: `GET /doctor/patients/{patient}/records` or `GET /nurse/patients/{patient}/records`
- **Response**: JSON or renders view

---

## 6. Doctor Schedules (ScheduleController)

### CREATE (`store`)
- **Location**: `app/Http/Controllers/Doctor/ScheduleController.php`
- **Route**: `POST /doctor/schedule`
- **Access**: Doctor only
- **Validation**:
  - Required: `day` (Monday-Sunday), `start_time`, `end_time` (must be after start_time)
- **Safety Checks**: Prevents overlapping schedules on same day
- **Process**:
  1. Validates input
  2. Checks for time conflicts
  3. Creates schedule for authenticated doctor
- **Response**: JSON only (AJAX endpoint)

### READ (`index`)
- **Location**: `app/Http/Controllers/Doctor/ScheduleController.php`
- **Route**: `GET /doctor/schedule`
- **Access**: Doctor only
- **Features**: Lists schedules for authenticated doctor, sorted by day and start time
- **Response**: Renders `doctor.schedule` view

### READ (`fetch`)
- **Location**: `app/Http/Controllers/Doctor/ScheduleController.php`
- **Route**: `POST /doctor/schedule/fetch`
- **Access**: Doctor only
- **Features**: AJAX endpoint to fetch schedules with optional search filter
- **Response**: JSON

### UPDATE (`update`)
- **Location**: `app/Http/Controllers/Doctor/ScheduleController.php`
- **Route**: `PUT /doctor/schedule/{schedule}`
- **Access**: Doctor only (only their own schedules)
- **Validation**: Same as create
- **Safety Checks**: Prevents overlapping schedules (excluding current schedule)
- **Process**: Updates schedule if no conflicts
- **Response**: JSON only (AJAX endpoint)

### DELETE (`destroy`)
- **Location**: `app/Http/Controllers/Doctor/ScheduleController.php`
- **Route**: `DELETE /doctor/schedule/{schedule}`
- **Access**: Doctor only (only their own schedules)
- **Process**: Deletes schedule
- **Response**: JSON only (AJAX endpoint)

### Additional Methods

**`search()`**
- Search schedules by day, start_time, or end_time
- Routes: `GET/POST /doctor/schedule/search`
- **Response**: JSON with filtered schedules

---

## 7. Departments (DepartmentController)

### READ (`index`)
- **Location**: `app/Http/Controllers/Admin/DepartmentController.php`
- **Route**: `GET /admin/departments`
- **Access**: Admin only
- **Features**:
  - Aggregates departments from User table
  - Groups by department and counts staff
  - Excludes empty/null departments
- **Response**: Renders `admin.departments.index` view
- **Note**: No CREATE/UPDATE/DELETE operations (departments are derived from user data)

---

## 8. Staff (StaffController)

### READ (`index`)
- **Location**: `app/Http/Controllers/Admin/StaffController.php`
- **Route**: `GET /admin/staff`
- **Access**: Admin only
- **Features**:
  - Lists users with roles: staff, doctor, nurse
  - Sorted by first name
- **Response**: JSON or renders `admin.staff.index` view
- **Note**: No CREATE/UPDATE/DELETE operations (uses UserController for modifications)

---

## Common Patterns Across All CRUD Operations

### 1. Role-Based Access Control
- All routes are protected by `auth` middleware
- Additional role middleware (`role:admin`, `role:doctor`, etc.) restricts access
- Each controller checks user role to determine what data to show/modify

### 2. Dual Response Format
- All controllers support both JSON (for AJAX requests) and HTML views
- Uses `$request->expectsJson()` to determine response type
- JSON responses follow consistent format: `{ success: true/false, data: ..., message: ... }`

### 3. Validation
- All input is validated using Laravel's validation rules
- Validation errors are returned in consistent format
- Custom validation messages for better user experience

### 4. Relationship Loading
- Uses Eloquent's `with()` method for eager loading relationships
- Prevents N+1 query problems
- Improves performance

### 5. Search Functionality
- Most controllers include search methods
- Search typically uses LIKE queries with wildcards
- Role-aware search filters results based on user permissions

### 6. Statistics Methods
- Many controllers provide statistics methods for dashboards
- Returns aggregated data (counts, sums, etc.)
- Used for analytics and reporting

### 7. Error Handling
- Returns appropriate error messages for JSON and HTML requests
- Handles missing records gracefully
- Provides user-friendly error messages

### 8. Database Transactions
- Used for operations affecting multiple tables
- Ensures data integrity (e.g., bed assignment creates both bed update and admission record)
- Rolls back on errors

### 9. Soft Deletes
- Some operations use soft deletes (e.g., appointments set status to "cancelled")
- Prevents data loss while maintaining referential integrity

### 10. Security Checks
- Prevents deletion of records with dependencies
- Validates ownership (e.g., doctors can only modify their own schedules)
- Checks for conflicts before creating records (e.g., overlapping schedules)

---

## Route Structure

All routes are defined in `routes/web.php` and follow RESTful conventions:

- **CREATE**: `POST /{resource}`
- **READ**: `GET /{resource}` (index) or `GET /{resource}/{id}` (show)
- **UPDATE**: `PUT /{resource}/{id}`
- **DELETE**: `DELETE /{resource}/{id}`

Routes are organized by role prefixes:
- `/admin/*` - Admin routes
- `/doctor/*` - Doctor routes
- `/nurse/*` - Nurse routes
- `/patient/*` - Patient routes
- `/staff/*` - Staff routes

---

## Model Relationships

Key relationships used in CRUD operations:

- **User** has many: appointments (as patient/doctor/nurse), schedules (as doctor), admissions (as patient)
- **Appointment** belongs to: patient (User), doctor (User), nurse (User), assignedBy (User)
- **PatientRecord** belongs to: user (User)
- **Ward** has many: beds
- **Bed** belongs to: ward (Ward), patient (User)
- **Billing** - standalone (references patient/doctor by name)
- **DoctorSchedule** belongs to: doctor (User)
- **PatientAdmission** belongs to: patient (User), bed (Bed)

---

## Notes

- All timestamps are automatically handled by Laravel (`created_at`, `updated_at`)
- Password hashing is handled automatically by Laravel's Hash facade
- Email validation ensures unique emails per user
- Date validation ensures appointments are scheduled for future dates
- Status fields use enum values for consistency
- Foreign key constraints ensure referential integrity

---

## API Usage & Implementation

This project implements a **hybrid API approach** where the same routes serve both HTML views and JSON responses. This is different from a traditional REST API setup with separate API routes.

### API Architecture Overview

**Key Characteristics:**
- **No Separate API Routes**: Uses `routes/web.php` instead of `routes/api.php`
- **Dual Response Format**: Controllers detect request type and return JSON or HTML
- **Session-Based Authentication**: Uses Laravel's web guard (session-based), not API tokens
- **CSRF Protection**: All AJAX requests include CSRF tokens
- **AJAX-First Approach**: Frontend primarily uses AJAX for data operations

### How API Detection Works

Controllers use `$request->expectsJson()` to detect JSON requests:

```php
if ($request->expectsJson()) {
    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}
return view('view.name', compact('data'));
```

**Request Detection:**
- Laravel automatically detects JSON requests when:
  - `Accept: application/json` header is present
  - `Content-Type: application/json` header is present
  - Request comes via AJAX (X-Requested-With: XMLHttpRequest)
  - URL ends with `.json`

### Frontend AJAX Implementation

#### 1. CSRF Token Setup

All Blade layouts include CSRF token in meta tag:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

#### 2. Laravel AJAX Helper

**Location**: `public/assets/js/laravel-ajax.js`

Provides helper functions for making AJAX requests with automatic CSRF token inclusion:

```javascript
// POST request
laravelAjax.post('/admin/users', { name: 'John' }, function(response) {
    console.log(response);
});

// GET request
laravelAjax.get('/admin/users/stats', function(response) {
    console.log(response);
});
```

**Features:**
- Automatically includes CSRF token in headers
- Works with both jQuery and vanilla JavaScript (fetch API)
- Sets proper headers (`Accept: application/json`, `X-CSRF-TOKEN`)

#### 3. jQuery AJAX Setup

jQuery is configured to automatically include CSRF token:

```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': token
    }
});
```

#### 4. Fetch API Usage

Direct fetch API usage example:

```javascript
fetch('/admin/users/get', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({ id: 1 })
})
.then(response => response.json())
.then(data => {
    console.log(data);
});
```

### API Response Format

All JSON responses follow a consistent format:

**Success Response:**
```json
{
    "success": true,
    "data": { ... },
    "message": "Operation successful" // optional
}
```

**Error Response:**
```json
{
    "success": false,
    "error": "Error message",
    "errors": { ... } // validation errors if applicable
}
```

### API Endpoints by Controller

#### UserController API Endpoints

**GET** `/admin/users`
- Returns: List of users
- Headers: `Accept: application/json`
- Response: `{ success: true, data: [...] }`

**POST** `/admin/users/get`
- Body: `{ id: 1 }` or `{ user_id: 1 }`
- Returns: Single user data
- Response: `{ success: true, data: {...} }`

**POST** `/admin/users/search`
- Body: `{ search_term: "john", role_filter: "doctor" }`
- Returns: Filtered users
- Response: `{ success: true, data: [...] }`

**POST** `/admin/users`
- Body: User creation data
- Returns: Created user
- Response: `{ success: true, message: "...", data: {...} }`

**PUT** `/admin/users/{user}`
- Body: User update data
- Returns: Updated user
- Response: `{ success: true, message: "...", data: {...} }`

**DELETE** `/admin/users/{user}`
- Returns: Deletion confirmation
- Response: `{ success: true, message: "..." }`

**GET** `/admin/users/stats`
- Returns: User statistics
- Response: `{ success: true, data: { total_users, users_by_role, recent_users } }`

#### AppointmentController API Endpoints

**GET** `/admin/appointments` (or `/doctor/appointments`, `/patient/appointments`)
- Returns: List of appointments
- Response: `{ success: true, data: [...] }`

**POST** `/admin/appointments/search`
- Body: `{ keyword: "john" }`
- Returns: Filtered appointments
- Response: `{ success: true, data: [...] }`

**GET** `/doctor/appointments/today`
- Returns: Today's appointments for doctor
- Response: `{ success: true, count: 5, data: [...] }`

#### BillingController API Endpoints

**GET** `/admin/billings/stats`
- Returns: Billing statistics
- Response: `{ success: true, data: { total_count, total_amount, paid_amount, pending_amount } }`

**POST** `/admin/billings/get`
- Body: `{ id: 1 }` or `{ bill_id: 1 }`
- Returns: Single billing record
- Response: `{ success: true, data: {...} }`

**POST** `/admin/billings/search`
- Body: `{ keyword: "john", status: "pending", date_from: "2024-01-01", date_to: "2024-12-31" }`
- Returns: Filtered bills
- Response: `{ success: true, data: [...] }`

**GET** `/admin/billings/status/{status}`
- Returns: Bills filtered by status
- Response: `{ success: true, data: [...] }`

#### WardController API Endpoints

**GET** `/admin/wards/{ward}/beds`
- Returns: Beds for a ward
- Response: `{ success: true, data: [...] }`

**POST** `/admin/wards/beds`
- Body: `{ ward_id: 1, bed_number: "A1", bed_type: "Standard" }`
- Returns: Created bed
- Response: `{ success: true, message: "...", data: {...} }`

**PUT** `/admin/wards/beds/{bed}`
- Body: `{ bed_number: "A1", bed_type: "ICU", status: "Occupied" }`
- Returns: Updated bed
- Response: `{ success: true, message: "...", data: {...} }`

**DELETE** `/admin/wards/beds/{bed}`
- Returns: Deletion confirmation
- Response: `{ success: true, message: "..." }`

**POST** `/admin/wards/assign-patient`
- Body: `{ patient_id: 1, bed_id: 1, admission_reason: "..." }`
- Returns: Assignment confirmation
- Response: `{ success: true, message: "..." }`

**POST** `/admin/wards/discharge-patient`
- Body: `{ bed_id: 1 }`
- Returns: Discharge confirmation
- Response: `{ success: true, message: "..." }`

**GET** `/admin/wards/available-beds`
- Returns: Available beds in active wards
- Response: `{ success: true, data: [...] }`

**GET** `/admin/wards/stats`
- Returns: Ward/bed statistics
- Response: `{ success: true, data: { total_wards, total_beds, available_beds, occupied_beds, maintenance_beds } }`

#### PatientRecordController API Endpoints

**POST** `/admin/records/get`
- Body: `{ id: 1 }` or `{ record_id: 1 }`
- Returns: Single patient record
- Response: `{ success: true, data: {...} }`

**POST** `/admin/records/search`
- Body: `{ keyword: "john", blood_type: "A+", department: "Cardiology" }`
- Returns: Filtered records
- Response: `{ success: true, data: [...] }`

**GET** `/doctor/patients/{patient}/records`
- Returns: Patient record for specific patient
- Response: `{ success: true, data: {...} }`

#### ScheduleController API Endpoints

**POST** `/doctor/schedule`
- Body: `{ day: "Monday", start_time: "09:00", end_time: "17:00" }`
- Returns: Created schedule
- Response: `{ status: "success", message: "...", data: {...} }`

**POST** `/doctor/schedule/fetch`
- Body: `{ search: "Monday" }` (optional)
- Returns: Doctor's schedules
- Response: `{ status: "success", data: [...] }`

**PUT** `/doctor/schedule/{schedule}`
- Body: `{ day: "Tuesday", start_time: "10:00", end_time: "18:00" }`
- Returns: Updated schedule
- Response: `{ status: "success", message: "...", data: {...} }`

**DELETE** `/doctor/schedule/{schedule}`
- Returns: Deletion confirmation
- Response: `{ status: "success", message: "..." }`

**GET/POST** `/doctor/schedule/search`
- Query/Body: `{ search: "Monday" }`
- Returns: Filtered schedules
- Response: `{ status: "success", data: [...] }`

#### AssignmentController API Endpoints

**POST** `/staff/assignments/nurses`
- Body: `{ appointment_id: 1 }`
- Returns: Available nurses
- Response: `{ success: true, data: [...] }`

**POST** `/staff/assignments/doctors`
- Body: `{ appointment_id: 1 }`
- Returns: Available doctors
- Response: `{ success: true, data: [...] }`

**POST** `/staff/assignments/assign`
- Body: `{ appointment_id: 1, assign_nurse: 1, nurse_id: 2, notes: "..." }`
- Returns: Assignment confirmation
- Response: `{ success: true, message: "..." }`

#### Nurse WardAssignmentController API Endpoints

**GET** `/nurse/ward-assignments/available-beds`
- Returns: Available beds
- Response: `{ success: true, data: [...] }`

**GET** `/nurse/ward-assignments/patients`
- Returns: List of patients
- Response: `{ success: true, data: [...] }`

**POST** `/nurse/ward-assignments/assign`
- Body: `{ patient_id: 1, bed_id: 1, admission_reason: "..." }`
- Returns: Assignment confirmation
- Response: `{ success: true, message: "..." }`

**POST** `/nurse/ward-assignments/discharge`
- Body: `{ bed_id: 1 }`
- Returns: Discharge confirmation
- Response: `{ success: true, message: "..." }`

**GET** `/nurse/ward-assignments/stats`
- Returns: Ward statistics
- Response: `{ success: true, data: {...} }`

### Authentication & Security

#### Session-Based Authentication

- Uses Laravel's web guard (session-based)
- No API tokens (Sanctum, Passport) required
- User must be logged in via web session
- All routes protected by `auth` middleware

#### CSRF Protection

- All POST/PUT/DELETE requests require CSRF token
- Token included in request header: `X-CSRF-TOKEN`
- Token obtained from meta tag: `<meta name="csrf-token" content="...">`
- Automatically handled by `laravel-ajax.js` helper

#### Role-Based Access Control

- Additional `role:admin`, `role:doctor`, etc. middleware
- API endpoints respect same role restrictions as web routes
- Unauthorized access returns 403 error

### Making API Calls from Frontend

#### Method 1: Using Laravel AJAX Helper

```javascript
// POST request
laravelAjax.post('/admin/users', {
    fname: 'John',
    lname: 'Doe',
    email: 'john@example.com',
    role: 'patient'
}, function(response) {
    if (response.success) {
        console.log('User created:', response.data);
    } else {
        console.error('Error:', response.error);
    }
});

// GET request
laravelAjax.get('/admin/users/stats', function(response) {
    if (response.success) {
        console.log('Stats:', response.data);
    }
});
```

#### Method 2: Using jQuery AJAX

```javascript
$.ajax({
    url: '/admin/users/get',
    type: 'POST',
    data: { id: 1 },
    dataType: 'json',
    headers: {
        'Accept': 'application/json'
    },
    success: function(response) {
        if (response.success) {
            console.log('User:', response.data);
        }
    },
    error: function(xhr, status, error) {
        console.error('Error:', error);
    }
});
```

#### Method 3: Using Fetch API

```javascript
fetch('/admin/users/get', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({ id: 1 })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('User:', data.data);
    }
})
.catch(error => console.error('Error:', error));
```

### Error Handling

#### Validation Errors

When validation fails, Laravel returns:
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

#### Custom Error Responses

Controllers return custom error format:
```json
{
    "success": false,
    "error": "Cannot delete user with existing records."
}
```

#### HTTP Status Codes

- `200` - Success
- `400` - Bad Request (validation errors, business logic errors)
- `403` - Forbidden (unauthorized access)
- `404` - Not Found (resource doesn't exist)
- `422` - Unprocessable Entity (validation errors)
- `500` - Server Error

### Advantages of This Approach

1. **Single Source of Truth**: Same controller methods handle both web and API
2. **DRY Principle**: No code duplication between web and API controllers
3. **Consistent Validation**: Same validation rules for both interfaces
4. **Session Security**: Leverages Laravel's built-in session security
5. **CSRF Protection**: Automatic CSRF protection for all requests
6. **Easy Development**: No need to manage separate API tokens

### Limitations & Considerations

1. **No Stateless API**: Requires session, not suitable for mobile apps without web views
2. **CSRF Required**: All state-changing operations need CSRF token
3. **Same Origin**: Best suited for same-domain AJAX requests
4. **No API Versioning**: No built-in versioning system
5. **Rate Limiting**: May need custom rate limiting implementation

### Future API Enhancements

If you need a true REST API (for mobile apps, external integrations), consider:

1. **Laravel Sanctum**: For SPA authentication and API tokens
2. **Separate API Routes**: Create `routes/api.php` for API endpoints
3. **API Resources**: Use Laravel API Resources for consistent formatting
4. **API Versioning**: Implement versioning (e.g., `/api/v1/users`)
5. **Rate Limiting**: Add rate limiting middleware
6. **API Documentation**: Use tools like Swagger/OpenAPI

---

*Last Updated: Documentation generated from codebase analysis*

