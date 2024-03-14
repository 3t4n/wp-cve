<?php

/**
 *
 * @wordpress-plugin
 * Plugin Name:       Remove product images background for WooCommerce
 * Description:       Remove/change background of WooCommerce product images.
 * Plugin URI:		  http://fresh-d.biz/wocommerce-remove-background.html
 * Version:           1.2
 * Author:            Fresh-d
 * Author URI:        https://fresh-d.biz/about-us.html
 * Requires at least: 4.1
 * Tested up to:      5.3.2
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-remove-bg
 * Domain Path:       /languages

 * WC requires at least: 3.1
 * WC tested up to: 3.9
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'Remove_BG_VERSION', '1.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-remove-bg-activator.php
 */
function activate_remove_bg() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-remove-bg-activator.php';
	$activate = Remove_BG_Activator::activate();
	if($activate === false) {
		wp_die( __('Failed to create needed tables', 'wc-remove-bg').'<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; '.__('Return to Plugins', 'wc-remove-bg').'</a>' );
	}
}

register_activation_hook( __FILE__, 'activate_remove_bg' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-remove-bg.php';

// Add settings link on plugin page
function wc_remove_bg_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=wc_remove_bg">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wc_remove_bg_settings_link' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_remove_bg() {

	$plugin = new Remove_BG();
	$plugin->run();

}
run_remove_bg();
