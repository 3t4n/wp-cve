<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cyberfoxdigital.co.uk
 * @since             1.0.1
 * @package           Cf_Christmasification
 *
 * @wordpress-plugin
 * Plugin Name:       Christmasify! By Cyber Fox
 * Plugin URI:        https://cyberfoxdigital.co.uk
 * Description:       This plugin gives your website the Christmas feels!
 * Version:           1.5.5
 * Author:            Cyber Fox
 * Author URI:        https://cyberfoxdigital.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       christmasify
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cf-christmasification-activator.php
 */
function activate_cf_christmasification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf-christmasification-activator.php';
	Cf_Christmasification_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cf-christmasification-deactivator.php
 */
function deactivate_cf_christmasification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf-christmasification-deactivator.php';
	Cf_Christmasification_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cf_christmasification' );
register_deactivation_hook( __FILE__, 'deactivate_cf_christmasification' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cf-christmasification.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cf_christmasification() {

	$plugin = new Cf_Christmasification();
	$plugin->run();

}
run_cf_christmasification();
