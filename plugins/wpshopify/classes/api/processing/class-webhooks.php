<?php

namespace ShopWP\API\Processing;

if (!defined('ABSPATH')) {
    exit();
}

class Webhooks extends \ShopWP\API
{
    public $Processing_Webhooks;

    public function __construct($Processing_Webhooks, $DB_Settings_Syncing)
    {
        $this->Processing_Webhooks = $Processing_Webhooks;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    /*

	Responsible for firing off a background process for syncing webhooks

	*/
    public function process_webhooks($request)
    {
        $this->Processing_Webhooks->process($request);
    }

    /*

	Register route: /process/webhooks

	*/
    public function register_route_process_webhooks()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/process/webhooks',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'process_webhooks'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_process_webhooks']);
    }
}
