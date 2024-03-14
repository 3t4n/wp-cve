<?php
/**
 * Plugin Name:             Stock Sync with Google Sheet for WooCommerce
 * Plugin URI:              https://wppool.dev/stock-sync-with-google-sheet-for-woocommerce/
 * Description:             Sync your WooCommerce product stock with Google Sheets.
 * Version:                 3.8.1
 * Author:                  WPPOOL
 * Author URI:              https://wppool.dev/
 * Text Domain:             stock-sync-with-google-sheet-for-woocommerce
 * Domain Path:             /languages
 * License:                 GPLv2 or later
 * License URI:             http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// If not SSGSW_FILE defined, then define it.
if ( ! defined('SSGSW_FILE') ) {
	// File.
	define('SSGSW_FILE', __FILE__);

	// Version.
	define( 'SSGSW_VERSION', '3.8.1' );
	/**
	 * Loading base file
	 * If you are a developer, please don't change this file location
	 */
	if ( file_exists(__DIR__ . '/includes/boot.php') ) {
		require_once __DIR__ . '/includes/boot.php';
	}
}
add_action( 'plugins_loaded', 'ssgsw_load_plugin_textdomain' );
/**
 * Loaded plugin text domain for translation
 *
 * @return bool
 */
function ssgsw_load_plugin_textdomain() {
	$domain = 'stock-sync-with-google-sheet-for-woocommerce';
	$dir    = untrailingslashit( WP_LANG_DIR );
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	$exists = load_textdomain( $domain, $dir . '/plugins/' . $domain . '-' . $locale . '.mo' );
	if ( $exists ) {
		return $exists;
	} else {
		load_plugin_textdomain( $domain, false, basename( __DIR__ ) . '/languages/' );
	}
}

/**
 * Manipulating the plugin code WILL NOT ALLOW you to use the premium features.
 * Please download the free version of the plugin from https://wordpress.org/plugins/stock-sync-with-google-sheet-for-woocommerce/
 * Powered by WPPOOL
 * https://wppool.dev/
 */
