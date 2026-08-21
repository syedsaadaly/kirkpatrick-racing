<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
          if (!Auth::check()) {
            return redirect()->route('admin.loginView')
                ->with('error', 'Please login first.');
        }

        // Check if user has admin role
        if ($request->user()->hasRole('admin')) {
            return $next($request);
        }

        // Not admin, redirect to login
        return redirect()->route('admin.loginView')
            ->with('error', 'Access denied.');
    }
    
}
