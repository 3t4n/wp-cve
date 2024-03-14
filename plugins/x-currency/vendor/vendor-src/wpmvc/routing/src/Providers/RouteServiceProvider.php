<?php

namespace XCurrency\WpMVC\Routing\Providers;

use XCurrency\WpMVC\Routing\Response;
use XCurrency\WpMVC\Routing\DataBinder;
use XCurrency\WpMVC\Routing\Ajax;
use XCurrency\WpMVC\Routing\Middleware;
use Wp;
abstract class RouteServiceProvider
{
    public static $container;
    protected static $properties;
    public function boot()
    {
        add_action('rest_api_init', [$this, 'action_rest_api_init']);
        add_action('parse_request', [$this, 'action_ajax_api_init'], 1);
    }
    /**
     * Fires once all query variables for the current request have been parsed.
     *
     * @param WP $wp Current WordPress environment instance (passed by reference).
     */
    public function action_ajax_api_init(WP $wp)
    {
        if (!isset($wp->request) || 1 !== \preg_match("@^" . static::$properties['ajax']['namespace'] . "/(.*)/?@i", $wp->request)) {
            return;
        }
        static::init_routes('ajax');
        if (!Ajax::$route_found) {
            status_header(404);
            Response::set_headers([]);
            echo wp_json_encode(['code' => 'ajax_no_route', 'message' => 'No route was found matching the URL and request method.']);
        }
        exit;
    }
    /**
     * Fires when preparing to serve a REST API request.
     */
    public function action_rest_api_init() : void
    {
        static::init_routes('rest');
    }
    protected static function init_routes(string $type)
    {
        Middleware::set_middleware_list(static::$properties['middleware']);
        $data_binder = static::$container->get(DataBinder::class);
        $data_binder->set_namespace(static::$properties[$type]['namespace']);
        include static::$properties['routes-dir'] . "/{$type}/api.php";
        $versions = static::$properties[$type]['versions'];
        if (\is_array($versions)) {
            foreach ($versions as $version) {
                $version_file = static::$properties['routes-dir'] . "/{$type}/{$version}/api.php";
                if (\is_file($version_file)) {
                    $data_binder->set_version($version);
                    include $version_file;
                }
            }
        }
    }
}
