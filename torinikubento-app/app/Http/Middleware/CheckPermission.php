<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has role
        if (!$user->role) {
            abort(403, 'Unauthorized: No role assigned');
        }

        // Check permissions
        foreach ($permissions as $permission) {
            if (!$user->hasPermission($permission)) {
                abort(403, 'Unauthorized: Missing permission - ' . $permission);
            }
        }

        return $next($request);
    }
}
