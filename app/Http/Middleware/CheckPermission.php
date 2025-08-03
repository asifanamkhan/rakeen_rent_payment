<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check() || !Auth::user()->hasPermission($permissions[0],$permissions[1])) {
            // Redirect or abort if the user does not have permission
            return redirect('/')->with('error', 'You do not have permission to access this resource.');
        }
        return $next($request);
    }
}
