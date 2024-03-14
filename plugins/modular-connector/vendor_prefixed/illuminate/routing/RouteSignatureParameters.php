<?php

namespace Modular\ConnectorDependencies\Illuminate\Routing;

use Modular\ConnectorDependencies\Illuminate\Support\Reflector;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use ReflectionFunction;
use ReflectionMethod;
/** @internal */
class RouteSignatureParameters
{
    /**
     * Extract the route action's signature parameters.
     *
     * @param  array  $action
     * @param  string|null  $subClass
     * @return array
     */
    public static function fromAction(array $action, $subClass = null)
    {
        $callback = RouteAction::containsSerializedClosure($action) ? \unserialize($action['uses'])->getClosure() : $action['uses'];
        $parameters = \is_string($callback) ? static::fromClassMethodString($callback) : (new ReflectionFunction($callback))->getParameters();
        return \is_null($subClass) ? $parameters : \array_filter($parameters, function ($p) use($subClass) {
            return Reflector::isParameterSubclassOf($p, $subClass);
        });
    }
    /**
     * Get the parameters for the given class / method by string.
     *
     * @param  string  $uses
     * @return array
     */
    protected static function fromClassMethodString($uses)
    {
        [$class, $method] = Str::parseCallback($uses);
        if (!\method_exists($class, $method) && Reflector::isCallable($class, $method)) {
            return [];
        }
        return (new ReflectionMethod($class, $method))->getParameters();
    }
}
