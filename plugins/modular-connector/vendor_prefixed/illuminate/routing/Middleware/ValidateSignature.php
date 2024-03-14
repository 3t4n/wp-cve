<?php

namespace Modular\ConnectorDependencies\Illuminate\Routing\Middleware;

use Closure;
use Modular\ConnectorDependencies\Illuminate\Routing\Exceptions\InvalidSignatureException;
/** @internal */
class ValidateSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $relative
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle($request, Closure $next, $relative = null)
    {
        if ($request->hasValidSignature($relative !== 'relative')) {
            return $next($request);
        }
        throw new InvalidSignatureException();
    }
}
