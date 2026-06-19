<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\VehicleExpense;

class VehicleExpenseController extends Controller
{
    public function index()
    {
        $query = VehicleExpense::with('employee')->orderBy('created_at', 'desc');

        if (Auth::user()->role !== 'admin') {
            $query->where('employee_branch', Auth::user()->branch);
        }

        $expenses = $query->get();

        return view('content.pages.pages-vehiclesexpenses', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'expense_type' => 'nullable|string|max:255',
            'expense_date' => 'nullable|date',
            'vendor_name' => 'nullable|string|max:255',
            'bill_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'expense_description' => 'nullable|string',
            'payment_type' => 'nullable|string|max:255',
            'expense_amount' => 'nullable|numeric',
            'expense_status' => 'nullable|string|max:255',
        ]);

        $validated['employee_id'] = Auth::id();
        $validated['employee_name'] = Auth::user()->name;
        $validated['employee_branch'] = Auth::user()->branch;

        if ($request->hasFile('bill_image')) {
            $file = $request->file('bill_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $filename);
            $validated['bill_image'] = 'uploads/expenses/' . $filename;
        }

        $expense = VehicleExpense::create($validated);
        $expense->employee_branch = Auth::user()->branch;

        return response()->json(['status' => 'success', 'expense' => $expense]);
    }

    public function update(Request $request, VehicleExpense $expense)
    {
        $validated = $request->validate([
            'vehicle_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'expense_type' => 'nullable|string|max:255',
            'expense_date' => 'nullable|date',
            'vendor_name' => 'nullable|string|max:255',
            'bill_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'expense_description' => 'nullable|string',
            'payment_type' => 'nullable|string|max:255',
            'expense_amount' => 'nullable|numeric',
            'expense_status' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('bill_image')) {
            $file = $request->file('bill_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $filename);
            $validated['bill_image'] = 'uploads/expenses/' . $filename;
        }

        $validated['employee_branch'] = Auth::user()->branch;

        $expense->update($validated);
        $expense->load('employee');
        $expense->employee_branch = $expense->employee?->branch;

        return response()->json(['status' => 'success', 'expense' => $expense]);
    }
}
