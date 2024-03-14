<?php

namespace ShopWP\API\Items;

use ShopWP\Messages;
use ShopWP\Utils;
use ShopWP\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class Shop extends \ShopWP\API
{
    public function __construct($plugin_settings, $Storefront_Shop)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Storefront_Shop = $Storefront_Shop;
    }

	public function handle_get_localizations($request) {
        
        $cached_enabled = $this->plugin_settings['general']['enable_data_cache'];
        $data_cache_length = $this->plugin_settings['general']['data_cache_length'];

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_available_translations'));

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }

        $response = $this->Storefront_Shop->api_get_available_localizations();

        if (\is_wp_error($response)) {
            return \wp_send_json_error($response);
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_available_translations', \maybe_serialize($response), $data_cache_length);
        }

        return \wp_send_json_success($response);

	}

    public function register_routes() {
        $this->api_route('/query/translator/available', 'GET', [$this, 'handle_get_localizations']);
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
}
