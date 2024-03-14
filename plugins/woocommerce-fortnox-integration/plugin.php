<?php

/**
 * Plugin Name: Fortnox integration for WooCommerce
 * Plugin URI: https://plugins.svn.wordpress.org/woocommerce-fortnox-integration/
 * Description: A Fortnox 3 API Interface. Synchronizes products, orders and more to Fortnox. Also updated inventory from Fortnox to WooCommerce.
 * Version: 4.4.6
 * Author: wetail
 * Author URI: https://wetail.io
 * License: GPL2
 * WC tested up to: 8.3.1
 */

require_once "autoload.php";

use src\admin_views\WF_Product_Additional_Fields;
use src\api\WF_Routes;
use src\fortnox\api\WF_Orders;
use src\fortnox\api\WF_Refunds;
use src\fortnox\WF_Ajax;
use src\fortnox\WF_Plugin;
use src\fortnox\WF_Utils;

define( "WF_API_NAMESPACE", 'woocommerce_fortnox' );
define( "WF_SLUG", 'woocommerce-fortnox-integration' );
define( 'FORTNOX_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'SLUG', basename( __DIR__ ) );
define( 'PATH', dirname( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NAME', basename( __DIR__ ) );

register_activation_hook( __FILE__, 'activate_fortnox_plugin' );


add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

function render_notices(): void {
	echo '<div class="error"><p><strong>' . esc_html__( 'Fortnox integration for WooCommerce requires WooCommerce to be installed and active. Plugin deactivated', SLUG ) . '</strong></p></div>';
}

function activate_fortnox_plugin(): void {
	if ( ! is_woo_active() ) {
		add_action( 'admin_notices', 'render_notices');

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}

if ( ! function_exists( 'fortnox_write_log' ) ) {
	function fortnox_write_log( $log ) {

		if ( get_option( 'fortnox_debug_log' ) ) {
			$logger = wc_get_logger();
			$context = array( 'source' => 'fortnox' );
			if ( is_array( $log ) || is_object( $log ) ) {
				$logger->debug( print_r( $log, true ), $context );
			} else {
				$logger->debug( $log, $context );
			}
		}
	}
}


if ( ! function_exists( 'is_woo_active' ) ) {
	function is_woo_active(){
        if ( is_plugin_active('woocommerce/woocommerce.php')) {
            return true;
        }
        if ( is_plugin_active_for_network('woocommerce/woocommerce.php')) {
            return true;
        }
	}
}

/**
 * Load plugin textdomain and enable order auto sync action
 */
add_action( 'plugins_loaded', function () {

	# Deactivate plugin if woocommerce is not active and show error message
	if ( ! is_woo_active() ) {
		add_action( 'admin_notices', 'render_notices');
		deactivate_plugins( plugin_basename( __FILE__ ) );
		return;
	}

	WF_Plugin::load_text_domain();

	# Auto sync order if set in options page
	if ( get_option( 'fortnox_sync_on_status' ) ) {
		$sync_status = get_option( "fortnox_sync_on_status" );
		add_action( "woocommerce_order_status_" . $sync_status, function ( $order_id ) {
			try {
				WF_Orders::sync( $order_id );
			} catch ( \Exception $error ) {
				WF_Utils::maybe_mail_error( $order_id, $error->getMessage() . " (Felkod: " . $error->getCode() . ")" );
			}
		}, 9999999 );
	}

	add_action( 'wf_order_after_create_or_update', [ 'src\fortnox\api\WF_Invoices', "maybe_create_invoice" ], 10, 1 );

	# create credit note on refund
	if ( get_option( 'credit_note_on_refund' ) ) {
		add_action( 'woocommerce_order_refunded', [ 'src\fortnox\api\WF_Refunds', "handle_refund" ], 10, 2 );
	}

	// Backwards compat for above auto sync
	// when fortnox_sync_on_status isn't set, we know that we can only use status _completed
	if ( get_option( 'fortnox_auto_sync_orders' ) && ! get_option( "fortnox_sync_on_status" ) ) {
		add_action( 'woocommerce_order_status_complete', function ( $order_id ) {
			try {
				WF_Orders::sync( $order_id );
			} catch ( \Exception $error ) {
			}
		} );
	}
} );

/**
 * init
 */
add_action( 'init', function () {
	// Set sequential order number
	add_action( 'woocommerce_checkout_update_order_meta', [
		'src\fortnox\WF_Plugin',
		"set_sequential_order_number"
	], 10, 2 );
	add_action( 'woocommerce_process_shop_order_meta', [
		'src\fortnox\WF_Plugin',
		"set_sequential_order_number"
	], 10, 2 );
	add_action( 'woocommerce_api_create_order', [ 'src\fortnox\WF_Plugin', "set_sequential_order_number" ], 10, 2 );
	add_action( 'woocommerce_deposits_create_order', [
		'src\fortnox\WF_Plugin',
		"set_sequential_order_number"
	], 10, 2 );

	// Get sequential order number
	add_filter( 'woocommerce_order_number', [ 'src\fortnox\WF_Plugin', "get_sequential_order_number" ], 10, 2 );

	if ( get_option( 'show_organization_number_field_in_billing_address_form' ) ) {
		add_filter( 'woocommerce_checkout_fields', [
			'src\fortnox\WF_Plugin',
			"show_organization_number_form_field"
		], 10, 1 );
		add_action( 'woocommerce_process_shop_order_meta', [
			'src\fortnox\WF_Plugin',
			'save_billing_company_number'
		], 10, 1 );
	}
	add_action( 'woocommerce_admin_order_data_after_billing_address', [
		'src\fortnox\WF_Plugin',
		"custom_checkout_field_display_admin_order_meta"
	], 10, 1 );


} );

/**
 * admin_init
 */
add_action( 'woocommerce_init', function () {
	WF_Product_Additional_Fields::init();
} );

add_action( 'admin_init', function () {

	// Add settings
	if ( wp_doing_ajax() ) {
		return;
	}

	if ( isset( $_GET['page'] ) && $_GET['page'] == 'fortnox' || is_fortnox_options_page( $_SERVER['REQUEST_URI'] ) ) {
		WF_Plugin::add_settings();
	}

	// Add admin scripts
	add_action( 'admin_enqueue_scripts', [ 'src\fortnox\WF_Plugin', "add_admin_scripts" ] );

	// Add Fortnox column to class-wf-orders table
	add_filter( 'manage_edit-shop_order_columns', [ 'src\fortnox\WF_Plugin', "add_orders_table_columns" ] );
	add_filter( 'woocommerce_shop_order_list_table_columns', [ 'src\fortnox\WF_Plugin', "add_orders_table_columns" ] );

	// Get Fornox column content to class-wf-orders table
	add_action( 'manage_shop_order_posts_custom_column', [
		'src\fortnox\WF_Plugin',
		"print_orders_table_column_content"
	], 10, 2 );
	add_action( 'woocommerce_shop_order_list_table_custom_column', [
		'src\fortnox\WF_Plugin',
		"print_orders_table_column_content"
	], 10, 2 );

	// Add Fortnox column to product table
	add_filter( 'manage_edit-product_columns', [ 'src\fortnox\WF_Plugin', "add_products_table_columns" ] );

	// Get Fortnox column content to class-wf-products table
	add_action( 'manage_product_posts_custom_column', [
		'src\fortnox\WF_Plugin',
		"print_products_table_column_content"
	], 10, 2 );

	// Add Fortnox meta box to Product and Order views
	add_action( 'load-post.php', [ 'src\fortnox\WF_Plugin', "add_meta_boxes" ] );
	add_action( 'load-post-new.php', [ 'src\fortnox\WF_Plugin', "add_meta_boxes" ] );

	# Sync of the product with Fortnox
	add_action( 'save_post', [ 'src\fortnox\WF_Plugin', "sync_changes_to_fortnox" ] );
} );

function is_fortnox_options_page( $request_uri ) {
	$arr = explode( '/', $request_uri );

	if ( 'wp-admin' === $arr[ count( $arr ) - 2 ] && 'options.php' === $arr[ count( $arr ) - 1 ] ) {
		return true;
	}

	return false;
}

/**
 * Add settings page
 */
add_action( 'admin_menu', function () {
	WF_Plugin::add_settings_page();
} );

add_action( 'woocommerce_customer_loaded', 'wetail_fortnox_add_scripts_for_my_account' );

function wetail_fortnox_add_scripts_for_my_account() {
	add_action( 'wp_enqueue_scripts', [ 'src\fortnox\WF_Plugin', "add_scripts_for_my_account" ] );
}

WF_Ajax::init();

function wf_init_routes() {
	WF_Routes::register_routes();
}

add_filter( 'rest_api_init', 'wf_init_routes' );
add_filter( 'woocommerce_my_account_my_orders_actions', [
	'src\fortnox\WF_Plugin',
	'add_action_to_order_row_my_account'
], 10, 2 );
add_action( 'upgrader_process_complete', [ 'src\WF_Migrate', 'wp_update_completed' ], 10, 2 );
