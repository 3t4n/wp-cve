<?php

namespace ShopWP\API\Tools;

use ShopWP\Messages;
use ShopWP\Utils;
use ShopWP\Options;

if (!defined('ABSPATH')) {
    exit();
}

class Cache extends \ShopWP\API
{
    public function __construct($DB_Settings_Syncing)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function delete_cache($request)
    {

        $result = $this->DB_Settings_Syncing->expire_sync();

        if (empty($result['delete_cache'])) {
            return $this->DB_Settings_Syncing->api_error('Unable to clear ShopWP cache. Please reload and try again.', __METHOD__, __LINE__);
        }
        
        return $result;
    }

    public function toggle_cache_clear($request)
    {
        return Options::update('shopwp_cache_cleared', false);
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_tools_delete_cache()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE, 
            '/cache', 
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_cache'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_tools_cache_toggle()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/cache/toggle',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'toggle_cache_clear'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_tools_delete_cache',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_tools_cache_toggle',
        ]);
    }
}
