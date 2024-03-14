<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Base;

if (!defined('ABSPATH')) exit;

class S123_Activate
{
    public static function s123_activate()
    {
        static::s123_init_options();
        static::s123_add_to_database();
    }

    public static function s123_add_to_database()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . "woocommerce_tax_rates";
        $wpdb->query("ALTER TABLE {$tableName} ADD COLUMN s123_tax_id VARCHAR(50) NULL DEFAULT NULL");
    }


    public static function s123_init_options()
    {
        add_option('s123-invoices', [
            'api_key' => '',
            'use_custom_inputs' => false,
            'plugin_version' => '',
            'use_order_status' => 'completed',
        ]);
    }
}
