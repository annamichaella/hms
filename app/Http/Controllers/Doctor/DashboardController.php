<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'todays_appointments' => Appointment::where('doctor_id', $user->id)
                ->where('appointment_date', today())
                ->where('status', '!=', 'cancelled')
                ->with('patient')
                ->orderBy('appointment_time')
                ->get(),
            'total_appointments' => Appointment::where('doctor_id', $user->id)->count(),
            'pending_appointments' => Appointment::where('doctor_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'upcoming_appointments' => Appointment::where('doctor_id', $user->id)
                ->where('appointment_date', '>=', today())
                ->where('status', '!=', 'cancelled')
                ->with('patient')
                ->orderBy('appointment_date')
                ->limit(5)
                ->get(),
        ];

        return view('doctor.dashboard', compact('stats'));
    }
}