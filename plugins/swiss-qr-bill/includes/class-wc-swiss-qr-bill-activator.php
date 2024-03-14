<?php
if ( !defined('ABSPATH') ) {
    exit();
}

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes
 */
class WC_Swiss_Qr_Bill_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {

        if ( !class_exists('WooCommerce') ) {
            echo sprintf(esc_html__('Swiss QR Bill for WooCommerce depends on %s to work!', 'swiss-qr-bill'), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">' . esc_html__('WooCommerce', 'swiss-qr-bill') . '</a>');
            @trigger_error('', E_USER_ERROR);
        }

        $woocommerce_default_country = get_option('woocommerce_default_country', '');
        $country = explode(':', $woocommerce_default_country)[0];
        if ( !in_array(strtoupper($country), array('CH', 'LI')) ) {
            echo __('Swiss QR bill for WooCommerce only works for shops in Switzerland and Liechtenstein. You have chosen another country in your shop address, therefore the plugin cannot be activated', 'swiss-qr-bill');
            @trigger_error('', E_USER_ERROR);
        }

        if ( !is_dir(WC_SWISS_QR_BILL_UPLOAD_DIR) ) {
            mkdir(WC_SWISS_QR_BILL_UPLOAD_DIR, 0700);
        }
    }

}
