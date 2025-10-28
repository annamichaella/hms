<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\PatientRecord;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'upcoming_appointments' => Appointment::where('patient_id', $user->id)
                ->where('appointment_date', '>=', now())
                ->where('status', '!=', 'cancelled')
                ->with('doctor')
                ->orderBy('appointment_date')
                ->limit(5)
                ->get(),
            'total_appointments' => Appointment::where('patient_id', $user->id)->count(),
            'pending_bills' => Billing::where('patient_name', $user->full_name)
                ->where('status', 'pending')
                ->count(),
            'patient_record' => PatientRecord::where('user_id', $user->id)->first(),
        ];

        return view('patient.dashboard', compact('stats'));
    }
}
