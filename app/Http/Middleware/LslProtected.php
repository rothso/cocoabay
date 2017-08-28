<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Closure;

class LslProtected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // We're checking with REMOTE_ADDR because it can't be easily spoofed
        $hostIp = $request->server('REMOTE_ADDR');
        $host = gethostbyaddr($hostIp);

        // ngrok is a trusted proxy that runs to provide a sharable public link to our server
        $isNgrok = App::environment('local') && $host == 'localhost';

        if ($isNgrok) {
            $hostIps = (array) $request->server('HTTP_X_FORWARDED_FOR');
            $host = gethostbyaddr(array_pop($hostIps)); // last IP is the SL server
        }

        // This route should only be accessed from an LSL script running on an SL server
        if (ends_with($host, '.agni.lindenlab.com')) {
            return $next($request);
        }

        abort(404); // TODO: log (but not to Sentry)
    }
}
