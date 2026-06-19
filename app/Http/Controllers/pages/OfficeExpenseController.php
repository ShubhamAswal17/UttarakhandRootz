<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfficeExpense;
use Illuminate\Support\Facades\Auth;

class OfficeExpenseController extends Controller
{
    public function index()
    {
        $query = OfficeExpense::orderBy('created_at', 'desc');

        // If user is not admin, show only their branch data
        if (Auth::user()->role !== 'admin') {
            $query->where('manager_branch', Auth::user()->branch);
        }

        $expenses = $query->get();

        return view('content.pages.pages-officeexpenses', compact('expenses'));
    }

    public function store(Request $request)
    {
        // Check if user is admin or manager
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            return response()->json(['status' => 'error', 'message' => 'Only admins and managers can create office expenses'], 403);
        }

        // Check if salary expense is being created by non-admin
        if ($request->input('expense_type') === 'salary' && Auth::user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Only admins can create salary expenses'], 403);
        }

        $validated = $request->validate([
            'expense_type' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'vendor_number' => 'nullable|string|max:255',
            'bill_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'expense_date' => 'nullable|date',
            'expense_description' => 'nullable|string',
            'payment_type' => 'nullable|string|max:255',
            'expense_amount' => 'nullable|numeric',
            'expense_status' => 'nullable|string|max:255',
        ]);

        // Auto-fill manager_id and manager_name from authenticated user
        $validated['manager_id'] = Auth::id();
        $validated['manager_name'] = Auth::user()->name;
        $validated['manager_branch'] = Auth::user()->branch;

        if ($request->hasFile('bill_image')) {
            $file = $request->file('bill_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $filename);
            $validated['bill_image'] = 'uploads/expenses/' . $filename;
        }

        $expense = OfficeExpense::create($validated);

        return response()->json(['status' => 'success', 'expense' => $expense]);
    }

    public function update(Request $request, OfficeExpense $expense)
    {
        // Check if user is admin or manager
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            return response()->json(['status' => 'error', 'message' => 'Only admins and managers can edit office expenses'], 403);
        }

        // Check if trying to change to salary or expense was salary and trying to update
        if (($request->input('expense_type') === 'salary' || $expense->expense_type === 'salary') && Auth::user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Only admins can edit salary expenses'], 403);
        }

        $validated = $request->validate([
            'expense_type' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'vendor_number' => 'nullable|string|max:255',
            'bill_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:4096',
            'expense_date' => 'nullable|date',
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

        $validated['manager_branch'] = Auth::user()->branch;

        $expense->update($validated);

        return response()->json(['status' => 'success', 'expense' => $expense]);
    }
}
