<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\PatientAdmission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_wards' => Ward::where('status', 'Active')->count(),
            'total_beds' => Bed::count(),
            'occupied_beds' => Bed::where('status', 'Occupied')->count(),
            'available_beds' => Bed::where('status', 'Available')->count(),
            'recent_admissions' => PatientAdmission::with(['patient', 'bed.ward'])
                ->where('status', 'Admitted')
                ->orderBy('admission_date', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('nurse.dashboard', compact('stats'));
    }
}