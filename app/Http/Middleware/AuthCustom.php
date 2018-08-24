<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;

class AuthCustom
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        dd($guard);
        switch ($guard) {
            case 'web':
                break;
            case 'web1':
                break;
            default:
                break;
        }


        if (Auth::guard($guard)->check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
