<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Bed;
use App\Models\PatientAdmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WardController extends Controller
{
    /**
     * Display a listing of wards.
     */
    public function index(Request $request)
    {
        $wards = Ward::withCount([
            'beds as total_beds',
            'beds as occupied_beds' => function ($query) {
                $query->where('status', 'Occupied');
            },
            'beds as available_beds' => function ($query) {
                $query->where('status', 'Available');
            }
        ])->orderBy('ward_name')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $wards
            ]);
        }

        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.wards.index', compact('wards'));
        } elseif ($user->role === 'nurse') {
            return view('nurse.wards.index', compact('wards'));
        } elseif ($user->role === 'staff') {
            return view('staff.wards.index', compact('wards'));
        }

        return view('wards.index', compact('wards'));
    }

    /**
     * Show the form for creating a new ward.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.wards.create');
        }

        return view('wards.create');
    }

    /**
     * Store a newly created ward.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ward_name' => 'required|string|max:100',
            'ward_type' => 'required|in:General,ICU,Emergency,Surgery,Pediatric,Maternity',
            'floor' => 'required|string|max:20',
            'capacity' => 'required|integer|min:0',
            'status' => 'nullable|in:Active,Maintenance,Closed',
        ]);

        $ward = Ward::create([
            'ward_name' => $request->ward_name,
            'ward_type' => $request->ward_type,
            'floor' => $request->floor,
            'capacity' => $request->capacity,
            'status' => $request->status ?? 'Active',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ward added successfully',
                'data' => $ward
            ]);
        }

        $user = Auth::user();
        $routeName = $user->role === 'admin' ? 'admin.wards.index' : 'wards.index';
        
        return redirect()->route($routeName)
            ->with('success', 'Ward added successfully!');
    }

    /**
     * Display the specified ward.
     */
    public function show(Ward $ward)
    {
        $ward->load(['beds.patient']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $ward
            ]);
        }

        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.wards.show', compact('ward'));
        }

        return view('wards.show', compact('ward'));
    }

    /**
     * Show the form for editing the specified ward.
     */
    public function edit(Ward $ward)
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.wards.edit', compact('ward'));
        }

        return view('wards.edit', compact('ward'));
    }

    /**
     * Update the specified ward.
     */
    public function update(Request $request, Ward $ward)
    {
        $request->validate([
            'ward_name' => 'required|string|max:100',
            'ward_type' => 'required|in:General,ICU,Emergency,Surgery,Pediatric,Maternity',
            'floor' => 'required|string|max:20',
            'capacity' => 'required|integer|min:0',
            'status' => 'nullable|in:Active,Maintenance,Closed',
        ]);

        $ward->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ward updated successfully',
                'data' => $ward
            ]);
        }

        $user = Auth::user();
        $routeName = $user->role === 'admin' ? 'admin.wards.index' : 'wards.index';
        
        return redirect()->route($routeName)->with('success', 'Ward updated successfully!');
    }

    /**
     * Remove the specified ward.
     */
    public function destroy(Ward $ward)
    {
        // Check if ward has occupied beds
        if ($ward->beds()->where('status', 'Occupied')->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cannot delete ward with occupied beds'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Cannot delete ward with occupied beds');
        }

        $ward->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ward deleted successfully'
            ]);
        }

        $user = Auth::user();
        $routeName = $user->role === 'admin' ? 'admin.wards.index' : 'wards.index';
        
        return redirect()->route($routeName)
            ->with('success', 'Ward deleted successfully!');
    }

    /**
     * Get beds for a ward.
     */
    public function getBeds(Ward $ward)
    {
        $beds = Bed::with(['ward', 'patient'])
            ->where('ward_id', $ward->id)
            ->orderBy('bed_number')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $beds
        ]);
    }

    /**
     * Store a newly created bed.
     */
    public function storeBed(Request $request)
    {
        $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'bed_number' => 'required|string|max:20',
            'bed_type' => 'required|in:Standard,ICU,Private,Semi-Private',
        ]);

        $bed = Bed::create([
            'ward_id' => $request->ward_id,
            'bed_number' => $request->bed_number,
            'bed_type' => $request->bed_type,
            'status' => 'Available',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bed added successfully',
            'data' => $bed
        ]);
    }

    /**
     * Update the specified bed.
     */
    public function updateBed(Request $request, Bed $bed)
    {
        $request->validate([
            'bed_number' => 'required|string|max:20',
            'bed_type' => 'required|in:Standard,ICU,Private,Semi-Private',
            'status' => 'required|in:Available,Occupied,Maintenance,Reserved',
        ]);

        $bed->update($request->only(['bed_number', 'bed_type', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Bed updated successfully',
            'data' => $bed
        ]);
    }

    /**
     * Remove the specified bed.
     */
    public function destroyBed(Bed $bed)
    {
        if ($bed->status === 'Occupied') {
            return response()->json([
                'success' => false,
                'error' => 'Cannot delete occupied bed'
            ], 400);
        }

        $bed->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bed deleted successfully'
        ]);
    }

    /**
     * Assign patient to bed.
     */
    public function assignPatient(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_reason' => 'nullable|string',
        ]);

        $bed = Bed::findOrFail($request->bed_id);

        if ($bed->status !== 'Available') {
            return response()->json([
                'success' => false,
                'error' => 'Bed is not available'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $bed->update([
                'status' => 'Occupied',
                'patient_id' => $request->patient_id,
                'admission_date' => now(),
            ]);

            PatientAdmission::create([
                'patient_id' => $request->patient_id,
                'bed_id' => $request->bed_id,
                'admission_reason' => $request->admission_reason,
                'created_by' => Auth::id(),
                'status' => 'Admitted',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Patient assigned to bed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to assign patient'
            ], 500);
        }
    }

    /**
     * Discharge patient from bed.
     */
    public function dischargePatient(Request $request)
    {
        $request->validate([
            'bed_id' => 'required|exists:beds,id',
        ]);

        $bed = Bed::findOrFail($request->bed_id);

        DB::beginTransaction();
        try {
            $bed->update([
                'status' => 'Available',
                'patient_id' => null,
                'admission_date' => null,
            ]);

            PatientAdmission::where('bed_id', $request->bed_id)
                ->where('status', 'Admitted')
                ->update([
                    'discharge_date' => now(),
                    'status' => 'Discharged',
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Patient discharged successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to discharge patient'
            ], 500);
        }
    }

    /**
     * Get available beds.
     */
    public function getAvailableBeds()
    {
        $beds = Bed::with('ward')
            ->where('status', 'Available')
            ->whereHas('ward', function ($query) {
                $query->where('status', 'Active');
            })
            ->orderBy('ward_id')
            ->orderBy('bed_number')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $beds
        ]);
    }

    /**
     * Get ward statistics.
     */
    public function getStats()
    {
        $stats = [
            'total_wards' => Ward::where('status', 'Active')->count(),
            'total_beds' => Bed::count(),
            'available_beds' => Bed::where('status', 'Available')->count(),
            'occupied_beds' => Bed::where('status', 'Occupied')->count(),
            'maintenance_beds' => Bed::where('status', 'Maintenance')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}