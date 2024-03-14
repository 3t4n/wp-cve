<?php
/**
 * Plugin Name: Woo Addons for Elementor - Stax
 * Description: WooCommerce Addons and Widgets for Elementor builder
 * Plugin URI: https://staxwp.com/elementor/woocommerce-addons-widgets/
 * Author: StaxWP
 * Version: 1.1.1
 * Author URI: https://staxwp.com
 *
 * Elementor tested up to: 3.14.1
 * Elementor Pro tested up to: 3.14.1
 *
 * Text Domain: stax-woo-addons-for-elementor
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'STAX_WOO_VERSION', '1.1.1' );
define( 'STAX_WOO_DOMAIN', 'stax-woo-addons-for-elementor' );
define( 'STAX_WOO_HOOK_PREFIX', 'stax_woocommerce_' );
define( 'STAX_WOO_SLUG_PREFIX', 'stax-woocommerce-' );

define( 'STAX_WOO_FILE', __FILE__ );
define( 'STAX_WOO_PLUGIN_BASE', plugin_basename( STAX_WOO_FILE ) );
define( 'STAX_WOO_PATH', plugin_dir_path( STAX_WOO_FILE ) );
define( 'STAX_WOO_URL', plugins_url( '/', STAX_WOO_FILE ) );
define( 'STAX_WOO_CORE_PATH', STAX_WOO_PATH . 'core/' );
define( 'STAX_WOO_WIDGET_PATH', STAX_WOO_PATH . 'widgets/' );
define( 'STAX_WOO_ENH_PATH', STAX_WOO_PATH . 'enhancements/' );
define( 'STAX_WOO_EXTRA_PATH', STAX_WOO_PATH . 'extra/' );
define( 'STAX_WOO_WIDGET_URL', STAX_WOO_URL . 'widgets/' );
define( 'STAX_WOO_ASSETS_URL', STAX_WOO_URL . 'assets/' );

/*
 * Localization
 */
function stax_woocommerce_load_plugin_textdomain() {
	load_plugin_textdomain( 'stax-woo-addons-for-elementor', false, basename( __DIR__ ) . '/languages/' );
}

add_action( 'plugins_loaded', 'stax_woocommerce_load_plugin_textdomain' );


require __DIR__ . '/vendor/autoload.php';

// Init plugin
require_once STAX_WOO_CORE_PATH . 'Plugin.php';

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_stax_woo_addons_for_elementor() {

	if ( ! class_exists( 'Appsero\Client' ) ) {
		require_once __DIR__ . '/vendor/appsero/client/src/Client.php';
	}

	$client = new Appsero\Client( '80b48872-f803-4f38-888d-dbd72eee1c54', 'Woo Addons for Elementor &#8211; Stax', __FILE__ );

	// Active insights
	$client->insights()->init();

}

appsero_init_tracker_stax_woo_addons_for_elementor();
