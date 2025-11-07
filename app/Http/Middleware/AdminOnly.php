<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check()) {
        abort(403, 'Unauthorized');
    }

    $role = auth()->user()->role;

    // Allow both admin and user
    if (!in_array($role, ['admin', 'user'])) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}


}
