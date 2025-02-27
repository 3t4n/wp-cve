<?php
/**
 * Stancer
 *
 * @package stancer
 * @license MIT
 * @copyright 2023-2024 Stancer / Iliad 78
 *
 * @wordpress-plugin
 * Plugin Name: Stancer
 * Plugin URI:  https://gitlab.com/wearestancer/cms/woocommerce
 * Description: Simple payment solution at low prices.
 * Version:     1.1.2
 * Author:      Stancer
 * Author URI:  https://www.stancer.com/
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 * Domain Path: /languages
 * Text Domain: stancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Currently plugin version.
 */
define( 'STANCER_WC_VERSION', '1.1.2' );
define( 'STANCER_ASSETS_VERSION', '1708362019113' );
define( 'STANCER_FILE', __FILE__ );
define( 'STANCER_DIRECTORY_PATH', plugin_dir_path( STANCER_FILE ) );

require_once STANCER_DIRECTORY_PATH . '/vendor/autoload.php';

add_action( 'plugins_loaded', 'load_translations' );

// Add links on plugins.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'plugin_action_links' );

/**
 * Wrapper to load our translations.
 */
function load_translations() {
	load_plugin_textdomain( 'stancer', false, plugin_basename( dirname( STANCER_FILE ) ) . '/languages' );
}

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	/**
	 * Check if WooCommerce is activated.
	 *
	 * Simple stub, just in case.
	 */
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' );
	}
}

/**
 * Add links on plugins.
 *
 * @since 1.1.0
 *
 * @param array $links Plugin Action links.
 *
 * @return array
 * */
function plugin_action_links( array $links ) {
	$locale_limit = 1;
	$locale = str_replace( '_', '-', get_locale(), $locale_limit );

	$new = [
		'settings' => vsprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			[
				admin_url( 'admin.php?page=wc-settings&tab=checkout&section=stancer' ),
				esc_attr__( 'View Stancer module settings', 'stancer' ),
				esc_html__( 'Settings', 'stancer' ),
			],
		),
		'manage' => vsprintf(
			'<a href="%s" target="_blank" rel="noopener, noreferrer" aria-label="%s">%s</a>',
			[
				sprintf( 'https://manage.stancer.com/%s/', $locale ),
				esc_attr__( 'Go to Stancer Customer Account', 'stancer' ),
				esc_html__( 'Customer Account', 'stancer' ),
			],
		),
	];

	return array_merge( $new, $links );
}

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_stancer() {
	$plugin = new WC_Stancer();
	$plugin->run();
}

run_stancer();
