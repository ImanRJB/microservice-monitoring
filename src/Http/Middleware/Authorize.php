<?php

namespace Milyoona\MicroserviceMonitor\Http\Middleware;

use Milyoona\MicroserviceMonitor\MicroserviceMonitor;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(MicroserviceMonitor::class)->authorize($request) ? $next($request) : abort(403);
    }
}
