<?php

namespace App\Http\Middleware;

use Auth;
use Cache;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check()) {
            $expiresAt = Carbon::now()->addMinutes(1);
            Cache::put('user-online-' . Auth::guard($guard)->user()->id, true, $expiresAt);
        }
        return $next($request);
    }
}
