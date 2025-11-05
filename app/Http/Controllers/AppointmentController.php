<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $appointments = [];

        if ($user->role === 'admin' || $user->role === 'staff') {
            $appointments = Appointment::with(['patient', 'doctor', 'nurse'])
                ->orderBy('appointment_date', 'asc')
                ->orderBy('appointment_time', 'asc')
                ->get();
        } elseif ($user->role === 'doctor') {
            $appointments = Appointment::with('patient')
                ->where('doctor_id', $user->id)
                ->orderBy('appointment_date', 'asc')
                ->orderBy('appointment_time', 'asc')
                ->get();
        } elseif ($user->role === 'patient') {
            $appointments = Appointment::with('doctor')
                ->where('patient_id', $user->id)
                ->orderBy('appointment_date', 'desc')
                ->get();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        }

        // Use different views based on role
        if ($user->role === 'patient') {
            return view('patient.appointments.index', compact('appointments'));
        } elseif ($user->role === 'doctor') {
            return view('doctor.appointments.index', compact('appointments'));
        } elseif ($user->role === 'staff') {
            return view('staff.appointments.index', compact('appointments'));
        } elseif ($user->role === 'admin') {
            return view('admin.appointments.index', compact('appointments'));
        }

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $doctors = User::where('role', 'doctor')->orderBy('fname')->get();
        $patients = User::where('role', 'patient')->orderBy('fname')->get();
        $user = Auth::user();
        
        // If no doctors available, redirect back with error
        if ($doctors->isEmpty() && $user->role === 'patient') {
            return redirect()->route('patient.appointments')
                ->with('error', 'No doctors are currently available. Please contact the administrator.');
        }
        
        // Use different views based on role
        if ($user->role === 'patient') {
            return view('patient.appointments.create', compact('doctors'));
        } elseif ($user->role === 'staff') {
            return view('staff.appointments.create', compact('doctors', 'patients'));
        }
        
        return view('appointments.create', compact('doctors'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isPatient = $user->role === 'patient';
        
        $validator = \Validator::make($request->all(), [
            'patient_id' => $isPatient ? 'nullable' : 'required|exists:users,id',
            'doctor_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $doctor = User::where('id', $value)->where('role', 'doctor')->first();
                    if (!$doctor) {
                        $fail('The selected doctor is invalid.');
                    }
                },
            ],
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'nullable|string|max:500',
        ], [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'The selected patient is invalid.',
            'doctor_id.required' => 'Please select a doctor.',
            'doctor_id.exists' => 'The selected doctor is invalid.',
            'appointment_date.required' => 'Please select an appointment date.',
            'appointment_date.after_or_equal' => 'The appointment date must be today or a future date.',
            'appointment_time.required' => 'Please select an appointment time.',
        ]);

        // For patients, redirect to create page on validation failure
        if ($validator->fails()) {
            if ($isPatient) {
                return redirect()->route('patient.appointments.create')
                    ->withErrors($validator)
                    ->withInput();
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $patientId = $isPatient ? Auth::id() : $request->patient_id;

        try {
            $appointment = Appointment::create([
                'patient_id' => $patientId,
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment scheduled successfully!',
                    'data' => $appointment->load(['patient', 'doctor'])
                ]);
            }

            $routeName = 'appointments.index';
            if ($user->role === 'patient') {
                $routeName = 'patient.appointments';
            } elseif ($user->role === 'admin') {
                $routeName = 'admin.appointments.index';
            } elseif ($user->role === 'staff') {
                $routeName = 'staff.appointments.index';
            }
            
            return redirect()->route($routeName)
                ->with('success', 'Appointment scheduled successfully!');
        } catch (\Exception $e) {
            $user = Auth::user();
            // For patients, always redirect to create page on error
            if ($user->role === 'patient') {
                return redirect()->route('patient.appointments.create')
                    ->withInput()
                    ->withErrors(['error' => 'Failed to create appointment. Please try again.']);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create appointment. Please try again.']);
        }
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        $appointment->load(['patient', 'doctor', 'nurse', 'assignedBy']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $appointment
            ]);
        }

        // Use different views based on role
        if ($user->role === 'patient') {
            return view('patient.appointments.show', compact('appointment'));
        } elseif ($user->role === 'doctor') {
            return view('doctor.appointments.show', compact('appointment'));
        } elseif ($user->role === 'staff') {
            return view('staff.appointments.show', compact('appointment'));
        } elseif ($user->role === 'admin') {
            return view('admin.appointments.show', compact('appointment'));
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $user = Auth::user();
        $doctors = User::where('role', 'doctor')->orderBy('fname')->get();
        $patients = User::where('role', 'patient')->orderBy('fname')->get();
        $appointment->load(['patient', 'doctor']);
        
        if ($user->role === 'staff') {
            return view('staff.appointments.edit', compact('appointment', 'doctors', 'patients'));
        }
        
        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    /**
     * Update the specified appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $validationRules = [
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes',
            'reason' => 'nullable|string|max:500',
        ];
        
        if ($user->role === 'staff') {
            $validationRules['patient_id'] = 'sometimes|exists:users,id';
            $validationRules['doctor_id'] = 'sometimes|exists:users,id';
        }
        
        $request->validate($validationRules);

        $updateData = $request->only([
            'status', 'appointment_date', 'appointment_time', 'reason'
        ]);
        
        if ($user->role === 'staff') {
            if ($request->has('patient_id')) {
                $updateData['patient_id'] = $request->patient_id;
            }
            if ($request->has('doctor_id')) {
                $updateData['doctor_id'] = $request->doctor_id;
            }
        }
        
        $appointment->update($updateData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully!',
                'data' => $appointment->fresh()->load(['patient', 'doctor'])
            ]);
        }

        return redirect()->back()->with('success', 'Appointment updated successfully!');
    }

    /**
     * Cancel the specified appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Appointment cancelled successfully!');
    }

    /**
     * Get today's appointments for a doctor.
     */
    public function getTodaysAppointments()
    {
        $doctorId = Auth::id();
        $today = date('Y-m-d');

        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->where('appointment_date', $today)
            ->orderBy('appointment_time', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $appointments->count(),
            'data' => $appointments
        ]);
    }

    /**
     * Search appointments.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $user = Auth::user();

        $query = Appointment::query();

        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id)
                ->whereHas('patient', function ($q) use ($keyword) {
                    $q->where('fname', 'like', "%{$keyword}%")
                      ->orWhere('lname', 'like', "%{$keyword}%");
                })
                ->orWhere('appointment_date', 'like', "%{$keyword}%");
        } elseif ($user->role === 'patient') {
            $query->where('patient_id', $user->id)
                ->whereHas('doctor', function ($q) use ($keyword) {
                    $q->where('fname', 'like', "%{$keyword}%")
                      ->orWhere('lname', 'like', "%{$keyword}%");
                })
                ->orWhere('appointment_date', 'like', "%{$keyword}%");
        } else {
            $query->whereHas('patient', function ($q) use ($keyword) {
                    $q->where('fname', 'like', "%{$keyword}%")
                      ->orWhere('lname', 'like', "%{$keyword}%");
                })
                ->orWhere('appointment_date', 'like', "%{$keyword}%");
        }

        $appointments = $query->with(['patient', 'doctor'])->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
}