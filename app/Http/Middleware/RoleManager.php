<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $authUserRole = Auth::user()->role;

        if ($role === 'admin' && $authUserRole == 0) {
            return $next($request); // Admin has unrestricted access.
        }

        if ($role === 'cashier' && $authUserRole == 1) {
            if ($request->route()->getName() === 'sales-management') {
                return $next($request); // Cashier can access sales management only.
            }
            return redirect()->route('sales-management');
        }
        switch ($authUserRole) {
            case 0:
                return redirect()->route('dashboard'); 
            case 1:
                return redirect()->route('sales-management');
        }

        return redirect()->route('login');
    }
}
