<?php

namespace Qodax\CheckoutManager\Foundation\Routing;

if ( ! defined('ABSPATH')) {
    exit;
}

class Router
{
    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @param Route $route
     */
    public function addRoute($route)
    {
        $this->routes[ $route->action ] = $route;

        add_action('wp_ajax_' . $route->action, [ $this, 'dispatchAction' ]);

        if ($route->public) {
            add_action('wp_ajax_nopriv_' . $route->action, [ $this, 'dispatchAction' ]);
        }
    }

    public function dispatchAction()
    {
        $action = sanitize_text_field($_POST['action']);

        if (isset($this->routes[$action])) {
            $this->routes[$action]->dispatch($_POST);
        }
    }
}