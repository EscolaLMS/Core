<?php

namespace EscolaLms\Core\Http\Middleware;

use Closure;
use EscolaLms\Core\Models\User;
use Illuminate\Http\Request;

class SetTimezoneForUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var ?User $user */
        $user = auth()->user() ? User::whereId(auth()->user()->getKey())->first() : null;
        $currentTimezone = $request->header('CURRENT-TIMEZONE');
        if ($currentTimezone && $user && $user->current_timezone !== $currentTimezone) {
            $user->current_timezone = $currentTimezone;
            $user->save();
        }

        return $next($request);
    }
}
