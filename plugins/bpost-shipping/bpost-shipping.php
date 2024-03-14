<?php
/**
 * Plugin Name: bpost shipping
 * Plugin URI: https://wordpress.org/plugins/bpost-shipping/
 * Description: bpost Shipping Manager is a service offered by bpost, allowing your customer to choose their preferred delivery method when ordering in your Woocommerce webshop.
 * Author: bpost
 * Author URI: https://www.bpost.be/
 * Version: 3.0.7
 * WC requires at least: 3.0
 * WC tested up to: 8.9
 * Requires PHP: 7.4
 */

define( 'BPOST_PLUGIN_ID', 'bpost_shipping' );
define( 'BPOST_PLUGIN_DIR', __DIR__ );
define( 'BPOST_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BPOST_PLUGIN_VERSION', '3.0.7' );

/**
 * Check if WooCommerce is active
 */
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div id="message" class="error">
			<p>Woocommerce is required to use bpost shipping plugin.</p>
		</div>';
	} );

	return;
}

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoloader.php';

$bpost_shipping_hooks = new WC_BPost_Shipping_Hooks();

/**
 * Hooks creation
 */
register_activation_hook( __FILE__, array( $bpost_shipping_hooks, 'bpost_shipping_cron_cache_activation' ) );
register_deactivation_hook( __FILE__, array( $bpost_shipping_hooks, 'bpost_shipping_cron_cache_deactivation' ) );

/**
 * Actions
 */
// Everywhere: Init when we use the shipping
add_action( 'plugins_loaded', array( $bpost_shipping_hooks, 'bpost_shipping_init' ) );

add_action( 'wp_enqueue_scripts', array( $bpost_shipping_hooks, 'enqueue_scripts_frontend' ), 1 );

add_action( 'admin_enqueue_scripts', array( $bpost_shipping_hooks, 'enqueue_scripts_admin' ) );

$isCustomFieldSpecialHook = false;
if ( get_option( 'ET_CORE_VERSION', 0 ) <> 0 ) {
	// Divi
	if ( $isCustomFieldSpecialHook ) {
		add_action( 'woocommerce_after_order_notes', [ $bpost_shipping_hooks, 'add_custom_checkout_fields' ] );
	} else {
		add_action( 'woocommerce_review_order_after_payment', [ $bpost_shipping_hooks, 'add_custom_checkout_fields' ] );
	}
} else {
	// Not Divi
	if ( $isCustomFieldSpecialHook ) {
		add_action( 'woocommerce_review_order_after_payment', [ $bpost_shipping_hooks, 'add_custom_checkout_fields' ] );
	} else {
		add_action( 'woocommerce_after_order_notes', [ $bpost_shipping_hooks, 'add_custom_checkout_fields' ] );
	}
}

add_action( 'woocommerce_checkout_process', [ $bpost_shipping_hooks, 'bpost_shipping_options_validation' ] );

// Checkout: After the closing of the SHM, save bpost data into the order
add_action( 'woocommerce_checkout_update_order_meta', array( $bpost_shipping_hooks, 'bpost_shipping_update_order_metas' ) );

// Checkout: After the closing of the SHM, save bpost data into the order
add_action( 'woocommerce_checkout_order_processed', array( $bpost_shipping_hooks, 'bpost_shipping_feed_info' ), 10, 2 );

// Order-received: Add a bpost block to show the shipping info
add_action(
	'woocommerce_order_details_after_order_table',
	array( $bpost_shipping_hooks, 'bpost_shipping_info_block' )
);

// Admin: We add a block in the order details page with the bpost shipping info
add_action(
	'woocommerce_admin_order_data_after_shipping_address',
	array( $bpost_shipping_hooks, 'bpost_shipping_admin_details' )
);

// Before checkout: api for param validation
add_action( 'woocommerce_api_shm-loader', array( $bpost_shipping_hooks, 'bpost_shipping_api_loader' ) );

// After shm popin: create virtual page for shm callback
add_action(
	'woocommerce_api_shm-callback',
	array( $bpost_shipping_hooks, 'bpost_shipping_virtual_page_shm_callback' )
);

add_action( 'woocommerce_api_bpost-label', array( $bpost_shipping_hooks, 'bpost_virtual_page_label' ) );

// Refresh bpost box status for the given order ID
add_action( 'woocommerce_api_bpost-refresh-status', array( $bpost_shipping_hooks, 'bpost_refresh_bpost_status' ) );

add_action( 'add_meta_boxes', array( $bpost_shipping_hooks, 'bpost_order_details_box_meta' ), 10, 2 );

//On fixed intervals (check cron_cache_(des)?activation)
add_action( 'cache_clean', array( $bpost_shipping_hooks, 'bpost_shipping_cron_cache_clean_run' ) );

/**
 * Filters
 */
// Admin: Add the plugin to the shipping methods list
add_filter( 'woocommerce_shipping_methods', array( $bpost_shipping_hooks, 'bpost_shipping_add_method' ) );

// Admin: Add a bulk action to print labels
add_filter( 'bulk_actions-edit-shop_order', array( $bpost_shipping_hooks, 'bpost_shipping_add_order_bulk_action' ) );
add_filter( 'handle_bulk_actions-edit-shop_order', array( $bpost_shipping_hooks, 'handle_bulk_actions' ), 10, 3 );

// Checkout: Put 'as from' at the estimated shipping cost
add_filter(
	'woocommerce_cart_shipping_method_full_label',
	array( $bpost_shipping_hooks, 'bpost_shipping_prefix_estimated_cost' ),
	10,
	2
);

add_filter(
	'woocommerce_admin_order_actions',
	array( $bpost_shipping_hooks, 'bpost_order_review_admin_actions' ),
	10,
	2 );

// Checkout: Add fields to include into checkout process
add_filter( 'woocommerce_checkout_fields', array( $bpost_shipping_hooks, 'bpost_shipping_filter_checkout_fields' ) );

add_filter(
	'woocommerce_order_shipping_method',
	array( $bpost_shipping_hooks, 'bpost_shipping_order_shipping_method' ),
	10,
	2
);

// Admin: Add link "Configure" on the plugins list
add_filter( 'plugin_action_links', array( $bpost_shipping_hooks, 'plugin_action_links' ), 10, 2 );

// Invalidate cache of packages to force ree-calculation of delivery prices after popup closing
add_filter( 'woocommerce_cart_shipping_packages', array(
	$bpost_shipping_hooks,
	'woocommerce_cart_shipping_packages',
), 100 );

/**
 * @param string $text
 *
 * @return string
 */
function bpost__( $text ) {
	return esc_html__( $text, BPOST_PLUGIN_ID );
}

add_action(
	'woocommerce_after_shipping_rate',
	array( $bpost_shipping_hooks, 'woocommerce_after_shipping_rate_add_shipping_options' ),
	10,
	2
);
