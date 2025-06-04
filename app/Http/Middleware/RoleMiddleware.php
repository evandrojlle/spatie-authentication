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
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $role
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = Auth::user();
        
        /**
         * @disregard
         */
        if (! $user || ! $user->hasRole($role)) {
            return response()->json(['error' => 'Acesso negado: papel insuficiente'], 401);
        }

        return $next($request);
    }
}
