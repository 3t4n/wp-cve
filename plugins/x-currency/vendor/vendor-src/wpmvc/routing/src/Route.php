<?php

namespace XCurrency\WpMVC\Routing;

use XCurrency\WpMVC\Routing\Providers\RouteServiceProvider;
use XCurrency\WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
class Route
{
    protected static string $route_prefix = '';
    protected static array $routes = [];
    protected static array $group_middleware = [];
    public static function group(string $prefix, \Closure $callback, array $middleware = [])
    {
        $previous_route_prefix = static::$route_prefix;
        $previous_route_middleware = static::$group_middleware;
        static::$route_prefix .= '/' . \trim($prefix, '/');
        static::$group_middleware = \array_merge(static::$group_middleware, $middleware);
        \call_user_func($callback);
        static::$route_prefix = $previous_route_prefix;
        static::$group_middleware = $previous_route_middleware;
    }
    public static function get(string $route, $callback, array $middleware = [])
    {
        static::register_route('GET', $route, $callback, $middleware);
    }
    public static function post(string $route, $callback, array $middleware = [])
    {
        static::register_route('POST', $route, $callback, $middleware);
    }
    public static function put(string $route, $callback, array $middleware = [])
    {
        static::register_route('PUT', $route, $callback, $middleware);
    }
    public static function patch(string $route, $callback, array $middleware = [])
    {
        static::register_route('PATCH', $route, $callback, $middleware);
    }
    public static function delete(string $route, $callback, array $middleware = [])
    {
        static::register_route('DELETE', $route, $callback, $middleware);
    }
    public static function resources(array $resources, array $middleware = [])
    {
        foreach ($resources as $resource => $callback) {
            static::resource($resource, $callback, [], $middleware);
        }
    }
    public static function resource(string $route, $callback, array $take = [], array $middleware = [])
    {
        $routes = ['index' => ['method' => 'GET', 'route' => $route], 'store' => ['method' => 'POST', 'route' => $route], 'show' => ['method' => 'GET', 'route' => $route . '/{id}'], 'update' => ['method' => 'PATCH', 'route' => $route . '/{id}'], 'delete' => ['method' => 'DELETE', 'route' => $route . '/{id}']];
        if (!empty($take)) {
            if (isset($take['type']) && 'only' === $take['type']) {
                $routes = \array_intersect_key($routes, \array_flip($take['items']));
            } else {
                $routes = \array_diff_key($routes, \array_flip($take['items']));
            }
        }
        foreach ($routes as $callback_method => $args) {
            static::register_route($args['method'], $args['route'], [$callback, $callback_method], $middleware);
        }
    }
    protected static function register_route(string $method, string $route, $callback, array $middleware = [])
    {
        $data_binder = RouteServiceProvider::$container->get(DataBinder::class);
        $namespace = $data_binder->get_namespace();
        $full_route = static::get_final_route($route);
        $middleware = \array_merge(static::$group_middleware, $middleware);
        rest_get_server()->register_route($namespace, $full_route, [['methods' => $method, 'callback' => function (WP_REST_Request $wp_rest_request) use($callback) {
            RouteServiceProvider::$container->set(WP_REST_Request::class, $wp_rest_request);
            return static::callback($callback);
        }, 'permission_callback' => function () use($middleware) {
            $permission = Middleware::is_user_allowed($middleware);
            if ($permission instanceof WP_Error) {
                static::set_status_code($permission->get_error_code());
            }
            return $permission;
        }]]);
    }
    protected static function callback($callback)
    {
        $response = RouteServiceProvider::$container->call($callback);
        if (!\is_array($response)) {
            exit;
        }
        $status_code = \intval($response['status_code']);
        static::set_status_code($status_code);
        $response = $response['data'];
        if ($status_code > 399 && 600 > $status_code) {
            $response['data']['status'] = $status_code;
            return $response;
        }
        return $response;
    }
    protected static function set_status_code(int $status_code)
    {
        status_header($status_code);
        /**
         * Filters the REST API response.
         *
         * @param WP_HTTP_Response $result  Result to send to the client. Usually a <code>WP_REST_Response</code>.
         */
        add_filter('rest_post_dispatch', function (WP_HTTP_Response $result) use($status_code) {
            $result->set_status($status_code);
            return $result;
        });
    }
    protected static function get_final_route(string $route)
    {
        if (!empty(static::$route_prefix)) {
            $route = \rtrim(static::$route_prefix, '/') . '/' . \ltrim($route, '/');
        }
        $route = \trim($route, '/');
        $route = static::format_route_regex($route);
        $data_binder = RouteServiceProvider::$container->get(DataBinder::class);
        $namespace = $data_binder->get_namespace();
        $version = $data_binder->get_version();
        if (!empty($version)) {
            return "/{$namespace}/{$version}/{$route}";
        }
        return "/{$namespace}/{$route}";
    }
    protected static function format_route_regex(string $route) : string
    {
        if (\strpos($route, '}') === \false) {
            return $route;
        }
        \preg_match_all('#\\{(.*?)\\}#', $route, $params);
        if (\strpos($route, '?}') !== \false) {
            return static::optional_param($route, $params);
        } else {
            return static::required_param($route, $params);
        }
    }
    protected static function optional_param(string $route, array $params) : string
    {
        foreach ($params[0] as $key => $value) {
            $route = \str_replace('/' . $value, '(?:/(?P<' . \str_replace('?', '', $params[1][$key]) . '>[-\\w]+))?', $route);
        }
        return $route;
    }
    protected static function required_param(string $route, array $params) : string
    {
        foreach ($params[0] as $key => $value) {
            $route = \str_replace($value, '(?P<' . $params[1][$key] . '>[-\\w]+)', $route);
        }
        return $route;
    }
}
