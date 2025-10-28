<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::where('doctor_id', Auth::id())
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedule', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for overlapping schedules
        $overlapping = DoctorSchedule::where('doctor_id', Auth::id())
            ->where('day', $request->day)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($overlapping) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule conflicts with existing time slot'
            ], 422);
        }

        $schedule = DoctorSchedule::create([
            'doctor_id' => Auth::id(),
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule added successfully',
            'data' => $schedule
        ]);
    }

    public function update(Request $request, $id)
    {
        $schedule = DoctorSchedule::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for overlapping schedules (excluding current one)
        $overlapping = DoctorSchedule::where('doctor_id', Auth::id())
            ->where('day', $request->day)
            ->where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($overlapping) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule conflicts with existing time slot'
            ], 422);
        }

        $schedule->update([
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule updated successfully',
            'data' => $schedule
        ]);
    }

    public function destroy($id)
    {
        $schedule = DoctorSchedule::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->firstOrFail();

        $schedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $query = DoctorSchedule::where('doctor_id', Auth::id());

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('day', 'like', "%{$search}%")
                  ->orWhere('start_time', 'like', "%{$search}%")
                  ->orWhere('end_time', 'like', "%{$search}%");
            });
        }

        $schedules = $query->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $schedules
        ]);
    }
}