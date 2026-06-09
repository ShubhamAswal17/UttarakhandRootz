<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterBasic extends Controller
{
    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];

        return view(
            'content.authentications.auth-register-basic',
            ['pageConfigs' => $pageConfigs]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|min:3',
            'Gender' => 'required|in:Male,Female,Other',
            'Mobile' => 'required|string|min:6|unique:users,mobile',
            'Address' => 'required|string|min:10',
            'District' => 'required|string|min:3',
            'Email' => 'required|email|unique:users,email',
            'Password' => 'required|string|min:6',
            'terms' => 'accepted',
        ]);

        $user = new User();

        $user->name = $request->Name;
        $user->gender = $request->Gender;
        $user->mobile = $request->Mobile;
        $user->address = $request->Address;
        $user->district = $request->District;
        $user->email = $request->Email;
        $user->password = Hash::make($request->Password);

        $user->save();

        if ($request->ajax()) {

            return response()->json([
                'status' => 'success',
                'message' => 'Registration submitted.'
            ]);
        }

        return redirect()
            ->route('auth-login')
            ->with('success', 'Registration submitted successfully.');
    }
   public function show(){
    $users = User::where('role', 'employee')->get();
    return view('content.pages.users', compact('users'));
   }
   public function getemployedata(Request $request ,$employeeId){
    $employee=User::findorfail($employeeId);
    return response()->json([
        'status' => 'success',
        'user' => $employee
    ]);
   }
   public function updateemployedata(Request $request,$employeeid){
    $employeeid=User::findorfail($employeeid);
    $request->validate([
        'employeeName' => 'required|string|min:3',
        'employeeEmail' => 'required|email|unique:users,email,'.$employeeid->id,
        'employeeMobile' => 'required|string|min:6|unique:users,mobile,'.$employeeid->id,
        'employeeSalary' => 'required|numeric',
        'employeeDesignation' => 'required|string|min:3',
        'employeeDoj' => 'required|date',
        'employeeStatus' => 'required|in:active,inactive'
    ]);
    $employeeid->update([
        'name' => $request->employeeName,
        'email' => $request->employeeEmail,
        'mobile' => $request->employeeMobile,
        'salary' => $request->employeeSalary,
        'designation' => $request->employeeDesignation,
        'joining_date' => $request->employeeDoj,
        'status' => $request->employeeStatus
    ]);
    return response()->json([
        'status' => 'success',
        'message' => 'Employee updated successfully.'
    ]);
   }

    
}