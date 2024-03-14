<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              silvasoft.nl
 * @since             1.0.0
 * @package           Silvasoft
 *
 * @wordpress-plugin
 * Plugin Name:       Silvasoft boekhouden
 * Plugin URI:        https://www.silvasoft.nl/artikel/woocommerce-boekhouding/
 * Description:       Koppel WooCommerce aan uw Silvasoft boekhouding en voorraadbeheer. Automatisch. Eenvoudig. Gratis.
 * Version:           2.7.3
 * Author:            silvasoft
 * Author URI:        https://www.silvasoft.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       silvasoft
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '2.7.3' );
define( 'SILVAPLUGINDIR', plugin_dir_path( __FILE__ ) );
define( 'SILVAPLUGINDIRURL', plugin_dir_url( __FILE__ ) );

global $apiconnector;

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-silvasoft-activator.php
 */
function activate_silvasoft() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-silvasoft-activator.php';
	Silvasoft_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-silvasoft-deactivator.php
 */
function deactivate_silvasoft() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-silvasoft-deactivator.php';
	Silvasoft_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_silvasoft' );
register_deactivation_hook( __FILE__, 'deactivate_silvasoft' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-silvasoft.php';


/* Custom cron interval */
function cron_add_silvaschedules( $schedules ) {
	// Adds once every minute to the existing schedules.
	$schedules['silvasoftwoosync'] = array(
		'interval' => 600, //600 seconds = 10 minutes
		'display' => __( 'Silvasoft Woo sync - 600s' )
	);
	
	
	return $schedules;
}
add_filter( 'cron_schedules', 'cron_add_silvaschedules' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_silvasoft() {

	$plugin = new Silvasoft();
	$plugin->run();

}
run_silvasoft();
