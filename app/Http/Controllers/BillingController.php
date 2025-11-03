<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    /**
     * Display a listing of bills.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $bills = [];

        if ($user->role === 'admin' || $user->role === 'staff') {
            $bills = Billing::orderBy('billing_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role === 'patient') {
            $bills = Billing::where('patient_name', $user->full_name)
                ->orderBy('billing_date', 'desc')
                ->get();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $bills
            ]);
        }

        // Use different views based on role
        if ($user->role === 'patient') {
            return view('patient.billing.index', compact('bills'));
        } elseif ($user->role === 'staff') {
            return view('staff.billings.index', compact('bills'));
        } elseif ($user->role === 'admin') {
            return view('admin.billings.index', compact('bills'));
        }

        return view('billings.index', compact('bills'));
    }

    /**
     * Show the form for creating a new bill.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return view('admin.billings.create');
        } elseif ($user->role === 'staff') {
            return view('staff.billings.create');
        }
        
        return view('billings.create');
    }

    /**
     * Store a newly created bill.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'doctor_name' => 'nullable|string|max:255',
            'service' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'billing_date' => 'required|date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,partial,paid,overdue',
        ]);

        $bill = Billing::create([
            'patient_name' => $request->patient_name,
            'doctor_name' => $request->doctor_name ?? '',
            'service' => $request->service,
            'amount' => $request->amount,
            'billing_date' => $request->billing_date,
            'due_date' => $request->due_date,
            'notes' => $request->notes ?? '',
            'status' => $request->status ?? 'pending',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bill created successfully',
                'id' => $bill->id,
                'data' => $bill
            ]);
        }

        $user = Auth::user();
        $routeName = 'billings.index';
        if ($user->role === 'admin') {
            $routeName = 'admin.billings.index';
        } elseif ($user->role === 'staff') {
            $routeName = 'staff.billings.index';
        }
        
        return redirect()->route($routeName)
            ->with('success', 'Bill created successfully!');
    }

    /**
     * Display the specified bill.
     */
    public function show(Request $request, $id = null)
    {
        // Handle both POST and GET requests
        $billingId = $id ?? $request->input('id') ?? $request->input('bill_id');
        
        if (!$billingId) {
            return response()->json([
                'success' => false,
                'error' => 'Bill ID not provided'
            ], 400);
        }
        
        $billing = Billing::find($billingId);
        
        if (!$billing) {
            if ($request->expectsJson() || $request->isMethod('POST')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Bill not found'
                ], 404);
            }
            abort(404);
        }

        if ($request->expectsJson() || $request->isMethod('POST')) {
            return response()->json([
                'success' => true,
                'data' => $billing
            ]);
        }

        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.billings.show', compact('billing'));
        } elseif ($user->role === 'staff') {
            return view('staff.billings.show', compact('billing'));
        } elseif ($user->role === 'patient') {
            return view('patient.billing.show', compact('billing'));
        }

        return view('billings.show', compact('billing'));
    }

    /**
     * Show the form for editing the specified bill.
     */
    public function edit(Billing $billing)
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return view('admin.billings.edit', compact('billing'));
        } elseif ($user->role === 'staff') {
            return view('staff.billings.edit', compact('billing'));
        }
        
        return view('billings.edit', compact('billing'));
    }

    /**
     * Update the specified bill.
     */
    public function update(Request $request, Billing $billing)
    {
        $request->validate([
            'patient_name' => 'sometimes|required|string|max:255',
            'doctor_name' => 'nullable|string|max:255',
            'service' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'billing_date' => 'sometimes|required|date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $billing->update($request->only([
            'patient_name', 'doctor_name', 'service', 'amount',
            'billing_date', 'due_date', 'notes'
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bill updated successfully',
                'data' => $billing
            ]);
        }

        $user = Auth::user();
        $routeName = 'billings.index';
        if ($user->role === 'admin') {
            $routeName = 'admin.billings.index';
        } elseif ($user->role === 'staff') {
            $routeName = 'staff.billings.index';
        }
        
        return redirect()->route($routeName)
            ->with('success', 'Bill updated successfully!');
    }

    /**
     * Update bill status.
     */
    public function updateStatus(Request $request, Billing $billing)
    {
        $request->validate([
            'status' => 'required|in:pending,partial,paid,overdue',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $billing->update([
            'status' => $request->status,
            'payment_method' => $request->payment_method,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $billing
            ]);
        }

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    /**
     * Remove the specified bill.
     */
    public function destroy(Billing $billing)
    {
        $billing->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bill deleted successfully'
            ]);
        }

        $user = Auth::user();
        $routeName = 'billings.index';
        if ($user->role === 'admin') {
            $routeName = 'admin.billings.index';
        } elseif ($user->role === 'staff') {
            $routeName = 'staff.billings.index';
        }
        
        return redirect()->route($routeName)
            ->with('success', 'Bill deleted successfully!');
    }

    /**
     * Search bills.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword', $request->get('search_term', ''));
        $status = $request->get('status', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $query = Billing::query();

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('patient_name', 'like', "%{$keyword}%")
                  ->orWhere('doctor_name', 'like', "%{$keyword}%")
                  ->orWhere('service', 'like', "%{$keyword}%");
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($dateFrom)) {
            $query->where('billing_date', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->where('billing_date', '<=', $dateTo);
        }

        $bills = $query->orderBy('billing_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $bills
        ]);
    }

    /**
     * Get bills by status.
     */
    public function getBillsByStatus($status)
    {
        $bills = Billing::where('status', $status)
            ->orderBy('billing_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bills
        ]);
    }

    /**
     * Get billing statistics.
     */
    public function getStats()
    {
        $totalCount = Billing::count();
        $totalAmount = Billing::sum('amount');
        $paidAmount = Billing::where('status', 'paid')->sum('amount');
        $pendingAmount = Billing::where('status', 'pending')->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_count' => $totalCount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'pending_amount' => $pendingAmount,
            ]
        ]);
    }
}