<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AWC_Search_Gateway
 */
class AWC_Search_Gateway
{
    public static function awc_get_gateway_billet()
    {
        global $wpdb;

        $gateway = 'woocommerce_appmax-billet_settings';

        $sql = 'select option_value from %s where option_name = \'%s\' limit 1';
        $stmt = sprintf( $sql, $wpdb->options, $gateway);
        $result = $wpdb->get_row( $stmt, OBJECT );

        return unserialize( $result->option_value );
    }

    public static function awc_get_gateway_credit_card()
    {
        global $wpdb;

        $gateway = 'woocommerce_appmax-credit-card_settings';

        $sql = 'select option_value from %s where option_name = \'%s\' limit 1';
        $stmt = sprintf( $sql, $wpdb->options, $gateway);
        $result = $wpdb->get_row( $stmt, OBJECT );

        return unserialize( $result->option_value );
    }

    public static function awc_get_gateway_pix()
    {
        global $wpdb;

        $gateway = 'woocommerce_appmax-pix_settings';

        $sql = 'select option_value from %s where option_name = \'%s\' limit 1';
        $stmt = sprintf( $sql, $wpdb->options, $gateway);
        $result = $wpdb->get_row( $stmt, OBJECT );

        return unserialize( $result->option_value );
    }
}