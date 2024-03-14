<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbsync.com
 * @since             1.0.0
 * @package           Data_Sync_X_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Data Sync for Xero by Wbsync
 * Plugin URI:        https://wbsync.com/integrations/xero-quickbooks
 * Description:       Sync your WooCommerce orders and inventory to your Xero accounting system.
 * Version:           1.0.0
 * Author:            Wbsync
 * Author URI:        https://wbsync.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       data-sync-x-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DATA_SYNC_X_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-data-sync-x-woocommerce-activator.php
 */
function activate_data_sync_x_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-data-sync-x-woocommerce-activator.php';
	Data_Sync_X_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-data-sync-x-woocommerce-deactivator.php
 */
function deactivate_data_sync_x_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-data-sync-x-woocommerce-deactivator.php';
	Data_Sync_X_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_data_sync_x_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_data_sync_x_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-data-sync-x-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_data_sync_x_woocommerce() {

	$plugin = new Data_Sync_X_Woocommerce();
	$plugin->run();

}
run_data_sync_x_woocommerce();
