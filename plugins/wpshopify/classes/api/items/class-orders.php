<?php

namespace ShopWP\API\Items;

use ShopWP\Transients;
use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Orders extends \ShopWP\API
{
    public function __construct(
        $plugin_settings,
        $Shopify_API,
        $Admin_API_Orders
    ) {
        $this->Shopify_API = $Shopify_API;
        $this->Admin_API_Orders = $Admin_API_Orders;
        $this->plugin_settings = $plugin_settings;
    }

    /*
    
    Public API Method
    
    */
    public function get_orders($params) {

        $final_params = $this->public_api_default_values($params, 'orders');

        $cached_enabled = $this->plugin_settings['general']['enable_data_cache'];
        $data_cache_length = $this->plugin_settings['general']['data_cache_length'];
        $schema = !empty($final_params['schema']) ? $final_params['schema'] : false;

        $cache_key = md5('get_orders_' . $final_params['first'] . $final_params['query'] . $schema);

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_' . $cache_key));

            if (!empty($cached_query)) {
                return $cached_query;
            }
        }

        $result = $this->Admin_API_Orders->api_get_orders($final_params, $schema);

        if (is_wp_error($result)) {
            return $result;
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $cache_key, \maybe_serialize($result), $data_cache_length);
        }

        return $result;

    }

}
