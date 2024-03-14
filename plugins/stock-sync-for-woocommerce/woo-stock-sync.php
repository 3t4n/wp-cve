<?php

/*
Plugin Name: Stock Sync for WooCommerce
Description: Stock synchronization for WooCommerce. Share same product stock in two WooCommerce stores.
Version:     2.6.2
Author:      Lauri Karisola / WP Trio
Author URI:  https://wptrio.com
Text Domain: woo-stock-sync
Domain Path: /languages
WC requires at least: 6.0.0
WC tested up to: 8.0.0
*/

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin file
 */
if ( ! defined( 'WOO_STOCK_SYNC_FILE' ) ) {
	define( 'WOO_STOCK_SYNC_FILE', __FILE__ );
}

/**
 * Load Composer libs
 */
require __DIR__ . '/vendor/autoload.php';

/**
 * Load DB table file
 */
require __DIR__ . '/includes/woo-stock-sync-db-table.php';

/**
 * Load plugin textdomain
 *
 * @return void
 */
add_action( 'plugins_loaded', 'woo_stock_sync_load_textdomain' );
function woo_stock_sync_load_textdomain() {
  load_plugin_textdomain( 'woo-stock-sync', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * HPOS compatibility
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

class Woo_Stock_Sync {
	/**
	 * Constructor
	 */
	public function __construct() {
		if ( class_exists( 'Woo_Stock_Sync_Pro' ) ) {
			return;
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$this->define_constants();

		$this->includes();
	}

	/**
	 * Define constants
	 */
	public function define_constants() {
		if ( ! defined( 'WOO_STOCK_SYNC_DIR_URL' ) ) {
			define( 'WOO_STOCK_SYNC_DIR_URL', plugin_dir_url( __FILE__ ) );
		}

		if ( ! defined( 'WOO_STOCK_SYNC_VERSION' ) ) {
			define( 'WOO_STOCK_SYNC_VERSION', '2.6.2' );
		}

		if ( ! defined( 'WOO_STOCK_SYNC_BASENAME' ) ) {
			define( 'WOO_STOCK_SYNC_BASENAME', plugin_basename( __FILE__ ) );
		}

		if ( ! defined( 'WOO_STOCK_SYNC_DIR_PATH' ) ) {
			define( 'WOO_STOCK_SYNC_DIR_PATH', plugin_dir_path( __FILE__ ) );
		}
	}

	/**
	 * Include required files
	 */
	public function includes() {
		$this->load_class( 'includes/woo-stock-sync-utils.php' );
		$this->load_class( 'includes/woo-stock-sync-logger.php' );
		$this->load_class( 'includes/woo-stock-sync-api-client.php' );
		$this->load_class( 'includes/woo-stock-sync-api-request.php' );
		$this->load_class( 'includes/woo-stock-sync-api-check.php' );
		$this->load_class( 'includes/woo-stock-sync-process.php', 'Woo_Stock_Sync_Process' );
		$this->load_class( 'includes/woo-stock-sync-tracker-primary.php', 'Woo_Stock_Sync_Tracker_Primary' );
		$this->load_class( 'includes/woo-stock-sync-tracker-secondary.php', 'Woo_Stock_Sync_Tracker_Secondary' );
		$this->load_class( 'includes/woo-stock-sync-rest-controller.php' );
		$this->load_class( 'includes/frontend/class-woo-stock-sync-frontend.php', 'Woo_Stock_Sync_Frontend' );

		if ( is_admin() ) {
			$this->admin_includes();
		}

		$this->load_class( 'includes/wp-flash-messages.php', FALSE );
	}

	/**
	 * Include admin files
	 */
	private function admin_includes() {
		$this->load_class( 'includes/admin/class-woo-stock-sync-admin.php', 'Woo_Stock_Sync_Admin' );
		$this->load_class( 'includes/admin/class-woo-stock-sync-ui.php', 'Woo_Stock_Sync_Ui' );
	}

	/**
	 * Load class
	 */
	private function load_class( $filepath, $class_name = FALSE ) {
		require_once( WOO_STOCK_SYNC_DIR_PATH . $filepath );

		if ( $class_name ) {
			return new $class_name;
		}

		return TRUE;
	}
}

add_action( 'plugins_loaded', 'woo_stock_sync_load', 15 );
function woo_stock_sync_load() {
	new Woo_Stock_Sync();
}
