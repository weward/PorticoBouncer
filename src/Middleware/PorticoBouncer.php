<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PorticoBouncer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $user = \App\Models\User::first();
        Auth::login($user);

        $guards = empty($guards) ? [null] : $guards;
        $userPermissions = auth()->user()->getAbilities()->pluck('name')->toArray();

        foreach ($userPermissions as $userPermission) {
            if (request()->route()->getName() == $userPermission) {
                return $next($request);
            }
        }

        abort(403);
    }
}
