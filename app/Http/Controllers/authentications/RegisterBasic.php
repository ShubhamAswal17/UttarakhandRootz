<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


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
   public function userProfileSetting()
{
    $user = auth()->user();

    if ($user->role == 'admin') {

        $users = User::whereIn('role', ['manager'])
            ->where('approval', 'approve')
            ->get();

        // Get all counts in only 3 queries
        $vehicleCounts = Vehicle::selectRaw('branch, COUNT(*) as total')
            ->groupBy('branch')
            ->pluck('total', 'branch');

        $bookingCounts = Booking::selectRaw('branch, COUNT(*) as total')
            ->where('status', 'completed')
            ->groupBy('branch')
            ->pluck('total', 'branch');

        $customerCounts = Booking::selectRaw('branch, COUNT(DISTINCT customer_id) as total')
            ->groupBy('branch')
            ->pluck('total', 'branch');

        $users = $users->map(function ($manager) use ($vehicleCounts, $bookingCounts, $customerCounts) {

            return [
                'manager' => $manager,
                'vehicle_count' => $vehicleCounts[$manager->branch] ?? 0,
                'booking_count' => $bookingCounts[$manager->branch] ?? 0,
                'customer_count' => $customerCounts[$manager->branch] ?? 0,
            ];
        });

        return view('content.authentications.accountsetting', compact('users'));
    }

    if ($user->role == 'manager') {

        $employees = User::where('role', 'employee')
            ->where('branch', $user->branch)
            ->where('approval', 'approve')
            ->get();

        $employeesData = $employees->map(function ($employee) {

            $employeeBookingsCount = Booking::where('employee_id', $employee->id)
                ->where('status', 'completed')
                ->count();

            $employeeCustomersCount = Booking::where('employee_id', $employee->id)
                ->distinct('customer_id')
                ->count('customer_id');

            $totalVehiclesInBranch = Vehicle::where('branch', $employee->branch)
                ->count();

            return [
                'employee' => $employee,
                'employee_bookings' => $employeeBookingsCount,
                'employee_customers' => $employeeCustomersCount,
                'total_vehicles' => $totalVehiclesInBranch,
                'branch' => $employee->branch,
            ];
        });

        return view('content.authentications.accountsetting', compact('employeesData'));
    }

    return view('content.authentications.accountsetting');
}

    public function editprofileIndex(Request $request){

        return view('content.authentications.changeProfileDetails');
    }

    public function Updatedata(Request $request)
{
    $user = Auth::user();

    // अगर कोई भी चेंज है तो पासवर्ड वेरिफिकेशन ज़रूरी है
    if ($request->isMethod('post') && (
        $request->filled('Name') ||
        $request->filled('Email') ||
        $request->filled('Mobile') ||
        $request->filled('Gender') ||
        $request->filled('Address') ||
        $request->hasFile('userpic') ||
        $request->filled('newPassword')
    )) {
        // वेलिडेशन में पासवर्ड ज़रूरी
        $request->validate([
            'oldPassword' => 'required',
            'Name' => 'required|string|min:3',
            'Email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'Mobile' => [
                'required',
                'string',
                'min:6',
                Rule::unique('users', 'mobile')->ignore($user->id),
            ],
            'Gender' => 'required|in:Male,Female,Other',
            'Address' => 'required|string|min:6',
            'userpic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'newPassword' => 'nullable|min:6|confirmed',
        ]);

        // पुराना पासवर्ड वेरिफाई करें
        if (!Hash::check($request->oldPassword, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        // अब सभी बदलाव करें
        $user->name = $request->Name;
        $user->gender = $request->Gender;
        $user->mobile = $request->Mobile;
        $user->address = $request->Address;
        $user->email = $request->Email;

        if ($request->hasFile('userpic')) {
            if ($user->image && file_exists(public_path('uploads/profile/' . $user->image))) {
                unlink(public_path('uploads/profile/' . $user->image));
            }
            $image = $request->file('userpic');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile'), $filename);
            $user->image = $filename;
        }

        if ($request->filled('newPassword')) {
            $user->password = Hash::make($request->newPassword);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.'
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'No changes detected, nothing to update.'
    ]);
}
public function teams()
{
    $user = auth()->user();

    if ($user->role == 'admin') {

        $employees = User::where('role', 'employee')
            ->where('approval', 'approve')
            ->get();

        // Vehicle count per branch
        $vehicleCounts = Vehicle::selectRaw('branch, COUNT(*) as total')
            ->groupBy('branch')
            ->pluck('total', 'branch');

        // Completed bookings per employee
        $bookingCounts = Booking::selectRaw('employee_id, COUNT(*) as total')
            ->where('status', 'completed')
            ->groupBy('employee_id')
            ->pluck('total', 'employee_id');

        // Unique customers per employee
        $customerCounts = Booking::selectRaw('employee_id, COUNT(DISTINCT customer_id) as total')
            ->groupBy('employee_id')
            ->pluck('total', 'employee_id');

        $employeesData = $employees->map(function ($employee) use ($vehicleCounts, $bookingCounts, $customerCounts) {

            return [
                'employee' => $employee,
                'employee_bookings' => $bookingCounts[$employee->id] ?? 0,
                'employee_customers' => $customerCounts[$employee->id] ?? 0,
                'total_vehicles' => $vehicleCounts[$employee->branch] ?? 0,
                'branch' => $employee->branch,
            ];
        });

        return view('content.authentications.profile-teams', compact('employeesData'));
    }

    abort(403, 'Unauthorized');
}

     public function deactivateAccount(Request $request)
    {
        $request->validate([
            'accountActivation' => 'required'
        ]);

        $user = Auth::user();

        $user->approval = 'hold';
        $user->save();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => true,
            'message' => 'Account deactivated successfully.'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|min:3',
            'Gender' => 'required|in:Male,Female,Other',
            'Mobile' => 'required|string|min:6|unique:users,mobile',
            'Address' => 'required|string|min:6',
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
        return response()->json([
        'status' => 'success',
        'message' => 'Registration submitted. Please wait for approval.'
    ], 200);
    }

    public function show()
    {
        $user = auth()->user();
        if ($user->role == 'admin'){

            $users = User::whereIn('role', ['employee', 'manager'])->where('approval', 'approve')->get();
            $branches = Vehicle::select('branch')->distinct()->pluck('branch');
            return view('content.pages.pages-employee', compact('users','branches'));
        } 
        elseif ($user->role == 'manager'){

        $users = User::where('role', 'employee')->where('approval', 'approve')->where('branch', $user->branch)->get();

        }
        else{

        abort(403, 'Unauthorized');

        }

        return view('content.pages.pages-employee', compact('users'));
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
            'employeeSalary' => 'required|numeric',
            'employeerole' => 'required|string|min:3',
            'employeedesignation'=>'required|string|min:3',
            'employeebranch'=>'string|min:3',
            'employeeDoj' => 'required|date',
            'employeeApproval' => 'required|in:approve,hold'
        ]);
         $user = auth()->user();
        $employeeid->update([
            
            'name' => $request->employeeName,
            'salary' => $request->employeeSalary,
            'role' => $request->employeerole,
            'designation'=>$request->employeedesignation,
            'joining_date' => $request->employeeDoj,
            'approval' => $request->employeeApproval
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
        return view('content.authentications.employeeapproval', compact('users'));

    }

    public function approval(Request $request, $employeeId)
    {
        $employee = User::findOrFail($employeeId);
        $employee->approval = 'approve';
        $employee->save(); 
        return response()->json([
        'status' => 'success',
        'message' => 'Employee  approval approved.'
    ], 200);  
    }

     public function delete(Request $request, $employeeId)
    {
        $employee = User::findOrFail($employeeId);
        $employee->delete(); 
        return response()->json([
        'status' => 'success',
        'message' => 'Employee  approval delete.'
    ], 200);  
    }

    
}