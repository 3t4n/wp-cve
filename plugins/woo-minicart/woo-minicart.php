<?php
/**
 * Plugin Name:       Minicart for WooCommerce
 * Plugin URI:        https://ahmadshyk.com/item/woocommerce-minicart-pro/
 * Description:       The simple plugin to add Minicart on your WooCommerce website.
 * Version:           2.0.5
 * Author:            Ahmad Shyk
 * Author URI:        https://ahmadshyk.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-minicart
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WOO_MINICART_VERSION', '2.0.5' );

/**
 * Activation Hook.
 */
register_activation_hook( __FILE__, 'wmc_activate' );
function wmc_activate(){
	$default = array(
		'enable-minicart'        => 1,
		'minicart-icon'          => 'wmc-icon-1',
		'minicart-position'      => 'wmc-top-right',
		'wmc-offset'             => 150, 
	);
	add_option( 'wmc_options', $default, '', 'yes' );
}

/**
 * Deactivation Hook.
 */
register_deactivation_hook( __FILE__, 'wmc_deactivate' );
function wmc_deactivate(){
}

/**
 * Admin notice if WooCommerce not installed and activated.
 */
function wmc_no_woocommerce(){ ?>
		<div class="error">
				<p><?php _e( 'Minicart for WooCommerce Plugin is activated but not effective. It requires WooCommerce in order to work.', 'woo-minicart' ); ?></p>
			</div>
<?php	
}

/**
 *  Main Class
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

require plugin_dir_path( __FILE__ ) . 'class-woo-minicart.php';

new WMC_Main_Class();

}

else{
	add_action( 'admin_notices', 'wmc_no_woocommerce' );
}

//Add settings link on plugin page
function wmc_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=woo-minicart">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wmc_settings_link' );

add_action( 'init', 'wmc_load_textdomain' );
  
//Load plugin textdomain.
function wmc_load_textdomain() {
  load_plugin_textdomain( 'wmc_textdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' ); 
}