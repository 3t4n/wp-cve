<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Base;

if (!defined('ABSPATH')) exit;

class S123_Deactivate
{
    public static function s123_deactivate()
    {
        static::s123_delete_options();
        static::s123_drop_database_data();
        static::i123_clear_cron_jobs();
        flush_rewrite_rules();
    }

    public static function s123_drop_database_data()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . "woocommerce_tax_rates";

        $wpdb->query("ALTER TABLE {$tableName} DROP COLUMN s123_tax_id");
    }

    public static function s123_delete_options()
    {
        delete_option('s123-invoices');
    }

    public static function i123_clear_cron_jobs()
    {
        wp_clear_scheduled_hook('i123_sync_warehouse_cron_hook');
    }
}