<?php

namespace App\Http\Middleware;

use Closure;

class LslProtected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // We're checking with REMOTE_ADDR because it can't be easily spoofed
        $host = gethostbyaddr($request->server('REMOTE_ADDR'));

        // This route should only be accessed from an LSL script running on an SL server
        if (!ends_with($host, '.agni.lindenlab.com')) {
            abort(404); // TODO: log (but not to Sentry)
        }

        // Authorized, proceed
        return $next($request);
    }
}
