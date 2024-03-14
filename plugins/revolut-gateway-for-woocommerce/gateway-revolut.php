<?php
/**
 * Plugin Name: Revolut Gateway for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/revolut-gateway-for-woocommerce/
 * Description: Accept card payments easily and securely via Revolut.
 * Author: Revolut
 * Author URI: https://www.revolut.com/business/online-payments
 * Text Domain: revolut-gateway-for-woocommerce
 * Version: 4.10.1
 * Requires at least: 4.4
 * Tested up to: 6.1
 * WC tested up to: 6.5
 * WC requires at least: 2.6
 */

defined( 'ABSPATH' ) || exit;
define( 'REVOLUT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_GATEWAY_REVOLUT_VERSION', '4.10.1' );
define( 'WC_GATEWAY_PUBLIC_KEY_ENDPOINT', '/public-key/latest' );
define( 'WC_GATEWAY_REVPAY_INDEX', 'USE_REVOLUT_PAY_2_0' );
define( 'WC_REVOLUT_WAIT_FOR_ORDER_TIME', 2 );
define( 'WC_REVOLUT_FETCH_API_ORDER_ATTEMPTS', 10 );
define( 'WC_REVOLUT_AUTO_CANCEL_TIMEOUT', 'PT2M' );
define( 'WC_REVOLUT_GATEWAYS', array( 'revolut', 'revolut_cc', 'revolut_pay', 'revolut_payment_request' ) );


/**
 * Manage all dependencies
 */
require_once REVOLUT_PATH . 'includes/class-wc-revolut-manager.php';

/**
 * Init revolut
 */
