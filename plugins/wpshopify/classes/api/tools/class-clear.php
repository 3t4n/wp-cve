<?php

namespace ShopWP\API\Tools;

use ShopWP\Messages;

if (!defined('ABSPATH')) {
    exit();
}

class Clear extends \ShopWP\API
{
    public function __construct($Processing_Database)
    {
        $this->Processing_Database = $Processing_Database;
    }

    /*

	Clear Synced

	*/
    public function clear_all($request)
    {
        return $this->Processing_Database->delete_posts_and_synced_data();
    }

    /*

	Clear Synced

	*/
    public function clear_synced($request)
    {
        return $this->Processing_Database->delete_only_synced_data();
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_tools_clear_all()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/clear/all',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'clear_all'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_tools_clear_synced()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/clear/synced',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'clear_synced'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_tools_clear_all']);
        add_action('rest_api_init', [
            $this,
            'register_route_tools_clear_synced',
        ]);
    }
}
