<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\RoleEnum as ROLE;

class AdminRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role_id === ROLE::ADMIN->value || auth()->user()->role_id === ROLE::MANAGER->value) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
