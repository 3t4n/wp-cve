<?php

namespace XCurrency\WpMVC\Routing;

use XCurrency\WpMVC\Routing\Providers\RouteServiceProvider;
use WP_REST_Request;
use WP_REST_Server;
use WP;
class Ajax extends Route
{
    protected static $ajax_routes = [];
    public static bool $route_found = \false;
    protected static function register_route(string $method, string $route, $callback, array $middleware = [])
    {
        //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
        if ($method !== $_SERVER['REQUEST_METHOD']) {
            return;
        }
        $route = static::get_final_route($route);
        $middleware = \array_merge(static::$group_middleware, $middleware);
        global $wp;
        /**
         * @var WP $wp
         */
        $match = \preg_match('@^' . $route . '$@i', \rtrim('/' . $wp->request, '/'), $matches);
        if (!$match) {
            return;
        }
        static::$route_found = \true;
        /**
         * Fire admin init if the current API has admin middleware
         */
        static::admin_init($middleware);
        $is_allowed = Middleware::is_user_allowed($middleware);
        if (!$is_allowed) {
            status_header(401);
            Response::set_headers([]);
            echo wp_json_encode(['code' => 'ajax_forbidden', 'message' => 'Sorry, you are not allowed to do that.']);
            exit;
        }
        $url_params = [];
        foreach ($matches as $param => $value) {
            if (!\is_int($param)) {
                $url_params[$param] = $value;
            }
        }
        static::bind_wp_rest_request($method, $url_params);
        $response = static::callback($callback);
        echo wp_json_encode($response);
        exit;
    }
    /**
     * Fire admin init if the current API has admin middleware
     */
    protected static function admin_init(array $middleware)
    {
        if (!\in_array('admin', $middleware)) {
            return;
        }
        if (!\defined('WP_ADMIN')) {
            \define('WP_ADMIN', \true);
        }
        /** Load WordPress Administration APIs */
        require_once ABSPATH . 'wp-admin/includes/admin.php';
        /** This action is documented in wp-admin/admin.php */
        do_action('admin_init');
    }
    protected static function bind_wp_rest_request(string $method, array $url_params = [])
    {
        $wp_rest_request = new WP_REST_Request($method, '/');
        $wp_rest_server = new WP_REST_Server();
        $wp_rest_request->set_url_params($url_params);
        //phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $wp_rest_request->set_query_params(wp_unslash($_GET));
        //phpcs:ignore WordPress.Security.NonceVerification.Missing
        $wp_rest_request->set_body_params(wp_unslash($_POST));
        $wp_rest_request->set_file_params($_FILES);
        $wp_rest_request->set_headers($wp_rest_server->get_headers(wp_unslash($_SERVER)));
        $wp_rest_request->set_body($wp_rest_server->get_raw_data());
        RouteServiceProvider::$container->set(WP_REST_Request::class, $wp_rest_request);
    }
}
