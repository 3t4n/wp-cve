<?php
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fired during plugin activation
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
 * @author     theDotstore <wordpress@multidots.in>
 */
class Woo_Hide_Shipping_Methods_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        set_transient('_welcome_screen_whsm_mode_activation_redirect_data', true, 30);
        add_option('whsm_version', WOO_HIDE_SHIPPING_METHODS_VERSION);
        $hide_shipping_option = get_option( 'hide_shipping_option' );

        $active_plugins = get_option( 'active_plugins', array() );        
		if ( is_multisite() ) {
            $network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
			$active_plugins = array_unique( $active_plugins );
			
            if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', $active_plugins ), true ) ) {
                wp_die("<strong>Hide Shipping Method For WooCommerce</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . esc_url(get_admin_url(null, 'plugins.php')) . "'>Plugins page</a>.");
			} else {
                if ( empty( $hide_shipping_option ) ){
                    update_option( 'hide_shipping_option', 'free_shipping_available' );
                }
            }
		} else {
			if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				wp_die("<strong>Hide Shipping Method For WooCommerce</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . esc_url(get_admin_url(null, 'plugins.php')) . "'>Plugins page</a>.");
			} else {
                if ( empty( $hide_shipping_option ) ){
                    update_option( 'hide_shipping_option', 'free_shipping_available' );
                }
            }
		}
    }
}