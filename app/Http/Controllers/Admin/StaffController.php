<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = User::whereIn('role', ['staff', 'doctor', 'nurse'])
            ->orderBy('fname')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $staff
            ]);
        }

        return view('admin.staff.index', compact('staff'));
    }
}
