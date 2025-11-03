<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'total_bills' => Billing::count(),
            'pending_bills' => Billing::where('status', 'pending')->count(),
            'total_wards' => Ward::count(),
            'recent_appointments' => Appointment::with(['patient', 'doctor'])
                ->latest()
                ->limit(5)
                ->get(),
            'recent_bills' => Billing::latest()
                ->limit(5)
                ->get(),
        ];

        return view('staff.dashboard', compact('stats'));
    }
}