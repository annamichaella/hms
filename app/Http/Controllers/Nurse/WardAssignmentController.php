<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\User;
use App\Models\PatientAdmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WardAssignmentController extends Controller
{
    public function index()
    {
        $beds = Bed::with(['ward', 'patient'])
            ->get()
            ->map(function($bed) {
                return [
                    'id' => $bed->id,
                    'ward_name' => $bed->ward->ward_name,
                    'bed_number' => $bed->bed_number,
                    'bed_type' => $bed->bed_type,
                    'status' => $bed->status,
                    'fname' => $bed->patient ? $bed->patient->fname : null,
                    'lname' => $bed->patient ? $bed->patient->lname : null,
                ];
            });

        $stats = [
            'available_beds' => Bed::where('status', 'Available')->count(),
            'occupied_beds' => Bed::where('status', 'Occupied')->count(),
            'total_beds' => Bed::count(),
        ];

        return view('nurse.ward-assignments', compact('beds', 'stats'));
    }

    public function getAvailableBeds()
    {
        $beds = Bed::with('ward')
            ->where('status', 'Available')
            ->get()
            ->map(function($bed) {
                return [
                    'id' => $bed->id,
                    'ward_name' => $bed->ward->ward_name,
                    'bed_number' => $bed->bed_number,
                    'bed_type' => $bed->bed_type,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $beds
        ]);
    }

    public function getPatients()
    {
        $patients = User::where('role', 'patient')
            ->select('id', 'fname', 'lname')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    public function assignPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bed_id' => 'required|exists:beds,id',
            'patient_id' => 'required|exists:users,id',
            'admission_reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $bed = Bed::findOrFail($request->bed_id);

        if ($bed->status !== 'Available') {
            return response()->json([
                'success' => false,
                'error' => 'Bed is not available'
            ], 422);
        }

        // Check if patient is already admitted
        $existingAdmission = PatientAdmission::where('patient_id', $request->patient_id)
            ->where('status', 'Admitted')
            ->exists();

        if ($existingAdmission) {
            return response()->json([
                'success' => false,
                'error' => 'Patient is already admitted'
            ], 422);
        }

        // Update bed status
        $bed->update([
            'status' => 'Occupied',
            'patient_id' => $request->patient_id,
            'admission_date' => now(),
        ]);

        // Create admission record
        PatientAdmission::create([
            'patient_id' => $request->patient_id,
            'bed_id' => $request->bed_id,
            'admission_date' => now(),
            'admission_reason' => $request->admission_reason,
            'status' => 'Admitted',
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient assigned to bed successfully'
        ]);
    }

    public function dischargePatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bed_id' => 'required|exists:beds,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $bed = Bed::findOrFail($request->bed_id);

        if ($bed->status !== 'Occupied') {
            return response()->json([
                'success' => false,
                'error' => 'Bed is not occupied'
            ], 422);
        }

        // Update admission record
        $admission = PatientAdmission::where('bed_id', $request->bed_id)
            ->where('status', 'Admitted')
            ->first();

        if ($admission) {
            $admission->update([
                'status' => 'Discharged',
                'discharge_date' => now(),
            ]);
        }

        // Update bed status
        $bed->update([
            'status' => 'Available',
            'patient_id' => null,
            'admission_date' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient discharged successfully'
        ]);
    }

    public function getWardStats()
    {
        $stats = [
            'available_beds' => Bed::where('status', 'Available')->count(),
            'occupied_beds' => Bed::where('status', 'Occupied')->count(),
            'total_beds' => Bed::count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}