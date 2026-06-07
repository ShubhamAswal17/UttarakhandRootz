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
  public function login(Request $request){
     $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
      if (Auth::attempt($credentials)) {
        
        $request->session()->regenerate();
       if ($request->ajax()) {

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful'
                ]);
            }
             return redirect()->route('home')->with('success', 'Login successful');
      }
      
     if ($request->ajax()) {

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        return redirect()
            ->back()
            ->withErrors([
                'email' => 'Invalid credentials'
            ]);
    
  }
  public function logout(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/')->with('success', 'Logout successful');
  }
}
 

