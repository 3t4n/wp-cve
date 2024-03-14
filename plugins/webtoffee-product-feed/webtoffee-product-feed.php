<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.webtoffee.com
 * @since             1.0.0
 * @package           Webtoffee_Product_Feed_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       WebToffee WooCommerce Product Feed & Sync Manager
 * Plugin URI:        https://wordpress.org/plugins/webtoffee-product-feed
 * Description:       Integrate your WooCommerce store with popular sale channels including Google Merchant Center, Facebook/Instagram ads&shops, TikTok ads and much more.
 * Version:           2.1.6
 * Author:            WebToffee
 * Author URI:        https://www.webtoffee.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       webtoffee-product-feed
 * Domain Path:       /languages
 * WC tested up to:   8.5.2
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION', '2.1.6' );
define( 'WEBTOFFEE_PRODUCT_FEED_ID', 'webtoffee_product_feed' );
define( 'WT_PRODUCT_FEED_PLUGIN_URL', plugin_dir_url(__FILE__));
define( 'WT_PRODUCT_FEED_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WT_PRODUCT_FEED_PLUGIN_FILENAME', __FILE__);
if (!defined('WT_PRODUCT_FEED_BASE_NAME')) {
    define('WT_PRODUCT_FEED_BASE_NAME', plugin_basename(__FILE__));
}

if ( !defined( 'WEBTOFFEE_PRODUCT_FEED_MAIN_ID' ) ) {
	define( 'WEBTOFFEE_PRODUCT_FEED_MAIN_ID', 'webtoffee_product_feed_main' );
}

if ( !defined( 'WT_PF_DEBUG_BASIC' ) ) {
	define( 'WT_PF_DEBUG_BASIC', false );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-webtoffee-product-feed-sync-activator.php
 */
function activate_webtoffee_product_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webtoffee-product-feed-sync-activator.php';
	Webtoffee_Product_Feed_Sync_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-webtoffee-product-feed-sync-deactivator.php
 */
function deactivate_webtoffee_product_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webtoffee-product-feed-sync-deactivator.php';
	Webtoffee_Product_Feed_Sync_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_webtoffee_product_feed' );
register_deactivation_hook( __FILE__, 'deactivate_webtoffee_product_feed' );


/* Checking WC is actived or not */
if ( !function_exists( 'is_plugin_active' ) ) {
	include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

add_action( 'plugins_loaded', 'wt_feed_basic_check_for_woocommerce' );

if ( !function_exists( 'wt_feed_basic_check_for_woocommerce' ) ) {

	function wt_feed_basic_check_for_woocommerce() {


		if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) || !defined( 'WC_VERSION' ) ) {
			add_action( 'admin_notices', 'wt_wc_missing_warning_for_feed_basic' );
		}
		if ( !function_exists( 'wt_wc_missing_warning_for_feed_basic' ) ) {

			function wt_wc_missing_warning_for_feed_basic() {

				$install_url = wp_nonce_url( add_query_arg( array( 'action' => 'install-plugin', 'plugin' => 'woocommerce', ), admin_url( 'update.php' ) ), 'install-plugin_woocommerce' );
				$class		 = 'notice notice-error';
				$post_type	 = 'product';
				$message	 = sprintf( __( 'The <b>WooCommerce</b> plugin must be active for <b> WebToffee WooCommerce %s Feed & Sync Manager</b> plugin to work.  Please <a href="%s" target="_blank">install & activate WooCommerce</a>.' ), ucfirst( $post_type ), esc_url( $install_url ) );
				printf( '<div class="%s"><p>%s</p></div>', esc_attr( $class ), ( $message ) );
			}

		}
	}
}


/** this added for a temporary when a plugin update with the option upload zip file. need to remove this after some version release */
 if ( !get_option( 'wt_pf_is_active' ) ) {
	update_option( 'wt_productfeed_show_legacy_menu', 1 );
 	activate_webtoffee_product_feed();
 }

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-webtoffee-product-feed-sync.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wt-productfeed-uninstall-feedback.php';

// WooCommerce HPOS compatibility decleration
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_webtoffee_product_feed() {

	$plugin = new Webtoffee_Product_Feed_Sync();
	$plugin->run();
}

run_webtoffee_product_feed();