function woocommerce_revolut_init() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	// be sure that plugin activation hook gets triggered.
	if ( WC_GATEWAY_REVOLUT_VERSION !== get_option( 'WC_GATEWAY_REVOLUT_VERSION' ) ) {
		woocommerce_revolut_install( is_network_admin() );
	}

	define( 'WC_REVOLUT_MAIN_FILE', __FILE__ );
	define( 'WC_REVOLUT_CARD_WIDGET_BG_COLOR', '#ffffff' );
	define( 'WC_REVOLUT_CARD_WIDGET_TEXT_COLOR', '#848484' );
	define( 'WC_REVOLUT_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
	add_action( 'admin_enqueue_scripts', 'woocommerce_revolut_load_admin_scripts' );
	load_plugin_textdomain( 'revolut-gateway-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	add_filter( 'woocommerce_payment_gateways', 'woocommerce_revolut_add_gateways' );
	add_action( 'init', 'woocommerce_revolut_load_rest_api' );
	add_action( 'wp_loaded', 'rest_api_includes' );
	add_action( 'before_woocommerce_init', 'declare_features_compatibility' );
}

/**
 * Include required env methods for webhook callbacks
 */
function rest_api_includes() {
	require_once WC_ABSPATH . 'includes/wc-cart-functions.php';
	require_once WC_ABSPATH . 'includes/wc-notice-functions.php';
}

/**
 * Declare compatibility with plugins and features.
 */
function declare_features_compatibility() {
	if ( class_exists( Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}
/**
 * Load API function
 */
function woocommerce_revolut_load_rest_api() {
	add_action( 'rest_api_init', 'woocommerce_revolut_create_callback_api', 99 );
}

/**
 * Create API to accept setup Webhook
 */
function woocommerce_revolut_create_callback_api() {
	$api = new Revolut_Webhook_Controller();
	$api->register_routes();
}

add_action( 'plugins_loaded', 'woocommerce_revolut_init', 0 );

/**
 * Set up Revolut plugin links
 *
 * @param array $links Revolut plugin link.
 *
 * @return array
 */
function woocommerce_revolut_plugin_links( $links ) {
	$settings_url = add_query_arg(
		array(
			'page'    => 'wc-settings',
			'tab'     => 'checkout',
			'section' => 'revolut',
		),
		admin_url( 'admin.php' )
	);

	$plugin_links = array(
		'<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings', 'revolut-gateway-for-woocommerce' ) . '</a>',
		'<a href="https://business.revolut.com/help-centre">' . __( 'Support', 'revolut-gateway-for-woocommerce' ) . '</a>',
		'<a href="https://developer.revolut.com/docs/accept-payments/plugins/woocommerce/configuration">' . __( 'Docs', 'revolut-gateway-for-woocommerce' ) . '</a>',
	);

	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woocommerce_revolut_plugin_links' );

/**
 * Add Revolut payment gateways
 *
 * @param String $gateways Revolut Payment Gateways.
 *
 * @return mixed
 */
function woocommerce_revolut_add_gateways( $gateways ) {
	return array_merge( $gateways, woocommerce_revolut_payment_gateways() );
}

/**
 * Get Revolut payment gateway list.
 */
function woocommerce_revolut_payment_gateways() {
	return array(
		'WC_Gateway_Revolut_CC',
		'WC_Gateway_Revolut_Pay',
		'WC_Gateway_Revolut_Payment_Request',
	);
}

/**
 * Create table to save Revolut Order
 */
register_activation_hook( __FILE__, 'woocommerce_revolut_install' );

/**
 * Install plugin tables.
 *
 * @param bool $network_wide  if the plugin is being network-activated.
 */
function woocommerce_revolut_install( $network_wide ) {
	global $wpdb;

	// Check if the plugin is being network-activated or not.
	if ( $network_wide ) {
		// Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
		if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
			$site_ids = get_sites(
				array(
					'fields'     => 'ids',
					'network_id' => get_current_network_id(),
				)
			);
		} else {
			$site_ids = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = %s;", array( $wpdb->siteid ) ) ); // db call ok; no-cache ok.
		}

		// Install the plugin for all these sites.
		foreach ( $site_ids as $site_id ) {
			switch_to_blog( $site_id );
			woocommerce_revolut_install_single_site();
			restore_current_blog();
		}
	} else {
		woocommerce_revolut_install_single_site();
	}
}

/**
 * Install plugin tables for single site.
 */
function woocommerce_revolut_install_single_site() {
	global $wpdb;
	$charset_collate     = $wpdb->get_charset_collate();
	$orders_table_name   = $wpdb->prefix . 'wc_revolut_orders';
	$temp_session_name   = $wpdb->prefix . 'wc_revolut_temp_session';
	$customer_table_name = $wpdb->prefix . 'wc_revolut_customer';

	try {
		$orders_table_sql = "CREATE TABLE IF NOT EXISTS $orders_table_name (
		order_id BINARY(16) NOT NULL,
		public_id BINARY(16) NOT NULL UNIQUE,
		wc_order_id INTEGER NULL UNIQUE,
    	PRIMARY KEY  (order_id)
	    ) $charset_collate;";

		$temp_session_table_sql = "CREATE TABLE IF NOT EXISTS $temp_session_name (
		order_id VARCHAR (150) NOT NULL UNIQUE,
		temp_session TEXT,
    	INDEX (order_id)
	    ) $charset_collate;";

		$customers_table_sql = "CREATE TABLE IF NOT EXISTS $customer_table_name (
		wc_customer_id INTEGER NOT NULL UNIQUE,
		revolut_customer_id VARCHAR (50) NOT NULL UNIQUE,
    	PRIMARY KEY  (wc_customer_id)
	    ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $orders_table_sql );
		dbDelta( $temp_session_table_sql );
		dbDelta( $customers_table_sql );

		// update plugin version on DB.
		update_option( 'WC_GATEWAY_REVOLUT_VERSION', WC_GATEWAY_REVOLUT_VERSION );
	} catch ( Exception $exception ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			error_log( $exception->getMessage() );
			// phpcs:enable
		}
	}
}

/**
 * Add script to setup Webhook using ajax
 */
function woocommerce_revolut_load_admin_scripts() {
	wp_register_script( 'revolut-settings', plugins_url( 'assets/js/revolut-setting.js', WC_REVOLUT_MAIN_FILE ), array(), WC_GATEWAY_REVOLUT_VERSION, false );
	wp_localize_script(
		'revolut-settings',
		'default_options',
		array(
			'default_bg_color'   => WC_REVOLUT_CARD_WIDGET_BG_COLOR,
			'default_text_color' => WC_REVOLUT_CARD_WIDGET_TEXT_COLOR,
			'nonce'              => array(
				'wc_revolut_clear_records'           => wp_create_nonce( 'wc-revolut-clear-records' ),
				'wc_revolut_onboard_applepay_domain' => wp_create_nonce( 'wc-revolut-onboard-applepay-domain' ),
			),
		)
	);

	wp_enqueue_script( 'revolut-settings' );
}
