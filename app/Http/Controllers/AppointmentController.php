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
        $user = Auth::user();
        
        // Use different views based on role
        if ($user->role === 'patient') {
            return view('patient.appointments.create', compact('doctors'));
        }
        
        return view('appointments.create', compact('doctors'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'nullable|string|max:500',
        ]);

        $patientId = Auth::user()->role === 'patient' ? Auth::id() : $request->patient_id;

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

        $user = Auth::user();
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
     * Update the specified appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes',
            'reason' => 'nullable|string|max:500',
        ]);

        $appointment->update($request->only([
            'status', 'appointment_date', 'appointment_time', 'reason'
        ]));

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