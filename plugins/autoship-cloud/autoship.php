<?php
/*
Plugin Name: Autoship Cloud powered by QPilot
Plugin URI: https://autoship.cloud
Description: Autoship Cloud for WooCommerce
Version: 2.5.3
Author: Patterns In the Cloud LLC
Author URI: https://qpilot.cloud
Text Domain: autoship
Domain Path: /languages

WC requires at least: 3.4.1
WC tested up to: 8.6.1
*/

define( 'Autoship_Version', '2.5.3' );

if ( ! defined( 'Autoship_Plugin_Dir' ) ) {
	define( 'Autoship_Plugin_Dir', __DIR__ );
}
if ( ! defined( 'Autoship_Plugin_File' ) ) {
	define( 'Autoship_Plugin_File', __FILE__ );
}
if ( ! defined( 'Autoship_Options_Count' ) ) {
	define( 'Autoship_Options_Count', 5 );
}
if ( ! defined( 'Autoship_Plugin_Folder_Name' ) ) {
	define( 'Autoship_Plugin_Folder_Name', 'autoship-cloud' );
}
if ( ! defined( 'Autoship_Plugin_Url' ) ) {
	define( 'Autoship_Plugin_Url', plugin_dir_url( __FILE__ ) );
}

function autoship_activate() {

  // Set the flush rewrite rules if not set.
  if ( ! get_option( 'autoship_flush_rewrite_rules_flag' ) ) {
      add_option( 'autoship_flush_rewrite_rules_flag', true );
  }

}
register_activation_hook( __FILE__, 'autoship_activate' );

function autoship_deactivate() {
  // Flush the Rewrite rules on deactivate
  flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'autoship_deactivate' );

function autoship_uninstall() {
  // Flush the Rewrite rules on uninstall
  flush_rewrite_rules();
}
register_uninstall_hook( __FILE__, 'autoship_uninstall' );

/**
 * Show action links on the plugin screen.
 *
 * @param mixed $links Plugin Action links.
 *
 * @return array
 */
function autoship_plugin_action_links( $links ) {



	$action_links = array(
		'connection' => '<a href="' . admin_url( 'admin.php?page=autoship' ) . '" aria-label="' . esc_attr__( 'View Autoship Connection', 'autoship' ) . '">' . esc_html__( 'Connection', 'autoship' ) . '</a>',
		'options'  => '<a href="' . admin_url( 'admin.php?page=autoship&tab=autoship-options' ) . '" aria-label="' . esc_attr__( 'View Autoship Options', 'autoship' ) . '">' . esc_html__( 'Options', 'autoship' ) . '</a>',
		'utilities'  => '<a href="' . admin_url( 'admin.php?page=autoship&tab=autoship-utilities' ) . '" aria-label="' . esc_attr__( 'View Autoship Utilities', 'autoship' ) . '">' . esc_html__( 'Utilities', 'autoship' ) . '</a>',
		'support'  => '<a href="https://support.autoship.cloud/?utm_source=Autoship+Cloud+Plugin&utm_medium=WP-Admin+Plugins" aria-label="' . esc_attr__( 'View Autoship Support', 'autoship' ) . '" target="_blank">' . esc_html__( 'Support', 'autoship' ) . '</a>',
	);

	return array_merge( $action_links, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'autoship_plugin_action_links' );

/**
* Add Admin Notice on Failed Requirements
*/
function autoship_requirement_notice(){

  $message = __( 'Autoship Cloud Requires WooCommerce to be installed and active. Please activate WooCommerce in order to use Autoship Cloud.', 'autoship' );
  echo "<div class=\"asc-notice notice notice-error\"><p><strong>{$message}</strong></p></div>";

}

/**
* Add Admin Notice on Failed Payment Requirements
*/
function autoship_payment_requirement_notice(){

  $message = sprintf( __( '<h3>No Autoship Payment Gateways Enabled</h3><p><strong>Autoship Cloud requires one or more supported WooCommerce Payment Gateways to be installed and active in order for customers to be able to schedule products for autoship. Please see a list of Autoship Cloud supported payment gateways <a href="%s">here</a>.</strong></p>', 'autoship' ), 'https://support.autoship.cloud/article/1002-payment-integrations' );

  echo "<div class=\"asc-notice notice notice-error\">{$message}</div>";

}

/**
* Check minimum requirements to use Autoship.
*/
function autoship_check_min_requirements(){

  // Get the Active Plugins
  $active_plugins = (array) get_option( 'active_plugins', array() );

  if ( is_multisite() )
  $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

  // Check that WooCommerce is installed and active
  if ( ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) &&
         defined( 'WC_VERSION' ) && version_compare ( WC_VERSION , 3.2, '>=') )
  return true;

  add_action( 'admin_notices', 'autoship_requirement_notice' );

  return false;

}

/**
 * Loads the Core Language file
 */
function autoship_load_languages() {
	$plugin_rel_path = basename( Autoship_Plugin_Dir ) . '/languages';
	load_plugin_textdomain( 'autoship', false, $plugin_rel_path );
}


/**
 * Loads the Core Files
 */
function autoship_load_includes(){

  require_once( 'src/QPilot/Client.php' );
  require_once( 'src/logger.php' );
  require_once( 'src/upgrade.php' );
  require_once( 'src/admin.php' );
  require_once( 'src/utilities.php' );
  require_once( 'src/api.php' );
  require_once( 'src/api-wc.php' );
  require_once( 'src/api-health.php');
  require_once( 'src/orders.php' );
  require_once( 'src/checkout.php' );
  require_once( 'src/cart.php' );
  require_once( 'src/coupons.php' );
  require_once( 'src/products.php' );
  require_once( 'src/product-page.php' );
  require_once( 'src/payments.php' );
  require_once( 'src/customers.php' );
  require_once( 'src/scripts.php' );
  require_once( 'src/ajax.php' );
  require_once( 'src/shortcodes.php' );
  require_once( 'src/pages.php' );
  require_once( 'src/languages.php' );
  require_once( 'src/export.php' );
  require_once( 'src/import.php' );
  require_once( 'src/bulk.php' );
  require_once( 'src/scheduled-orders.php' );
  require_once( 'src/shipping.php' );
  require_once( 'src/free-shipping.php' );
  require_once( 'src/deprecated.php' );
  require_once( 'src/upzelo.php' );

}

/**
 * Declare compatibility with WooCommerce HPOS
 * https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
 */
function autoship_woocommerce_hpos_compatibility() {
  if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}
add_action( 'before_woocommerce_init', 'autoship_woocommerce_hpos_compatibility' );

/**
 * Start it up
 */
function autoship_init() {

  // While we allow Autoship to be active when requirements fail, no functionality is included.
  if ( autoship_check_min_requirements() ){

		autoship_load_includes();
		autoship_load_languages();

	}

}
add_action( 'plugins_loaded', 'autoship_init' );
add_action( 'woocommerce_init', 'autoship_confirm_valid_payment_gateways', 99 );
