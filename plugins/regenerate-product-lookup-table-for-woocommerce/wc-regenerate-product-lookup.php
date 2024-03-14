<?php
/**
 * Woocommerce regenerate product lookup table
 *
 * @package           SmnWcrpl
 * @author            Suman Bhattarai
 * @copyright         2021 Suman Bhattarai
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Regenerate product lookup table for WooCommerce
 * Plugin URI:        http://sumanbhattarai.com.np/woocommerce-auto-regenerate-product-lookup-table/
 * Description:       This plugin auto regenerates Woocommerce product lookup table.
 * Version:           1.0.3
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Author:            Suman Bhattarai
 * Author URI:        http://sumanbhattarai.com.np
 * Text Domain:       smnwcrpl
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


// No direct access to file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Load text domain
 */
function smnwcrpl_load_textdomain() {
	load_plugin_textdomain( 'smnwcrpl', false, plugin_dir_path( __FILE__ ) . 'languages/' );
}

add_action( 'plugins_loaded', 'smnwcrpl_load_textdomain' );


/**
 * Load files for admin setting area
 */
if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callbacks.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-postsave.php';
}


/**
 * Set default options for cron
 * @return string[]
 */
function smnwcrpl_options_default() {
	return [
		'cron_schedule_time' => 'twicedaily',
	];
}


/**
 * Function to run on product activation
 */
function smnwcrpl_register_cron_when_plugin_is_activated() {
	// If cron is not registered, register it
	if ( ! wp_next_scheduled( 'smnwcrpl_regenerate_product_lookup_table' ) ) {
		wp_schedule_event( time(), 'twicedaily', 'smnwcrpl_regenerate_product_lookup_table' );
	}

	// Add default schedule option
	add_option( 'smnwcrpl_options', [ 'cron_schedule_time' => 'twicedaily' ] );
}

add_action( 'smnwcrpl_regenerate_product_lookup_table', 'smnwcrpl_auto_regenerate_woocommerce_product_lookup_table' );


/**
 * Run Woocommerce product lookup table regeneration
 */
function smnwcrpl_auto_regenerate_woocommerce_product_lookup_table() {
	if ( function_exists( 'wc_update_product_lookup_tables' ) && function_exists( 'wc_update_product_lookup_tables_is_running' ) ) {
		if ( ! wc_update_product_lookup_tables_is_running() ) {
			wc_update_product_lookup_tables();
		}
	}
}


/**
 * Register cron when plugin is activated
 */
register_activation_hook( __FILE__, 'smnwcrpl_register_cron_when_plugin_is_activated' );


/**
 * WHEN PLUGIN IS DEACTIVATED
 * Cleanup
 */

/**
 * Register deactivation hook
 */
register_deactivation_hook( __FILE__, 'smnwcrpl_product_lookup_table_cron_deactivate' );


/**
 * Perform deactivation actions
 */
function smnwcrpl_product_lookup_table_cron_deactivate() {
	$timestamp = wp_next_scheduled( 'smnwcrpl_regenerate_product_lookup_table' );
	wp_unschedule_event( $timestamp, 'smnwcrpl_regenerate_product_lookup_table' );
}

/**
 * Add admin notice if Woocommerce is not active
 */
function smnwcrpl_admin_notices() {
	$pluginList = get_option( 'active_plugins' );
	$woo_plugin = 'woocommerce/woocommerce.php';

	if ( ! in_array( $woo_plugin, $pluginList ) ) {
		echo '<div class="notice notice-warning is-dismissible">
		             <p><strong>Regenerate product lookup table for WooCommerce</strong> only works with store created using <a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>.</p>
		         </div>';
	}
}

add_filter( 'admin_notices', 'smnwcrpl_admin_notices' );

/**
 * Add settings link to plugin actions
 *
 * @param  array  $plugin_actions
 * @param  string  $plugin_file
 */
function smnwcrpl_add_plugin_link( $plugin_actions, $plugin_file ) {

	$new_actions = array();

	if ( basename( plugin_dir_path( __FILE__ ) ) . '/wc-regenerate-product-lookup.php' === $plugin_file ) {
		$new_actions['cl_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'smnwcrpl' ),
			esc_url( admin_url( 'options-general.php?page=smnwcrpl' ) ) );
	}

	return array_merge( $new_actions, $plugin_actions );
}

add_filter( 'plugin_action_links', 'smnwcrpl_add_plugin_link', 10, 2 );

function filter_plugin_row_meta( array $plugin_meta, $plugin_file ) {
	if ( 'regenerate-product-lookup-table-for-woocommerce/wc-regenerate-product-lookup.php' !== $plugin_file ) {
		return $plugin_meta;
	}

	$plugin_meta[] = sprintf(
		'<a href="%1$s"><span class="dashicons dashicons-star-filled" aria-hidden="true" style="font-size:14px;line-height:1.3"></span>%2$s</a>',
		'https://www.buymeacoffee.com/smnbhattarai/',
		esc_html_x( 'Donate', 'verb', 'smnwcrpl' )
	);

	return $plugin_meta;
}

add_filter( 'plugin_row_meta', 'filter_plugin_row_meta', 10, 4 );