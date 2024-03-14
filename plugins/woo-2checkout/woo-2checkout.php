<?php
/**
 * Payment Gateway - 2Checkout for WooCommerce
 *
 * @package    StorePress\PaymentGateway
 *
 * @wordpress-plugin
 * Plugin Name:          Payment Gateway - 2Checkout for WooCommerce
 * Plugin URI:           https://wordpress.org/plugins/woo-2checkout/
 * Description:          2Checkout Payment Gateway for WooCommerce. Requires WooCommerce 5.5+
 * Author:               Emran Ahmed
 * Version:              3.0.0
 * Requires PHP:         7.4
 * Requires at least:    6.1
 * Tested up to:         6.4
 *
 * WC requires at least: 8.1
 * WC tested up to:      8.6
 * Text Domain:          woo-2checkout
 * Author URI:           https://getwooplugins.com/
 * License:              GPL v3 or later
 * License URI:          https://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path:          /languages
 */

defined( 'ABSPATH' ) || die( 'Keep Silent' );

use StorePress\TwoCheckoutPaymentGateway\Plugin;

if ( ! defined( 'STOREPRESS_TWO_CHECKOUT_PLUGIN_FILE' ) ) {
	define( 'STOREPRESS_TWO_CHECKOUT_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'STOREPRESS_TWO_CHECKOUT_COMPATIBLE_EXTENDED_VERSION' ) ) {
	define( 'STOREPRESS_TWO_CHECKOUT_COMPATIBLE_EXTENDED_VERSION', '3.0.0' );
}

// Include the Plugin class.
if ( ! class_exists( '\StorePress\TwoCheckoutPaymentGateway\Plugin' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Plugin.php';
}

/**
 * Get compatible version of extended plugin.
 *
 * @return string
 */
function woo_2checkout_compatible_pro_version(): string {
	return constant( 'STOREPRESS_TWO_CHECKOUT_COMPATIBLE_EXTENDED_VERSION' );
}

/**
 * The main function that returns the Plugin class
 *
 * @return Plugin|false
 * @since 2.1.0
 */
function woo_2checkout() {
	// Include the main class.

	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	if ( function_exists( 'woo_2checkout_pro' ) ) {
		return woo_2checkout_pro();
	}

	return Plugin::instance();
}

// Get the plugin running.
add_action( 'plugins_loaded', 'woo_2checkout' );

/**
 * Admin Notice for required plugin.
 *
 * @return void
 */
function woo_2checkout_requirements_notice() {
	if ( ! class_exists( 'WooCommerce' ) ) {

		$text = esc_html__( 'WooCommerce', 'woo-2checkout' );

		$plugin_args = array(
			'tab'       => 'plugin-information',
			'plugin'    => 'woocommerce',
			'TB_iframe' => 'true',
			'width'     => '640',
			'height'    => '500',
		);

		$link = add_query_arg( $plugin_args, admin_url( 'plugin-install.php' ) );

		$message = __( '<strong>Payment Gateway - 2Checkout for WooCommerce</strong> is an add-on of ', 'woo-2checkout' );

		printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', 'notice notice-error', wp_kses_post( $message ), esc_url( $link ), esc_html( $text ) );
	}
}

add_action( 'admin_notices', 'woo_2checkout_requirements_notice' );

// Deactivate too old extended plugin.
add_action(
	'admin_init',
	function () {

		$plugin_file = 'woo-2checkout-pro/woo-2checkout-pro.php';

		$file   = wp_normalize_path( $plugin_file );
		$plugin = plugin_basename( $file );

		$abs_file = trailingslashit( WP_PLUGIN_DIR ) . $plugin;

		if ( ! file_exists( $abs_file ) ) {
			return;
		}

		$data             = get_plugin_data( $abs_file );
		$required_version = woo_2checkout_compatible_pro_version();
		$current_version  = sanitize_text_field( $data['Version'] );

		if ( is_plugin_inactive( $plugin_file ) ) {
			return;
		}

		// Yes. Compatible.
		if ( version_compare( $current_version, $required_version, '>=' ) ) {
			return;
		}

		// Deactivate the plugin silently, Prevent deactivation hooks from running.
		deactivate_plugins( $plugin_file, true );
	},
	12
);
