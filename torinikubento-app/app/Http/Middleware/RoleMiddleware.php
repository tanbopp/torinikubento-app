<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['message' => 'Your account has been deactivated.']);
        }

        // Check if user has role
        if (!$user->role) {
            return abort(403, 'Access denied. No role assigned.');
        }

        // Check if user role is in allowed roles
        if (!empty($roles) && !in_array($user->role->name, $roles)) {
            return abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
