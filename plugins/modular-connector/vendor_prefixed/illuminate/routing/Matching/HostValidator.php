<?php

namespace Modular\ConnectorDependencies\Illuminate\Routing\Matching;

use Modular\ConnectorDependencies\Illuminate\Http\Request;
use Modular\ConnectorDependencies\Illuminate\Routing\Route;
/** @internal */
class HostValidator implements ValidatorInterface
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function matches(Route $route, Request $request)
    {
        $hostRegex = $route->getCompiled()->getHostRegex();
        if (\is_null($hostRegex)) {
            return \true;
        }
        return \preg_match($hostRegex, $request->getHost());
    }
}
