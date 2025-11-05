<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search_term') && !empty($request->search_term)) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('fname', 'like', "%{$searchTerm}%")
                  ->orWhere('lname', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->has('role_filter') && !empty($request->role_filter) && $request->role_filter !== 'All Roles') {
            $query->where('role', $request->role_filter);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:100',
            'mname' => 'nullable|string|max:200',
            'lname' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staff,doctor,nurse,patient',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'fname' => $request->fname,
            'mname' => $request->input('mname', ''),
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'specialization' => $request->input('specialization', ''),
            'department' => $request->input('department', ''),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request, $id = null)
    {
        // Handle both POST and GET requests with ID in different places
        $userId = $id ?? $request->input('id') ?? $request->input('user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'error' => 'User ID not provided'
            ], 400);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            if ($request->expectsJson() || $request->isMethod('POST')) {
                return response()->json([
                    'success' => false,
                    'error' => 'User not found'
                ], 404);
            }
            abort(404);
        }

        if ($request->expectsJson() || $request->isMethod('POST')) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Ensure required fields are present for validation
        // Use request value if provided and not empty, otherwise fall back to existing user value
        $request->merge([
            'fname' => $request->filled('fname') ? $request->input('fname') : $user->fname,
            'lname' => $request->filled('lname') ? $request->input('lname') : $user->lname,
            'email' => $request->filled('email') ? $request->input('email') : $user->email,
            'role' => $request->filled('role') ? $request->input('role') : $user->role,
        ]);

        $request->validate([
            'fname' => 'required|string|max:100',
            'mname' => 'nullable|string|max:200',
            'lname' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,staff,doctor,nurse,patient',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
        ]);

        // Build update data - use request values directly (they're already merged with defaults if needed)
        $data = [
            'fname' => $request->input('fname'),
            'mname' => $request->input('mname', $user->mname ?? ''),
            'lname' => $request->input('lname'),
            'email' => $request->input('email'),
            'role' => $request->input('role'), // This will use the new role from the form
            'phone' => $request->input('phone', $user->phone ?? ''),
            'address' => $request->input('address', $user->address ?? ''),
            'specialization' => $request->input('specialization', $user->specialization ?? ''),
            'department' => $request->input('department', $user->department ?? ''),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Check if user has related records
        if ($user->patientAppointments()->count() > 0 ||
            $user->doctorAppointments()->count() > 0 ||
            $user->patientRecord()->exists()) {
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cannot delete user with existing records. Consider deactivating instead.'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Cannot delete user with existing records.');
        }

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Get user statistics.
     */
    public function getStats()
    {
        $stats = [
            'total_users' => User::count(),
            'users_by_role' => User::selectRaw('role, count(*) as count')
                ->groupBy('role')
                ->get()
                ->pluck('count', 'role'),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get patients list.
     */
    public function patients(Request $request)
    {
        $user = Auth::user();
        $query = User::where('role', 'patient');

        // Add search functionality
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('fname', 'like', "%{$keyword}%")
                  ->orWhere('mname', 'like', "%{$keyword}%")
                  ->orWhere('lname', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        // For doctors, get patients who have appointments with them
        if ($user->role === 'doctor') {
            $query->whereHas('patientAppointments', function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            });
        }

        $patients = $query->orderBy('fname')->get();

        // Add additional data for the views
        $patients = $patients->map(function ($patient) {
            $patient->has_record = \App\Models\PatientRecord::where('user_id', $patient->id)->exists();
            if ($patient->role === 'patient' && Auth::user()->role === 'doctor') {
                $lastAppt = $patient->patientAppointments()
                    ->where('doctor_id', Auth::id())
                    ->orderBy('appointment_date', 'desc')
                    ->first();
                $patient->last_appointment = $lastAppt ? $lastAppt->appointment_date->format('Y-m-d') : null;
            }
            return $patient;
        });

        if ($request->expectsJson() || $request->isMethod('GET') && $request->has('keyword')) {
            return response()->json([
                'success' => true,
                'data' => $patients->values()
            ]);
        }

        // Determine which view to use based on the route
        if ($user->role === 'doctor') {
            return view('doctor.patients.index', compact('patients'));
        } elseif ($user->role === 'nurse') {
            return view('nurse.patients.index', compact('patients'));
        }

        return view('patients.index', compact('patients'));
    }
}