<?php

namespace WPPayForm\Framework\Http;

use Closure;
use ReflectionMethod;
use ReflectionFunction;
use InvalidArgumentException;
use WPPayForm\Framework\Container\Util;
use WPPayForm\Framework\Support\Reflector;
use WPPayForm\Framework\Support\UrlRoutable;

trait SubstituteRouteParametersTrait
{
    protected function SubstituteParameters($routeParameters)
    {
        $resolved = [];

        $signatureParameters = $this->getParametersFromRouteAction();

        if ($signatureParameters) {

            foreach ($signatureParameters as $signatureParameter) {

                $name = $signatureParameter->getName();
                
                if ($this->boundModel($name, $signatureParameter, $routeParameters)) {
                    
                    $class = Util::getParameterClassName($signatureParameter);
                    
                    $resolved[$name] = $this->app->make($class)->findOrFail(
                        $routeParameters[$name]
                    );

                    unset($routeParameters[$name]);
                }

            }
        }

        $remainingParams = [];

        $signatureParameters = array_filter($signatureParameters, function($param) {
            return !class_exists(Reflector::getParameterClassName($param) ?: '');
        });


        if (($params = count($signatureParameters)) && $params != count($routeParameters)) {
            throw new InvalidArgumentException(
                'Invalid route action, route parameters doesn\'t match with method signature.'
            );
        }

        foreach ($routeParameters as $param) {
            
            if ($dep = array_shift($signatureParameters)) {
                $remainingParams[$dep->getName()] = $param;
            }
        }

        return $resolved + $remainingParams;
    }

    protected function boundModel($name, $parameter, $routeParameters)
    {
        if (array_key_exists($name, $routeParameters)) {
            return Reflector::isParameterSubclassOf($parameter, UrlRoutable::class);
        }
    }

    protected function getParametersFromRouteAction()
    {
        if ($this->action instanceof Closure) {
            return (new ReflectionFunction($this->action))->getParameters();
        }

        list($class, $method) = explode('@', $this->action);

        return (new ReflectionMethod($class, $method))->getParameters();
    }
}
