<?php 
/**
 * Plugin Name: Shiptimize for WooCommerce
 * Description: Shiptimize for WooCommerce  
 * Version: 3.1.82
 * Author: Shiptimize
 * Author URI: https://shiptimize.me
 * Text Domain: shiptimize-for-woocommerce
 * Domain Path: /languages/
 * @package shiptimize
 *
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 3.0.0
 * WC tested up to: 6.4.1
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * There's nothing for us to do in cron tasks 
 */
if ( wp_doing_cron() )
{
 return; 
} 
 
define( 'SHIPTIMIZE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHIPTIMIZE_PLUGIN_FILE',  __FILE__ );
define( 'SHIPTIMIZE_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'SHIPTIMIZE_LOGO', SHIPTIMIZE_PLUGIN_URL . 'assets/images/logo.svg' );

require_once(SHIPTIMIZE_PLUGIN_PATH . '/constants.php'); 
require_once(SHIPTIMIZE_PLUGIN_PATH . 'includes/class-woo-shiptimize.php');

add_action(
    'before_woocommerce_init',
    function() {
        // Check if the FeaturesUtil class exists in the \Automattic\WooCommerce\Utilities namespace.
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            // Declare compatibility with custom order tables using the FeaturesUtil class.
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }
);

/** 
 * Marketplace have special needs, vendors have their own keys and the owner of the site might not 
 * have a contract with shiptimize 
 * @return true if a marketplace we integrate with is installed 
 */ 
function shiptimize_is_marketplace() {
  global $shiptimize_wcfm, $shiptimize_dokan;

  return isset($shiptimize_wcfm) || isset($shiptimize_dokan); 
}

$shiptimize = WooShiptimize::instance(); 
$shiptimize->bootstrap(); 

