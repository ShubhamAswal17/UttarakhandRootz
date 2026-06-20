<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\bookings;
use App\Models\Vehicle;
use App\Models\Maintenance;
use App\Models\OfficeExpense;

class adminController extends Controller
{
   public function index()
{
    $totalRevenue = bookings::where('status', 'completed')
        ->sum('amount');

    $maintenanceExpense = Maintenance::sum('service_amount');

    $officeExpense = OfficeExpense::sum('expense_amount');

    $totalExpense = $maintenanceExpense + $officeExpense;
   
    $totalProfit = $totalRevenue - $totalExpense;

    $lastWeekRevenue = bookings::where('status', 'completed')
    ->whereDate('created_at', '>=', now()->subDays(7))
    ->sum('amount');

    $totalBookings = bookings::count();

    $completedBookings = bookings::where('status', 'completed')->count();

    $totalVehicles = Vehicle::count();

    // Current Month Revenue
    $currentMonthRevenue = bookings::where('status', 'completed')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('amount');

    // Last Month Revenue
    $lastMonthRevenue = bookings::where('status', 'completed')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->sum('amount');

    $revenueGrowth = $lastMonthRevenue > 0
        ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | YEARLY CHART DATA
    |--------------------------------------------------------------------------
    */

    $monthlyRevenue = [];
    $monthlyProfit = [];
    $monthlyBookings = [];
    $monthlyExpenses = [];

    for ($month = 1; $month <= 12; $month++) {

        $revenue = bookings::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $maintenance = Maintenance::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->sum('service_amount');

        $office = OfficeExpense::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->sum('expense_amount');

        $expense = $maintenance + $office;

        $bookings = bookings::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->count();

        $monthlyRevenue[] = $revenue;
        $monthlyProfit[] = $revenue - $expense;
        $monthlyExpenses[] = $expense;
        $monthlyBookings[] = $bookings;
    }

    return view('content.pages.pages-admin-dashboard', compact(
        'totalRevenue',
        'lastWeekRevenue',
        'totalExpense',
        'totalProfit',
        'totalBookings',
        'completedBookings',
        'totalVehicles',
        'revenueGrowth',
        'monthlyRevenue',
        'monthlyProfit',
        'monthlyExpenses',
        'monthlyBookings'
    ));
}
}
