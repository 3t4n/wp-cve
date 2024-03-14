<?php

namespace XCurrency\WpMVC\Routing;

use XCurrency\WpMVC\Routing\Providers\RouteServiceProvider;
use XCurrency\WpMVC\Routing\Contracts\Middleware as MiddlewareContract;
use XCurrency\WP_Error;
class Middleware
{
    protected static array $middleware = [];
    public static function set_middleware_list(array $middleware)
    {
        static::$middleware = $middleware;
    }
    /**
     * @param array $middleware
     * @return boolean|WP_Error
     */
    public static function is_user_allowed(array $middleware)
    {
        $container = RouteServiceProvider::$container;
        foreach ($middleware as $middleware_name) {
            if (!\array_key_exists($middleware_name, static::$middleware)) {
                return \false;
            }
            $current_middleware = static::$middleware[$middleware_name];
            $middleware_object = $container->get($current_middleware);
            if (!$middleware_object instanceof MiddlewareContract) {
                return \false;
            }
            $permission = $container->call([$middleware_object, 'handle']);
            if ($permission instanceof WP_Error || !$permission) {
                return $permission;
            }
        }
        return \true;
    }
}
