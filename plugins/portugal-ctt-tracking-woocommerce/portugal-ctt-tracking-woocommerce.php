<?php
/*
 * Plugin Name: Portugal CTT Tracking for WooCommerce
 * Plugin URI: https://www.webdados.pt/wordpress/plugins/tracking-ctt-portugal-para-woocommerce-wordpress/
 * Description: Lets you associate a tracking code with a WooCommerce order so that both the store owner and the client can track the order sent with CTT
 * Version: 2.2
 * Author: PT Woo Plugins (by Webdados)
 * Author URI: https://ptwooplugins.com
 * Text Domain: portugal-ctt-tracking-woocommerce
 * Domain Path: /languages/
 * Requires at least: 5.6
 * Tested up to: 6.5
 * Requires PHP: 7.0
 * WC requires at least: 6.0
 * WC tested up to: 8.5
*/

/* WooCommerce CRUD ready */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'CTT_TRACKING_WP_VERSION', '5.6' );

/* Localization */
add_action( 'plugins_loaded', 'ctt_tracking_load_textdomain', 0 );
function ctt_tracking_load_textdomain() {
	load_plugin_textdomain( 'portugal-ctt-tracking-woocommerce' );
}

/* Check if WooCommerce is active - Get active network plugins - "Stolen" from Novalnet Payment Gateway */
function ctt_tracking_active_nw_plugins() {
	if ( !is_multisite() )
		return false;
	$ctt_tracking_activePlugins = ( get_site_option('active_sitewide_plugins' ) ) ? array_keys( get_site_option('active_sitewide_plugins' ) ) : array();
	return $ctt_tracking_activePlugins;
}
if ( in_array( 'woocommerce/woocommerce.php', (array) get_option( 'active_plugins' ) ) || in_array( 'woocommerce/woocommerce.php', (array) ctt_tracking_active_nw_plugins() ) ) {


	/* Our own order class and the main classes */
	add_action( 'plugins_loaded', 'ctt_tracking_init', 1 );
	function ctt_tracking_init() {
		if ( class_exists( 'WooCommerce' ) && version_compare( WC_VERSION, CTT_TRACKING_WP_VERSION, '>=' ) ) { //We check again because WooCommerce could have "died"
			require_once( dirname( __FILE__ ) . '/includes/class-ctt-tracking.php' );
			$GLOBALS['CTT_Tracking'] = CTT_Tracking();
			/* Add settings links - This is here because inside the main class we cannot call the correct plugin_basename( __FILE__ ) */
			add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ), array( CTT_Tracking(), 'add_settings_link' ) );
		} else {
			add_action( 'admin_notices', 'admin_notices_ctt_tracking_not_active' );
		}
	}

	/* Main class */
	function CTT_Tracking() {
		return CTT_Tracking::instance(); 
	}

	
} else {


	add_action( 'admin_notices', 'admin_notices_ctt_tracking_not_active' );


}


function admin_notices_ctt_tracking_not_active() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php
			printf(
				__( '<strong>Portugal CTT Tracking for WooCommerce</strong> is installed and active but <strong>WooCommerce (%s or above)</strong> is not.', 'portugal-ctt-tracking-woocommerce' ),
				CTT_TRACKING_WP_VERSION
			)
		?></p>
	</div>
	<?php
}

/* HPOS & Blocks Compatible */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );


/* Portuguese Postcodes nag */
add_action( 'admin_init', function() {
	if (
		( ! defined( 'WEBDADOS_PORTUGUESE_POSTCODES_NAG' ) )
		&&
		( ! function_exists( '\Webdados\PortuguesePostcodesWooCommerce\init' ) )
		&&
		empty( get_transient( 'webdados_portuguese_postcodes_nag' ) )
	) {
		define( 'WEBDADOS_PORTUGUESE_POSTCODES_NAG', true );
		require_once( 'webdados_portuguese_postcodes_nag/webdados_portuguese_postcodes_nag.php' );
	}
} );


/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */

