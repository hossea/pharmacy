<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $authUserRole = Auth::user()->role;

        if ($role === 'admin' && $authUserRole == 0) {
            return $next($request); // Admin access
        }

        if ($role === 'cashier' && $authUserRole == 1) {
            return $next($request); // Cashier access
        }

        // Redirect to appropriate home page
        return $authUserRole == 0
            ? redirect()->route('dashboard')
            : redirect()->route('sales-management');
    }
}
