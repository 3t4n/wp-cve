<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.smartbill.ro
 * @since             1.0.0
 * @copyright         Intelligent IT SRL 2018
 * @package           smartbill-facturare-si-gestiune
 *
 * @wordpress-plugin
 * Plugin Name:       SmartBill Facturare si Gestiune
 * Plugin URI:        https://www.smartbill.ro/resurse/integrari
 * Description:       Acest modul permite emiterea facturilor in SmartBill Cloud pentru WooCommerce 3.0 - 8.7.0, WordPress 4.7.0 - 6.4.2
 * Version:           3.2.3
 * Author:            smarbill.ro
 * Author URI:        https://www.smartbill.ro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       smartbill-woocommerce
 * Domain Path:       /languages
 * Copyright:         Intelligent IT SRL 2018
 * Requires at least: 4.7.0
 * Tested up to:      6.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SMARTBILL_PLUGIN_VERSION', '3.2.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smartbill-woocommerce-activator.php
 */
function activate_smartbill_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smartbill-woocommerce-activator.php';
	Smartbill_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smartbill-woocommerce-deactivator.php
 */
function deactivate_smartbill_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smartbill-woocommerce-deactivator.php';
	Smartbill_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_smartbill_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_smartbill_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-smartbill-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_smartbill_woocommerce() {

	$plugin = new Smartbill_Woocommerce();
	$plugin->run();

}
run_smartbill_woocommerce();

/**
 * Enable woocommerce tax rounding. 
 */
function enable_woocommerce_settings() {
	update_option( 'woocommerce_tax_round_at_subtotal', 'yes' );
}
add_action( 'admin_init', 'enable_woocommerce_settings' );

/**
 * Show error message */
function show_smartbill_version_err() {
	/* translators: Variable is WordPress min version and Woocommerce min version*/
	$part_1 = sprintf( __( 'Modulul SmartBill functioneaza cu WordPress (minim v. %1s) si WooCommerce (minim v. %2s). ', 'smartbill-woocommerce' ), '4.7.0' ,'3.0.0');
	$part_2 =  __( 'Verifica versiunile WordPress si WooCommerce instalate si asigura-te ca modulul WooCommerce este activat.', 'smartbill-woocommerce' );

	wp_die(  "<strong>".esc_attr($part_1) . '</strong><br/><br/>' . esc_attr($part_2)  . '<br/><br/><br/> <a href="plugins.php" >' .  esc_attr__( 'Inapoi', 'smartbill-woocommerce' ) . '</a>' );
}

/**
 * Check WordPress and WooCommerce versions
 *
 * @return boolean
 */
function check_smartbill_compatibility() {
	$min_wp_ver  = '4.7.0';
	$min_woo_ver = '3.0';

	// check wordpres version to be higher than minimum allowed version.
	if ( version_compare( $GLOBALS['wp_version'], $min_wp_ver, '<' )) {
		return false;
	}
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		// check woocommerce version to be higher than minimum allowed version.
		if ( version_compare( $woocommerce->version, $min_woo_ver, '<=' ) ) {
			return false;
		}
	} else {
		return false;
	}

	// Add sanity checks for other version requirements here.
	return true;
}


