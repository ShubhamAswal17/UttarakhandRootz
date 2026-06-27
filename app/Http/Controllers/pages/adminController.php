<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Customer;;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Maintenance;
use App\Models\OfficeExpense;
use App\Models\VehicleExpense;

class adminController extends Controller
{
    public function index()
    {
       


         /*
        |--------------------------------------------------------------------------
        | Current week Statistics
        |--------------------------------------------------------------------------
        */

        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        
        $currentWeekRevenue = Booking::where('status', 'completed')
          ->whereBetween('booking_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('amount');

        $currentWeekBookings = Booking::whereBetween('booking_date', [$currentWeekStart, $currentWeekEnd])
          ->count();

        $currentWeekCustomers = Booking::whereBetween('booking_date', [$currentWeekStart, $currentWeekEnd])
          ->distinct('customer_id')
          ->count('customer_id');
        $currentWeekVehiclesAdded = Vehicle::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
          ->count();
        
        $currentWeekMaintenance = Maintenance::whereBetween('service_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('service_amount');

        $currentWeekOfficeExpense = OfficeExpense::whereBetween('expense_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('expense_amount');

        $currentWeekVehicleExpense = VehicleExpense::whereBetween('expense_date', [$currentWeekStart, $currentWeekEnd])
          ->sum('expense_amount');

        $currentWeekExpense =$currentWeekMaintenance +$currentWeekOfficeExpense +$currentWeekVehicleExpense;

        /*
        |--------------------------------------------------------------------------
        | Current Month Statistics
        |--------------------------------------------------------------------------
        */

        $currentMonthBookings = Booking::where('status', 'completed')
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->get();


        $currentMonthCustomers = Booking::whereBetween('booking_date', [now()->startOfMonth(),now()->endOfMonth()])
          ->distinct('customer_id')
          ->count('customer_id');

        $totalVehicles = Vehicle::count();

        
        $currentMonthRevenue = Booking::where('status', 'completed')
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->sum('amount');

        $branchEmployees = User::where('role', 'employee')
            ->get();


        $secondLastMonthRevenue = Booking::where('status', 'completed')
        ->whereMonth('booking_date', now()->subMonths(2)->month)
        ->whereYear('booking_date', now()->subMonths(2)->year)
        ->sum('amount');

        $lastMonthRevenue = Booking::where('status', 'completed')
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


        $currentMonthMaintenance = Maintenance::whereMonth('service_date', now()->month)
          ->whereYear('service_date', now()->year)
          ->sum('service_amount');

        $currentMonthOfficeExpense = OfficeExpense::whereMonth('expense_date', now()->month)
          ->whereYear('expense_date', now()->year)
          ->sum('expense_amount');

        $currentMonthVehicleExpense = VehicleExpense::whereMonth('expense_date', now()->month)
          ->whereYear('expense_date', now()->year)
          ->sum('expense_amount');

        $currentMonthExpense = $currentMonthMaintenance + $currentMonthOfficeExpense + $currentMonthVehicleExpense;


// Last Month
      $lastMonthMaintenance = Maintenance::whereMonth('service_date', now()->subMonth()->month)
        ->whereYear('service_date', now()->subMonth()->year)
        ->sum('service_amount');

      $lastMonthOfficeExpense = OfficeExpense::whereMonth('expense_date', now()->subMonth()->month)
        ->whereYear('expense_date', now()->subMonth()->year)
        ->sum('expense_amount');

      $lastMonthVehicleExpense = VehicleExpense::whereMonth('expense_date', now()->subMonth()->month)
        ->whereYear('expense_date', now()->subMonth()->year)
        ->sum('expense_amount');

      $lastMonthExpense = $lastMonthMaintenance + $lastMonthOfficeExpense + $lastMonthVehicleExpense;


// Second Last Month

      $secondLastMonthMaintenance = Maintenance::whereMonth('service_date', now()->subMonths(2)->month)
        ->whereYear('service_date', now()->subMonths(2)->year)
        ->sum('service_amount');

      $secondLastMonthOfficeExpense = OfficeExpense::whereMonth('expense_date', now()->subMonths(2)->month)
        ->whereYear('expense_date', now()->subMonths(2)->year)
        ->sum('expense_amount');

      $secondLastMonthVehicleExpense = VehicleExpense::whereMonth('expense_date', now()->subMonths(2)->month)
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

        $lastWeekRevenue = Booking::where('status', 'completed')
          ->whereBetween('return_date', [$lastWeekStart, $lastWeekEnd])
          ->sum('amount');


// Second Last Week
        $secondLastWeekStart = now()->subWeeks(2)->startOfWeek();
        $secondLastWeekEnd = now()->subWeeks(2)->endOfWeek();

        $previousWeekRevenue = Booking::where('status', 'completed')
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

      $lastWeekMaintenance = Maintenance::whereBetween('service_date', [$lastWeekStart, $lastWeekEnd])
        ->sum('service_amount');

      $lastWeekOfficeExpense = OfficeExpense::whereBetween('expense_date', [$lastWeekStart, $lastWeekEnd])
        ->sum('expense_amount');

      $lastWeekVehicleExpense = VehicleExpense::whereBetween('expense_date', [$lastWeekStart, $lastWeekEnd])
        ->sum('expense_amount');

      $lastWeekExpense = $lastWeekMaintenance + $lastWeekOfficeExpense + $lastWeekVehicleExpense;


      // Second Last Week
      $secondLastWeekStart = now()->subWeeks(2)->startOfWeek();
      $secondLastWeekEnd = now()->subWeeks(2)->endOfWeek();

      $secondLastWeekMaintenance = Maintenance::whereBetween('service_date', [$secondLastWeekStart, $secondLastWeekEnd])
        ->sum('service_amount');

      $secondLastWeekOfficeExpense = OfficeExpense::whereBetween('expense_date', [$secondLastWeekStart, $secondLastWeekEnd])
        ->sum('expense_amount');

      $secondLastWeekVehicleExpense = VehicleExpense::whereBetween('expense_date', [$secondLastWeekStart, $secondLastWeekEnd])
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
$popularBookings = Booking::selectRaw(
    'Bookings.vehicle_id,
     Vehicles.vehicle_name,
     Vehicles.registration_number,
     Vehicles.branch,
     COUNT(*) as total_bookings,
     SUM(Bookings.amount) as revenue'
)
->join('Vehicles', 'Bookings.vehicle_id', '=', 'Vehicles.id')
->where('Bookings.status', 'completed')
->whereMonth('Bookings.booking_date', now()->month)
->whereYear('Bookings.booking_date', now()->year)
->groupBy(
    'Bookings.vehicle_id',
    'Vehicles.vehicle_name',
    'Vehicles.registration_number',
    'Vehicles.branch'
)
->orderByDesc('total_bookings')
->limit(5)
->get();
        /*
        |--------------------------------------------------------------------------
        | yearly data
        |--------------------------------------------------------------------------
        */

        $year = now()->year;

$monthlyRevenue = [];
$monthlyExpense = [];

for ($m = 1; $m <= 12; $m++) {

    // Revenue (Bookings)
    $monthlyRevenue[] = Booking::where('status', 'completed')
        ->whereYear('booking_date', $year)
        ->whereMonth('booking_date', $m)
        ->sum('amount');

    // Expense (all combined)
    $monthlyExpense[] =
        Maintenance::whereYear('service_date', $year)->whereMonth('service_date', $m)->sum('service_amount')
        + OfficeExpense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->sum('expense_amount')
        + VehicleExpense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->sum('expense_amount');
}

    
              /*
        |--------------------------------------------------------------------------
        | slecting year dynamicaly
        |--------------------------------------------------------------------------
        */
        $currentYear = now()->year;

        $years = [
            $currentYear,
            $currentYear - 1,
            $currentYear - 2,
        ];

    
              /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */


        return view(
            'content.pages.pages-admin-dashboard',
            compact(
                'years',
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
                'monthlyExpenseGrowthPercent',
                
                'monthlyRevenue',
                'monthlyExpense'
            )
        );
    }

  public function revenueData(Request $request)
{
    $year = $request->year;

    $monthlyRevenue = [];
    $monthlyExpense = [];

    for ($m = 1; $m <= 12; $m++) {


        $monthlyRevenue[] = Booking::where('status', 'completed')
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $m)
            ->sum('amount');

        $maintenance = Maintenance::whereMonth('service_date', $m)
        ->whereYear('service_date', $year)
        ->sum('service_amount');

        $office = OfficeExpense::whereMonth('expense_date', $m)
          ->whereYear('expense_date', $year)
          ->sum('expense_amount');

        $vehicle = VehicleExpense::whereMonth('expense_date', $m)
          ->whereYear('expense_date', $year)
          ->sum('expense_amount');

        $monthlyExpense[] = $maintenance + $office + $vehicle;

       
    }

    return response()->json([
        'monthlyRevenue' => $monthlyRevenue,
        'monthlyExpense' => $monthlyExpense
    ]);
}


}