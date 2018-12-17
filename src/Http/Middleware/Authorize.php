<?php

namespace DigitalCloud\NovaBlogTool\Http\Middleware;

use DigitalCloud\NovaBlogTool\NovaBlogTool;

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
        return resolve(NovaBlogTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
