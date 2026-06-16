<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Bookings;
use App\Models\customers;
use Illuminate\Support\Facades\Hash;

class RegisterBasic extends Controller
{
    public function index()
    {

        $vehiclelocations=Vehicle::select('branch')->distinct()->get();
        $pageConfigs = ['myLayout' => 'blank'];

        return view(
            'content.authentications.auth-register-basic',
            ['pageConfigs' => $pageConfigs, 'vehiclelocations' => $vehiclelocations]
        );

    }
    public function accountsetting()
    {

         $user = auth()->user();
        if ($user->role == 'admin'){            
            $users=User::wherein('role', ['manager'])
                        ->where('approval', 'approve')
                        ->get();
       
            $users = $users->map(function ($users) {
            $branch = $users->branch;
            $vehicleCount = Vehicle::where('branch', $branch)->count();
            $bookingCount = Bookings::where('branch', $branch)->where('status', 'completed')->count();
            $customerCount = Bookings::where('branch', $users->branch)->distinct('customer_id')->count('customer_id');
            return [
                'manager' => $users,
                'vehicle_count' => $vehicleCount,
                'booking_count' => $bookingCount,
                'customer_count' => $customerCount,
            ];
            });
            return view('content.authentications.accountsetting', compact('users'));
        }

        if ($user->role == 'manager'){            
                $employees = User::where('role', 'employee')
		            ->where('branch', auth()->user()->branch)
                    ->where('approval', 'approve')
                    ->get();
                $employeesData = $employees->map(function ($employee) {
                $branch = $employee->branch;
                $employeeBookingsCount = bookings::where('employee_id', $employee->id)->where('status', 'completed')->count();
                $employeeCustomersCount = bookings::where('employee_id', $employee->id)
                                                    ->pluck('customer_id')
                                                    ->unique()
                                                    ->count();
                $totalVehiclesInBranch = Vehicle::where('branch', $branch)->count();
                return [
                    'employee' => $employee,
                    'employee_bookings' => $employeeBookingsCount,
                    'employee_customers' => $employeeCustomersCount,
                    'total_vehicles' => $totalVehiclesInBranch,
                    'branch' => $branch,
                ];
                
                });
                return view('content.authentications.accountsetting', compact('employeesData'));
        } 
        return view('content.authentications.accountsetting');  
       
    }

    public function editprofile()
    {


        return view('content.authentications.editprofile');
    
    }

    public function teams()
    {

        $user = auth()->user();
        if (auth()->user()->role == 'admin') {
                $employees = User::where('role', 'employee')
                ->where('approval', 'approve')
                ->get();
            $employeesData = $employees->map(function ($employee) {
            $branch = $employee->branch;
            $employeeBookingsCount = bookings::where('employee_id', $employee->id)->where('status', 'completed')->count();

            $employeeCustomersCount = bookings::where('employee_id', $employee->id)
            ->pluck('customer_id')
            ->unique()
            ->count();

            $totalVehiclesInBranch = Vehicle::where('branch', $branch)->count();

            return [
                'employee' => $employee,
                'employee_bookings' => $employeeBookingsCount,
                'employee_customers' => $employeeCustomersCount,
                'total_vehicles' => $totalVehiclesInBranch,
                'branch' => $branch,
             ];
            });
            return view('content.authentications.profile-teams', compact('employeesData'));
        } 
         abort(403, 'Unauthorized');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|min:3',
            'Gender' => 'required|in:Male,Female,Other',
            'Mobile' => 'required|string|min:6|unique:users,mobile',
            'Address' => 'required|string|min:10',
            'branch' => 'required|string|min:3',
            'Email' => 'required|email|unique:users,email',
            'Password' => 'required|string|min:6',
            'terms' => 'accepted',
        ]);
        $user = new User();
        $user->name = $request->Name;
        $user->gender = $request->Gender;
        $user->mobile = $request->Mobile;
        $user->address = $request->Address;
        $user->branch = $request->branch;
        $user->email = $request->Email;
        $user->password = Hash::make($request->Password);
        $user->save();
        if ($request->ajax()){
            return response()->json([
                'status' => 'success',
                'message' => 'Registration submitted.'
            ]);
        }
        return redirect()->route('auth-login')->with('success', 'Registration submitted successfully.');
    }

    public function show()
    {
        $user = auth()->user();
        if ($user->role == 'admin'){

            $users = User::whereIn('role', ['employee', 'manager'])->where('approval', 'approve')->get();
            $branches = Vehicle::select('branch')->distinct()->pluck('branch');
            return view('content.pages.users', compact('users','branches'));
        } 
        elseif ($user->role == 'manager'){

        $users = User::where('role', 'employee')->where('approval', 'approve')->where('branch', $user->branch)->get();

        }
        else{

        abort(403, 'Unauthorized');

        }

        return view('content.pages.users', compact('users'));
    }
    public function getemployedata(Request $request ,$employeeId)
    {

        $employee=User::findorfail($employeeId);
        return response()->json(['status' => 'success','user' => $employee]);

    }
    public function updateemployedata(Request $request,$employeeid)
    {
        $employeeid=User::findorfail($employeeid);
        $request->validate([
            'employeeName' => 'required|string|min:3',
            // 'employeeEmail' => 'required|email|unique:users,email,'.$employeeid->id,
            // 'employeeMobile' => 'required|string|min:6|unique:users,mobile,'.$employeeid->id,
            'employeeSalary' => 'required|numeric',
            'employeerole' => 'required|string|min:3',
            'employeedesignation'=>'required|string|min:3',
            'employeebranch'=>'string|min:3',
            'employeeDoj' => 'required|date',
            'employeeStatus' => 'required|in:active,inactive'
        ]);
         $user = auth()->user();
        $employeeid->update([
            
            'name' => $request->employeeName,
            // 'email' => $request->employeeEmail,
            // 'mobile' => $request->employeeMobile,
            'salary' => $request->employeeSalary,
            'role' => $request->employeerole,
            'designation'=>$request->employeedesignation,
            'joining_date' => $request->employeeDoj,
            'status' => $request->employeeStatus
        ]);
        if ($user->role === 'admin') {
            $data['branch'] = $request->employeebranch;
        }
        return response()->json(['status' => 'success','message' => 'Employee updated successfully.']);
    }

    public function approvalemployee()
    {

        abort_if(
            !in_array(auth()->user()->role, ['admin', 'manager']),
            403,'Unauthorized'
        );
        $user = auth()->user();
        $users = User::where('approval', 'hold')->when($user->role === 'manager', function ($query) use ($user) {
            $query->where('branch', $user->branch);
        })->get();
        return view('content.authentications.userapprove', compact('users'));

    }

    public function approval(Request $request, $employeeId)
    {

        $employee = User::findOrFail($employeeId);
        $employee->approval = 'approve';
        $employee->save();
        return response()->json([ 'status' => 'success', 'message' => 'Employee approved']);    
    }

    
}