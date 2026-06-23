<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminOrManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'manager')) {
            return $next($request);
        }
        if (url()->previous() != url()->current()) {
            return redirect()->back()->with('error', 'Access denied.');
        }
        return redirect()->route('pages-home')->with('error', 'Access denied.');
    }
}
