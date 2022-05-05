<?php

namespace EscolaLms\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetTimezoneForUserMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $user->current_timezone = $request->header('CURRENT_TIMEZONE', 'UTC');
        $user->save();
        return $next($request);
    }
}
