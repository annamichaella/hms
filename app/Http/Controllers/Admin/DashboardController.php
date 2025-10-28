<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\Ward;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_appointments' => Appointment::count(),
            'total_billings' => Billing::count(),
            'total_wards' => Ward::count(),
            'recent_appointments' => Appointment::with(['patient', 'doctor'])
                ->latest()
                ->limit(5)
                ->get(),
            'users_by_role' => User::selectRaw('role, count(*) as count')
                ->groupBy('role')
                ->get()
                ->pluck('count', 'role'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
