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
            'username' => 'required|string|min:3',
            'mobile' => 'required|string|min:6|unique:users,mobile',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'terms' => 'accepted',
        ]);

        $user = new User();

        $user->name = $request->username;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

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
}