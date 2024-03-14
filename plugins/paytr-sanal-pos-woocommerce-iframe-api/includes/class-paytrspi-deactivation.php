<?php
if (!defined('ABSPATH')) {
    exit;
};

class PaytrCheckoutDeactivation
{
    public static function deactivate()
    {
        delete_option('woocommerce_paytrcheckout_settings');

        global $wpdb, $table_prefix;

        $table_name = $table_prefix . 'paytr_iframe_transaction';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}