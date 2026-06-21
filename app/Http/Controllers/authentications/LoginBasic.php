<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }
 public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
        'approval' => 'approve'
    ];

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful'
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials or account not approved.'
    ], 401);
}
  public function logout(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/')->with('success', 'Logout successful');
  }
}
 

