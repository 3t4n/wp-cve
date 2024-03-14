<?php

namespace Modular\ConnectorDependencies\Illuminate\Http\Middleware;

use Closure;
use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Response;
/** @internal */
class CheckResponseForModifications
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
        $response = $next($request);
        if ($response instanceof Response) {
            $response->isNotModified($request);
        }
        return $response;
    }
}
