<?php

namespace App\Http\Controllers;

use App\Models\PatientRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PatientRecordController extends Controller
{
    /**
     * Display a listing of patient records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $records = [];

        if ($user->role === 'admin' || $user->role === 'staff' || $user->role === 'doctor') {
            $records = PatientRecord::with('user')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role === 'patient') {
            $records = PatientRecord::with('user')
                ->where('user_id', $user->id)
                ->get();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $records
            ]);
        }

        // Use different views based on role
        if ($user->role === 'patient') {
            return view('patient.records.index', compact('records'));
        } elseif ($user->role === 'staff') {
            return view('staff.records.index', compact('records'));
        } elseif ($user->role === 'admin') {
            return view('admin.records.index', compact('records'));
        }

        // Default to admin view if role not recognized
        return view('admin.records.index', compact('records'));
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $patients = User::where('role', 'patient')->orderBy('fname')->get();
        $user = Auth::user();
        
        // Use different views based on role
        if ($user->role === 'admin') {
            return view('admin.records.create', compact('patients'));
        }
        
        return view('records.create', compact('patients'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $record = PatientRecord::create($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient record created successfully',
                'data' => $record->load('user')
            ]);
        }

        $user = Auth::user();
        $routeName = 'admin.records.index';
        if ($user->role === 'admin') {
            $routeName = 'admin.records.index';
        } elseif ($user->role === 'staff') {
            $routeName = 'staff.records.index';
        }
        
        return redirect()->route($routeName)
            ->with('success', 'Patient record created successfully!');
    }

    /**
     * Display the specified record.
     */
    public function show(Request $request, $id = null)
    {
        // Handle both POST and GET requests
        $recordId = $id ?? $request->input('id') ?? $request->input('record_id');
        
        if (!$recordId) {
            return response()->json([
                'success' => false,
                'error' => 'Record ID not provided'
            ], 400);
        }
        
        $record = PatientRecord::with('user')->find($recordId);
        
        if (!$record) {
            if ($request->expectsJson() || $request->isMethod('POST')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Record not found'
                ], 404);
            }
            abort(404);
        }

        if ($request->expectsJson() || $request->isMethod('POST')) {
            return response()->json([
                'success' => true,
                'data' => $record
            ]);
        }

        return view('records.show', compact('record'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, PatientRecord $record)
    {
        $request->validate([
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $record->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient record updated successfully',
                'data' => $record->load('user')
            ]);
        }

        $user = Auth::user();
        $routeName = 'admin.records.index';
        if ($user->role === 'admin') {
            $routeName = 'admin.records.index';
        } elseif ($user->role === 'staff') {
            $routeName = 'staff.records.index';
        }
        
        return redirect()->route($routeName)
            ->with('success', 'Patient record updated successfully!');
    }

    /**
     * Remove the specified record.
     */
    public function destroy(PatientRecord $record)
    {
        $record->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient record deleted successfully'
            ]);
        }

        $user = Auth::user();
        $routeName = 'admin.records.index';
        if ($user->role === 'admin') {
            $routeName = 'admin.records.index';
        } elseif ($user->role === 'staff') {
            $routeName = 'staff.records.index';
        }
        
        return redirect()->route($routeName)
            ->with('success', 'Patient record deleted successfully!');
    }

    /**
     * Search patient records.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword', $request->get('search_name', ''));
        $bloodType = $request->get('blood_type', '');
        $department = $request->get('department', '');

        $query = PatientRecord::with('user');

        if (!empty($keyword)) {
            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where('fname', 'like', "%{$keyword}%")
                  ->orWhere('mname', 'like', "%{$keyword}%")
                  ->orWhere('lname', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        if (!empty($bloodType)) {
            $query->where('blood_type', $bloodType);
        }

        if (!empty($department)) {
            $query->whereHas('user', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        $records = $query->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    /**
     * Get patient record by patient/user ID.
     */
    public function getByPatientId(Request $request, $patientId)
    {
        $record = PatientRecord::with('user')
            ->where('user_id', $patientId)
            ->first();

        if (!$record) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Patient record not found'
                ], 404);
            }
            
            // Return view with message if no record exists
            $patient = User::find($patientId);
            return view('records.show', compact('patient'))->with('message', 'No medical records found for this patient.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $record
            ]);
        }

        return view('records.show', compact('record'));
    }
}