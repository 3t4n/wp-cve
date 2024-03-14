<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.ipushpull.com/wordpress
 * @since             2.4.0
 * @package           Ipushpull
 *
 * @wordpress-plugin
 * Plugin Name:       ipushpull
 * Plugin URI:        ipushpull
 * Description:       Display live, updating data from desktop Microsoft Excel in your website or blog. When your sheet updates, your website will update too - no need to save or upload files. Use it for financial information, sports results, price lists and more
 * Version:           2.4.0
 * Author:            ipushpull
 * Author URI:        https://www.ipushpull.com/wordpress
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ipushpull
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// if(strstr($_SERVER['HTTP_HOST'],'.local')) {
//     define('IPUSHPULL_URL','http://ipushpull.local');
// } else {
// }
define('IPUSHPULL_URL','https://www.ipushpull.com');
define('IPUSHPULL_API_URL','https://www.ipushpull.com/api/1.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ipushpull-activator.php
 */
function activate_ipushpull() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ipushpull-activator.php';
	Ipushpull_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ipushpull-deactivator.php
 */
function deactivate_ipushpull() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ipushpull-deactivator.php';
	Ipushpull_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ipushpull' );
register_deactivation_hook( __FILE__, 'deactivate_ipushpull' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ipushpull.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_ipushpull() {

	$plugin = new Ipushpull();
	$plugin->run();

}
run_ipushpull();
