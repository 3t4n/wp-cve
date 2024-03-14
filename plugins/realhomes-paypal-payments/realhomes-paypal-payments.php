<?php
/**
 * Plugin Name:       RealHomes PayPal Payments
 * Plugin URI:        https://themeforest.net/item/real-homes-wordpress-real-estate-theme/5373914
 * Description:       Provides PayPal functionality for individual property payments.
 * Version:           2.0.1
 * Tested up to:      6.4.1
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            InspiryThemes
 * Author URI:        https://inspirythemes.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       realhomes-paypal-payments
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Currently, plugin version.
define( 'REALHOMES_PAYPAL_PAYMENTS_VERSION', get_plugin_data( __FILE__ )['Version'] );

// Plugin unique identifier
define( 'REALHOMES_PAYPAL_PAYMENTS_NAME', 'realhomes-paypal-payments' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-realhomes-paypal-payments.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function realhomes_paypal_payments_run() {

	$plugin = new Realhomes_Paypal_Payments();
	$plugin->run();

}

realhomes_paypal_payments_run();
