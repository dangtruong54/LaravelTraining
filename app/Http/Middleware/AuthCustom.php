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
        switch ($guard) {
            case 'web':
                if(!Auth::guard($guard)->check()) {
                    return redirect()->route('login');
                }
                break;
            case 'web1':
                if(!Auth::guard($guard)->check()) {
                    return redirect()->route('login2');
                }
                break;
            default:
                return redirect()->route('login');
                break;
        }
        return $next($request);
    }
}
