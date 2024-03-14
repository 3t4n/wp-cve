<?php

namespace ShopWP\API\Syncing;

use ShopWP\Messages;
use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Indicator extends \ShopWP\API
{
    public $DB_Settings_Syncing;

    public function __construct($DB_Settings_Syncing)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    /*

	Update setting: add_to_cart_color

	*/
    public function set_syncing_indicator($request)
    {

        $syncing_on = $request->get_param('syncing');

        if (!is_bool($syncing_on)) {
            return $this->DB_Settings_Syncing->api_error('ShopWP Error: Unable to turn syncing on due to type error.', __METHOD__, __LINE__);
        }

        if ($syncing_on) {
            $this->DB_Settings_Syncing->reset_syncing_notices();

        } else {
            $this->DB_Settings_Syncing->expire_sync();
        }

        $toggle_syncing_result = $this->DB_Settings_Syncing->toggle_syncing($syncing_on);

        // If the DB update was unsuccessful ...
        if (!$toggle_syncing_result) {
            return $this->DB_Settings_Syncing->api_error('Error: Unable to change syncing status due to database error.', __METHOD__, __LINE__);
        }

        return $this->handle_response([
            'response' => $toggle_syncing_result,
        ]);
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_indicator()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/indicator',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'set_syncing_indicator'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_syncing_indicator',
        ]);
    }
}
