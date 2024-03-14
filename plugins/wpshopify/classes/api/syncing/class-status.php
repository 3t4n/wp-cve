<?php

namespace ShopWP\API\Syncing;

use ShopWP\Messages;

if (!defined('ABSPATH')) {
    exit();
}

class Status extends \ShopWP\API
{
    public $DB_Settings_Syncing;

    public function __construct($DB_Settings_Syncing)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function get_syncing_status($request)
    {
        return [
            'is_syncing' => $this->DB_Settings_Syncing->is_syncing(),
            'syncing_errors' => $this->get_syncing_errors(),
            'syncing_totals' => $this->DB_Settings_Syncing->syncing_totals(),
            'syncing_current_amounts' => $this->DB_Settings_Syncing->syncing_current_amounts(),
            'recently_syncd_media_ref' => $this->DB_Settings_Syncing->get_recently_syncd_media_ref(),
            'current_syncing_step_text' => $this->DB_Settings_Syncing->get_current_syncing_step_text(),
            'finished_data_deletions' => $this->get_syncing_status_removal(),
            'finished_webhooks_deletions' => $this->get_syncing_status_webhooks_deletion(),
            'finished_webhooks_connection' => $this->get_syncing_status_webhooks_connection(),
            'finished_removing_connection' => $this->get_syncing_status_remove_connection(),
            'finished_media' => $this->get_syncing_status_media(),
            'finished_syncing_data' => $this->get_has_finished_syncing_data(),
            'percent_completed_removal' => $this->get_percent_completed_removal(),
        ];
    }

    public function get_syncing_errors() {

        $any_errors = maybe_unserialize($this->DB_Settings_Syncing->get_column_single('syncing_errors'));

        if (empty($any_errors) || empty($any_errors[0]->syncing_errors)) {
            return false;
        }
                
        return maybe_unserialize($any_errors[0]->syncing_errors);

    }

    public function get_percent_completed_removal() {

        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return 100;
        }

        $percent_completed_removal = $this->DB_Settings_Syncing->get_col_val('percent_completed_removal', 'int');

        return $percent_completed_removal;

    }

    public function get_has_finished_syncing_data() {

        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }
        
        $syncing_current_amounts_products = $this->DB_Settings_Syncing->get_col_val('syncing_current_amounts_products', 'int');
        $syncing_totals_products = $this->DB_Settings_Syncing->get_col_val('syncing_totals_products', 'int');

        if ($syncing_current_amounts_products === 0 && $syncing_totals_products === 0) {
            return false;
        }

        if ($syncing_current_amounts_products === $syncing_totals_products) {
            return true;
        }

        return false;
    }

    public function get_syncing_status_webhooks_deletion()
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }

        return $this->DB_Settings_Syncing->get_col_val('finished_webhooks_deletions', 'bool');
    }

    public function get_syncing_status_remove_connection()
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }

        return $this->DB_Settings_Syncing->get_col_val('finished_removing_connection', 'bool');
    }

    public function get_syncing_status_webhooks_connection()
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }
        
        $syncing_current_amounts_webhooks = $this->DB_Settings_Syncing->get_col_val('syncing_current_amounts_webhooks', 'int');
        $syncing_totals_webhooks = $this->DB_Settings_Syncing->get_col_val('syncing_totals_webhooks', 'int');

        if ($syncing_current_amounts_webhooks === 0 && $syncing_totals_webhooks === 0) {
            return false;
        }

        if ($syncing_current_amounts_webhooks === $syncing_totals_webhooks) {
            return true;
        }

        return false;
    }

    public function get_syncing_status_removal()
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }

        return $this->DB_Settings_Syncing->get_col_val('finished_data_deletions', 'bool');

    }

    public function get_syncing_status_media()
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }

        return $this->DB_Settings_Syncing->get_col_val('finished_media', 'bool');

    }

    // Fires once the syncing process stops
    public function get_syncing_notices($request)
    {
        return $this->handle_response(
            $this->DB_Settings_Syncing->syncing_notices()
        );
    }

    // Fires once the syncing process stops
    public function delete_syncing_notices($request)
    {
        return $this->handle_response(
            $this->DB_Settings_Syncing->reset_syncing_notices()
        );
    }

    public function register_route_syncing_status()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/status',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_syncing_status'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_syncing_status_webhooks()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/status/webhooks',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_status_webhooks'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_syncing_status_removal()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/status/removal',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_status_removal'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_syncing_status_media()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/status/media',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_status_media'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_syncing_stop()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/stop',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'expire_sync'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function expire_sync()
    {

        $result = $this->handle_response([
            'response' => $this->DB_Settings_Syncing->expire_sync(),
        ]);

        update_option('shopwp_should_flush_rewrite_rules', 1);
        do_action('shopwp_processing_completed');
       
        return $result;
    }

    public function init()
    {

        add_action('rest_api_init', [$this, 'register_route_syncing_status']);

        add_action('rest_api_init', [
            $this,
            'register_route_syncing_status_webhooks',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_syncing_status_removal',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_syncing_status_media',
        ]);
        add_action('rest_api_init', [$this, 'register_route_syncing_stop']);
    }
}
