<?php

/** 
 *
 * @link              https://myworks.software/
 * @since             1.0.0
 * @package           MyWorks_WC_Xero_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Sync for Xero - by MyWorks
 * Plugin URI:        https://myworks.software/integrations/woocommerce-xero-sync/
 * Description:       Automatically sync your WooCommerce store with Xero - in real-time! Easily sync customers, orders, payments, products, inventory and more between your WooCommerce store and Xero. Your complete solution to streamline your accounting workflow.
 * Version:           1.0.5
 * Author:            MyWorks Software
 * Author URI:        https://myworks.software/
 * Developer: 		  MyWorks Software
 * Developer URI:     https://myworks.software/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       myworks-sync-for-xero
 * Domain Path:       /languages
 * Requires at least: 5.2
 * Requires PHP: 5.6
 *
 * WC requires at least: 4.0.0
 * WC tested up to: 8.5.2
 *
 * Copyright: © 2011-2024 MyWorks Software.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

#Config
require plugin_dir_path( __FILE__ ) . 'p-config-s.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
#define( 'MW_WC_XERO_SYNC_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-myworks-woo-sync-for-xero-activator.php
 */
function activate_myworks_woo_sync_for_xero() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-myworks-woo-sync-for-xero-activator.php';
	MyWorks_WC_Xero_Sync_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-myworks-woo-sync-for-xero-deactivator.php
 */
function deactivate_myworks_woo_sync_for_xero() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-myworks-woo-sync-for-xero-deactivator.php';
	MyWorks_WC_Xero_Sync_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_myworks_woo_sync_for_xero' );
register_deactivation_hook( __FILE__, 'deactivate_myworks_woo_sync_for_xero' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-myworks-woo-sync-for-xero.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_myworks_woo_sync_for_xero() {

	$myworks_wc_xero_sync = new MyWorks_WC_Xero_Sync();
	$myworks_wc_xero_sync->run();

}
run_myworks_woo_sync_for_xero();