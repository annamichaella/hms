<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor', 'nurse']);

        // Add search functionality
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('patient', function ($q2) use ($keyword) {
                    $q2->where('fname', 'like', "%{$keyword}%")
                       ->orWhere('lname', 'like', "%{$keyword}%");
                })
                ->orWhereHas('doctor', function ($q2) use ($keyword) {
                    $q2->where('fname', 'like', "%{$keyword}%")
                       ->orWhere('lname', 'like', "%{$keyword}%");
                })
                ->orWhere('appointment_date', 'like', "%{$keyword}%");
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        if ($request->expectsJson() || ($request->isMethod('GET') && $request->has('keyword'))) {
            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        }

        return view('staff.assignments', compact('appointments'));
    }

    public function getNurses()
    {
        $nurses = User::where('role', 'nurse')
            ->select('id', 'fname', 'lname')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $nurses
        ]);
    }

    public function getDoctors()
    {
        $doctors = User::where('role', 'doctor')
            ->select('id', 'fname', 'lname')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }

    public function assignNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'nurse_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $appointment = Appointment::findOrFail($request->appointment_id);

        $appointment->update([
            'nurse_id' => $request->nurse_id,
            'assigned_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nurse assigned successfully'
        ]);
    }

    public function reassignDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $appointment = Appointment::findOrFail($request->appointment_id);

        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'assigned_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Doctor reassigned successfully'
        ]);
    }

    public function assignRoom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'room_id' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // For now, we'll store room_id in a custom field or notes
        // In a real application, you might want to create a separate rooms table
        $appointment = Appointment::findOrFail($request->appointment_id);

        $appointment->update([
            'assigned_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Room assigned successfully'
        ]);
    }

    public function getAppointmentDetails(Request $request)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'nurse'])
            ->findOrFail($request->appointment_id);

        $data = [
            'id' => $appointment->id,
            'patient_fname' => $appointment->patient ? $appointment->patient->fname : null,
            'patient_lname' => $appointment->patient ? $appointment->patient->lname : null,
            'doctor_fname' => $appointment->doctor ? $appointment->doctor->fname : null,
            'doctor_lname' => $appointment->doctor ? $appointment->doctor->lname : null,
            'nurse_fname' => $appointment->nurse ? $appointment->nurse->fname : null,
            'nurse_lname' => $appointment->nurse ? $appointment->nurse->lname : null,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $appointment->appointment_time->format('H:i'),
            'status' => $appointment->status,
            'reason' => $appointment->reason,
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}