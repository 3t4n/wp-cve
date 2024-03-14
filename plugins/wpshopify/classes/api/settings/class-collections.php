<?php

namespace ShopWP\API\Settings;

if (!defined('ABSPATH')) {
    exit();
}

class Collections extends \ShopWP\API
{
    public $DB_Settings_General;

    public function __construct($DB_Settings_General, $DB_Settings_Syncing)
    {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function get_setting_selected_collections($request)
    {
        $collections = maybe_unserialize(
            $this->DB_Settings_General->sync_by_collections()
        );

        return $this->handle_response([
            'response' => $collections,
        ]);
    }

    public function register_route_selected_collections()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/settings/selected_collections',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_setting_selected_collections'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_selected_collections',
        ]);
    }
}
