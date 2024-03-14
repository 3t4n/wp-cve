<?php

namespace ShopWP\API\Processing;

if (!defined('ABSPATH')) {
    exit();
}

class Webhooks_Deletions extends \ShopWP\API
{
    public $Processing_Webhooks_Deletions;

    public function __construct(
        $Processing_Webhooks_Deletions,
        $DB_Settings_Syncing
    ) {
        $this->Processing_Webhooks_Deletions = $Processing_Webhooks_Deletions;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    /*

	Responsible for firing off a background process for smart collections

	*/
    public function process_webhooks($request)
    {
        $this->Processing_Webhooks_Deletions->process($request);
    }

    /*

	Register route: /process/webhooks

	*/
    public function register_route_process_webhooks()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/process/webhooks_delete',
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
