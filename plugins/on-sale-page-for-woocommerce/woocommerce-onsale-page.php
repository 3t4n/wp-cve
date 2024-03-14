<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wpgenie.org
 * @since             1.0.0
 * @package           Woocommerce_onsale_page
 *
 * @wordpress-plugin
 * Plugin Name:       OnSale Page for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/last-users-order-column-for-woocommerce/
 * Description:       OnSale Page for WooCommerce is an extension for WooCommerce. We developed this plugin because WooCommerce has onsale widget and shortcode but it lacks paging, sorting and filtering which you can usually find on regular WooCommerce catalog page.
 * Version:           1.1.3
 * Author:            wpgenie
 * Author URI:        http://wpgenie.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc_onsale_page
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 9.0
 */

// If this file is called directly, abort.

function is_plugin_active_for_network_wpg( $plugin ) {
	if ( ! is_multisite() ) {
		return false;
	}

	$plugins = get_site_option( 'active_sitewide_plugins' );
	if ( isset( $plugins[ $plugin ] ) ) {
		return true;
	}

	return false;
}


if ( ! defined( 'ABSPATH' ) ) {
	die;
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) or

is_plugin_active_for_network_wpg('woocommerce/woocommerce.php')

 ) {

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-onsale-page.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_wc_onsale_page() {

		$plugin = new Woocommerce_onsale_page();
		$plugin->run();


	}

	add_action( 'woocommerce_init' ,'run_wc_onsale_page');
	
} else {
	
	add_action('admin_notices', 'wc_onsale_page');
	function wc_onsale_page(){
		global $current_screen;
		if($current_screen->parent_base == 'plugins'){
			echo '<div class="error"><p>Woocommerce Onsale Pege '.__('requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="'.admin_url('plugin-install.php?tab=search&type=term&s=WooCommerce').'" target="_blank">WooCommerce</a> first.', 'wc_onsale_page').'</p></div>';
		}
	}
	$plugin = plugin_basename(__FILE__);

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if(is_plugin_active($plugin)){
	 	deactivate_plugins( $plugin);
	}
	 if ( isset( $_GET['activate'] ) )
    		unset( $_GET['activate'] );
}
