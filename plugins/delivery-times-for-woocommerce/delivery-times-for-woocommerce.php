<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.deviodigital.com/
 * @since             1.0
 * @package           DTWC
 *
 * @wordpress-plugin
 * Plugin Name:          Delivery Times for WooCommerce
 * Plugin URI:           https://deviodigital.com/how-to-use-the-delivery-times-for-woocommerce-plugin/
 * Description:          Allow your customers to choose their desired delivery date and time during checkout with WooCommerce
 * Version:              1.8.0
 * Author:               Devio Digital
 * Author URI:           https://www.deviodigital.com/
 * License:              GPL-2.0+
 * License URI:          http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:          delivery-times-for-woocommerce
 * Domain Path:          /languages
 * WC requires at least: 3.5.0
 * WC tested up to:      6.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Current plugin version.
 */
define( 'DTWC_VERSION', '1.8.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dtwc-activator.php
 */
function activate_dtwc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dtwc-activator.php';
	Delivery_Times_For_WooCommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dtwc-deactivator.php
 */
function deactivate_dtwc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dtwc-deactivator.php';
	Delivery_Times_For_WooCommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dtwc' );
register_deactivation_hook( __FILE__, 'deactivate_dtwc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dtwc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_dtwc() {

	$plugin = new DTWC();
	$plugin->run();

}
run_dtwc();

/**
 * Add settings link on plugin page
 *
 * @since 1.0.3
 * @param array $links an array of links related to the plugin.
 * @return array updatead array of links related to the plugin.
 */
function dtwc_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=dtwc_settings">' . esc_attr__( 'Settings', 'delivery-times-for-woocommerce' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

$pluginname = plugin_basename( __FILE__ );

add_filter( "plugin_action_links_$pluginname", 'dtwc_settings_link' );

/**
 * Add a check for our plugin before redirecting
 */
function dtwc_activate() {
  add_option( 'dtwc_do_activation_redirect', true );
}
register_activation_hook( __FILE__, 'dtwc_activate' );

/**
 * Redirect to the Delivery Times for WooCommerce Settings page on single plugin activation
 *
 * @since 1.0
 */
function dtwc_redirect() {
	if ( get_option( 'dtwc_do_activation_redirect', false ) ) {
			delete_option( 'dtwc_do_activation_redirect' );
			if ( null === filter_input( INPUT_POST, 'activate-multi' ) ) {
					wp_safe_redirect( 'admin.php?page=dtwc_settings' );
			}
	}
}
add_action( 'admin_init', 'dtwc_redirect' );
