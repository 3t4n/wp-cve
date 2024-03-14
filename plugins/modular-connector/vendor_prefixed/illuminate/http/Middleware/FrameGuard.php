<?php

namespace Modular\ConnectorDependencies\Illuminate\Http\Middleware;

use Closure;
/** @internal */
class FrameGuard
{
    /**
     * Handle the given request and get the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', \false);
        return $response;
    }
}
