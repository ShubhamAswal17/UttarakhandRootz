<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\bookings;
use App\Models\customers;;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Maintenance;
use App\Models\OfficeExpense;
use App\Models\VehicleExpense;

class HomePage extends Controller
{
    public function index()
    {
        $manager = Auth::user();
        $branch = $manager->branch;


         /*
        |--------------------------------------------------------------------------
        | Current week Statistics
        |--------------------------------------------------------------------------
        */

        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        
        $currentWeekRevenue = Bookings::where('branch', $branch)
          ->where('status', 'completed')
          ->whereBetween('booking_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('amount');

        $currentWeekBookings = Bookings::where('branch', $branch)
          ->whereBetween('booking_date', [$currentWeekStart, $currentWeekEnd])
          ->count();

        $currentWeekCustomers = Bookings::where('branch', $branch)
          ->whereBetween('booking_date', [$currentWeekStart, $currentWeekEnd])
          ->distinct('customer_id')
          ->count('customer_id');
        $currentWeekVehiclesAdded = Vehicle::where('branch', $branch)
          ->whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
          ->count();
        
        $currentWeekMaintenance = Maintenance::where('branch', $branch)
          ->whereBetween('service_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('service_amount');

        $currentWeekOfficeExpense = OfficeExpense::where('manager_branch', $branch)
          ->whereBetween('expense_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('expense_amount');

        $currentWeekVehicleExpense = VehicleExpense::where('employee_branch', $branch)
          ->whereBetween('expense_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('expense_amount');

        $currentWeekExpense =$currentWeekMaintenance +$currentWeekOfficeExpense +$currentWeekVehicleExpense;

        /*
        |--------------------------------------------------------------------------
        | Current Month Statistics
        |--------------------------------------------------------------------------
        */

        $currentMonthBookings = bookings::where('branch', $branch)
            ->where('status', 'completed')
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->get();


        $currentMonthCustomers = bookings::where('branch', $branch)
          ->whereBetween('booking_date', [now()->startOfMonth(),now()->endOfMonth()])
          ->distinct('customer_id')
          ->count('customer_id');

        $totalVehicles = Vehicle::where('branch', $branch)->count();

        
        $currentMonthRevenue = bookings::where('branch', $branch)
            ->where('status', 'completed')
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->sum('amount');

        $branchEmployees = User::where('branch', $branch)
            ->where('role', 'employee')
            ->get();


        $secondLastMonthRevenue = bookings::where('branch', $branch)
        ->where('status', 'completed')
        ->whereMonth('booking_date', now()->subMonths(2)->month)
        ->whereYear('booking_date', now()->subMonths(2)->year)
        ->sum('amount');

        $lastMonthRevenue = bookings::where('branch', $branch)
        ->where('status', 'completed')
        ->whereMonth('booking_date', now()->subMonth()->month)
        ->whereYear('booking_date', now()->subMonth()->year)
        ->sum('amount');

        $monthRevenueDifference = $lastMonthRevenue - $secondLastMonthRevenue;

        // Growth Percentage
        if ($secondLastMonthRevenue > 0) {
          $MonthlyRevenueGrowthPercent = ($monthRevenueDifference / $secondLastMonthRevenue) * 100;
        } else {
          $MonthlyRevenueGrowthPercent = $lastMonthRevenue > 0 ? 100 : 0;
        }


        $currentMonthMaintenance = Maintenance::where('branch', $branch)
          ->whereMonth('service_date', now()->month)
          ->whereYear('service_date', now()->year)
          ->sum('service_amount');

        $currentMonthOfficeExpense = OfficeExpense::where('manager_branch', $branch)
          ->whereMonth('expense_date', now()->month)
          ->whereYear('expense_date', now()->year)
          ->sum('expense_amount');

        $currentMonthVehicleExpense = VehicleExpense::where('employee_branch', $branch)
          ->whereMonth('expense_date', now()->month)
          ->whereYear('expense_date', now()->year)
          ->sum('expense_amount');

        $currentMonthExpense = $currentMonthMaintenance + $currentMonthOfficeExpense + $currentMonthVehicleExpense;


// Last Month
      $lastMonthMaintenance = Maintenance::where('branch', $branch)
        ->whereMonth('service_date', now()->subMonth()->month)
        ->whereYear('service_date', now()->subMonth()->year)
        ->sum('service_amount');

      $lastMonthOfficeExpense = OfficeExpense::where('manager_branch', $branch)
        ->whereMonth('expense_date', now()->subMonth()->month)
        ->whereYear('expense_date', now()->subMonth()->year)
        ->sum('expense_amount');

      $lastMonthVehicleExpense = VehicleExpense::where('employee_branch', $branch)
        ->whereMonth('expense_date', now()->subMonth()->month)
        ->whereYear('expense_date', now()->subMonth()->year)
        ->sum('expense_amount');

      $lastMonthExpense = $lastMonthMaintenance + $lastMonthOfficeExpense + $lastMonthVehicleExpense;


// Second Last Month

      $secondLastMonthMaintenance = Maintenance::where('branch', $branch)
        ->whereMonth('service_date', now()->subMonths(2)->month)
        ->whereYear('service_date', now()->subMonths(2)->year)
        ->sum('service_amount');

      $secondLastMonthOfficeExpense = OfficeExpense::where('manager_branch', $branch)
        ->whereMonth('expense_date', now()->subMonths(2)->month)
        ->whereYear('expense_date', now()->subMonths(2)->year)
        ->sum('expense_amount');

      $secondLastMonthVehicleExpense = VehicleExpense::where('employee_branch', $branch)
        ->whereMonth('expense_date', now()->subMonths(2)->month)
        ->whereYear('expense_date', now()->subMonths(2)->year)
        ->sum('expense_amount');

      $secondLastMonthExpense =$secondLastMonthMaintenance + $secondLastMonthOfficeExpense +$secondLastMonthVehicleExpense;

      $monthlyExpenseDifference = $lastMonthExpense - $secondLastMonthExpense;

      $monthlyExpenseGrowthPercent = $secondLastMonthExpense > 0
            ? round(($monthlyExpenseDifference / $secondLastMonthExpense) * 100, 2): 0;
         
       
        /*
        |--------------------------------------------------------------------------
        | Revenue Comparison
        | Last Week vs Previous Week
        |--------------------------------------------------------------------------
        */
// Last Week (Previous Calendar Week)
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();

        $lastWeekRevenue = bookings::where('branch', $branch)
          ->where('status', 'completed')
          ->whereBetween('return_date', [$lastWeekStart, $lastWeekEnd])
          ->sum('amount');


// Second Last Week
        $secondLastWeekStart = now()->subWeeks(2)->startOfWeek();
        $secondLastWeekEnd = now()->subWeeks(2)->endOfWeek();

        $previousWeekRevenue = bookings::where('branch', $branch)
          ->where('status', 'completed')
          ->whereBetween('return_date', [$secondLastWeekStart, $secondLastWeekEnd])
          ->sum('amount');
// Growth %
        $weeklyRevenueDifference = $lastWeekRevenue - $previousWeekRevenue;

        $weeklyRevenueGrowthPercent = $previousWeekRevenue > 0
        ? round(($weeklyRevenueDifference / $previousWeekRevenue) * 100, 2)
        : 0;

        /*
        |--------------------------------------------------------------------------
        | Expense Comparison
        | Last Week vs Previous Week
        |--------------------------------------------------------------------------
        */

       // Last Week (Previous Calendar Week)
      $lastWeekStart = now()->subWeek()->startOfWeek();
      $lastWeekEnd = now()->subWeek()->endOfWeek();

      $lastWeekMaintenance = Maintenance::where('branch', $branch)
        ->whereBetween('service_date', [$lastWeekStart, $lastWeekEnd])
        ->sum('service_amount');

      $lastWeekOfficeExpense = OfficeExpense::where('manager_branch', $branch)
        ->whereBetween('expense_date', [$lastWeekStart, $lastWeekEnd])
        ->sum('expense_amount');

      $lastWeekVehicleExpense = VehicleExpense::where('employee_branch', $branch)
        ->whereBetween('expense_date', [$lastWeekStart, $lastWeekEnd])
        ->sum('expense_amount');

      $lastWeekExpense = $lastWeekMaintenance + $lastWeekOfficeExpense + $lastWeekVehicleExpense;


      // Second Last Week
      $secondLastWeekStart = now()->subWeeks(2)->startOfWeek();
      $secondLastWeekEnd = now()->subWeeks(2)->endOfWeek();

      $secondLastWeekMaintenance = Maintenance::where('branch', $branch)
        ->whereBetween('service_date', [$secondLastWeekStart, $secondLastWeekEnd])
        ->sum('service_amount');

      $secondLastWeekOfficeExpense = OfficeExpense::where('manager_branch', $branch)
        ->whereBetween('expense_date', [$secondLastWeekStart, $secondLastWeekEnd])
        ->sum('expense_amount');

      $secondLastWeekVehicleExpense = VehicleExpense::where('employee_branch', $branch)
        ->whereBetween('expense_date', [$secondLastWeekStart, $secondLastWeekEnd])
        ->sum('expense_amount');

      $secondLastWeekExpense = $secondLastWeekMaintenance + $secondLastWeekOfficeExpense + $secondLastWeekVehicleExpense;


    // Growth %
      $weeklyExpenseDifference = $lastWeekExpense - $secondLastWeekExpense;

      $WeeklyExpenseGrowthPercent = $secondLastWeekExpense > 0
      ? round(($weeklyExpenseDifference / $secondLastWeekExpense) * 100, 2)
      : 0;

             /*
        |--------------------------------------------------------------------------
        | Popular Vehicles
        |--------------------------------------------------------------------------
        */
$popularBookings = Bookings::selectRaw(
        'bookings.vehicle_id,
         vehicles.vehicle_name,
         vehicles.registration_number,
         COUNT(*) as total_bookings,
         SUM(bookings.amount) as revenue'
    )
    ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
    ->where('bookings.branch', $branch)
    ->where('bookings.status', 'completed')
    ->whereMonth('bookings.created_at', now()->month)
    ->whereYear('bookings.created_at', now()->year)
    ->groupBy(
        'bookings.vehicle_id',
        'vehicles.vehicle_name',
        'vehicles.registration_number'
    )
    ->orderByDesc('total_bookings')
    ->limit(5)
    ->get();
        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'content.pages.pages-home',
            compact(
                'currentMonthBookings',
                'totalVehicles',
                'currentMonthCustomers',
                'currentMonthExpense',
                'currentMonthRevenue',
                'branchEmployees',
                'popularBookings',


                'currentWeekRevenue',
                'currentWeekBookings',
                'currentWeekCustomers',
                'currentWeekVehiclesAdded',
                'currentWeekExpense',

                
                'lastWeekRevenue',
              
                'weeklyRevenueDifference',
                'weeklyRevenueGrowthPercent',
                

                'weeklyExpenseDifference',
                'WeeklyExpenseGrowthPercent',

                'monthRevenueDifference',
                'MonthlyRevenueGrowthPercent',

                'monthlyExpenseDifference',
                'monthlyExpenseGrowthPercent'
            )
        );
    }
}