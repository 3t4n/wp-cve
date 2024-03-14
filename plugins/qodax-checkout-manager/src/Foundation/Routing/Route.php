<?php

namespace Qodax\CheckoutManager\Foundation\Routing;

use Qodax\CheckoutManager\Contracts\HttpResponseInterface;
use Qodax\CheckoutManager\Foundation\Container;
use Qodax\CheckoutManager\Http\Middleware\VerifyCsrfToken;
use Qodax\CheckoutManager\Http\Request;

if ( ! defined('ABSPATH')) {
    exit;
}

class Route
{
    private $action;
    private $controller;
    private $method;
    private $public = false;
    private $middleware = [
        VerifyCsrfToken::class
    ];

    private function __construct(string $action, string $controller, string $method)
    {
        $this->action = $action;
        $this->controller = $controller;
        $this->method = $method;
    }

    public static function make(string $action, string $controller, string $method): self
    {
        return new self($action, $controller, $method);
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function public(): self
    {
        $this->public = true;

        return $this;
    }

    public function middleware(array $middleware): self
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * @param array $data
     */
    public function dispatch($data)
    {
        $request = new Request($data);

        foreach ($this->middleware as $middleware) {
            $guard = new $middleware();

            if (method_exists($guard, 'handle')) {
                $guard->handle($request);
            }
        }

        $controller = Container::instance()->make($this->controller);

        // todo: throws exception if $response not implement ResponseInterface
        /* @var HttpResponseInterface $response */
        $response = call_user_func([ $controller, $this->method ], $request);
        $response->send();
    }
}