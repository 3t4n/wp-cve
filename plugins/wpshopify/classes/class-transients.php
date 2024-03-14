<?php

namespace ShopWP;

use ShopWP\Options;

if (!defined('ABSPATH')) {
    exit();
}

class Transients
{
    public function __construct() {}

    public static function delete_single($name)
    {
        return delete_transient($name);
    }

    public static function set($name, $value, $time = 0)
    {
        return set_transient($name, $value, $time);
    }

    public static function get($name)
    {
        return get_transient($name);
    }

    public static function delete_plugin_options()
    {
        global $wpdb;

        $query = "SELECT option_name FROM " . $wpdb->options . " WHERE option_name LIKE 'shopwp_%' OR option_name LIKE 'wps_settings_%' OR option_name LIKE '%_transient_shopwp_%' OR option_name LIKE 'wps_admin_%'";

        $plugin_options = $wpdb->get_results($query);

        foreach ($plugin_options as $option) {
            Options::delete($option->option_name);
        }
    }

    public static function delete_custom_options()
    {
        $results = [];

        $results['shopwp_custom_options'] = self::delete_plugin_options();

        return $results;
    }

    public static function delete_plugin_cache()
    {
        global $wpdb;

        $query = "DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_%' OR `option_name` LIKE '%_transient_timeout_wps_%' OR `option_name` LIKE '%_wps_background_processing_%' OR `option_name` LIKE '%_transient_wps_async_processing_%' OR `option_name` LIKE '%wp_wps_background_processing%' OR `option_name` LIKE '%wps_product_data_%' OR `option_name` LIKE '%_transient_shopwp_%' OR `option_name` LIKE '%shopwp_background_processing%' OR `option_name` LIKE '%shopwp_%' OR `option_name` LIKE '%wps_wp_%' OR `option_name` LIKE '%wp_shopify_%'";

        $results = $wpdb->query($query);

        if ($results === false) {
            return Utils::wp_error([
                'message_lookup' => 'delete_plugin_cache',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        } else {
            return true;
        }
    }

    public static function delete_cached_settings()
    {
        global $wpdb;

        $query = "DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_settings_%' OR `option_name` LIKE '%_transient_wps_table_single_row_%'";

        $results = $wpdb->query($query);

        if ($results === false) {
            return Utils::wp_error([
                'message_lookup' => 'delete_cached_settings',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        }

        return true;
    }

    public static function delete_cached_product_queries()
    {
        global $wpdb;

        $query = "DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_shopwp_products_query_hash_cache_%'";
        $results = $wpdb->query($query);

        if ($results === false) {
            return Utils::wp_error([
                'message_lookup' => 'delete_cached_products_queries',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        }

        return $results;
    }

    public static function delete_cached_collection_queries()
    {
        global $wpdb;

        $query = "DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_wps_collections_query_hash_cache_%'";
        $results = $wpdb->query($query);

        if ($results === false) {
            return Utils::wp_error([
                'message_lookup' => 'delete_cached_collection_queries',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        }

        return $results;
    }
}
