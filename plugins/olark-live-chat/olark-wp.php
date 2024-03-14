<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.olark.com?rid=integration_plugin_wordpress
 * @since             1.0.0
 * @package           Olark_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Olark Live Chat
 * Plugin URI:        https://github.com/olark/wordpress-plugin/edit/master/olark-wp/
 * Description:       This plugin is designed to allow you to add an Olark chatbox to your WordPress site easily! Simply activate and add your Site ID from Olark in the settings!
 * Version:           1.0.9
 * Author:            Olark
 * Author URI:        https://www.olark.com?rid=integration_plugin_wordpress
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       olark-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-olark-wp-activator.php
 */
function activate_olark_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-olark-wp-activator.php';
	Olark_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-olark-wp-deactivator.php
 */
function deactivate_olark_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-olark-wp-deactivator.php';
	Olark_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_olark_wp' );
register_deactivation_hook( __FILE__, 'deactivate_olark_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-olark-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_olark_wp() {

	$plugin = new Olark_Wp();
	$plugin->run();

}
run_olark_wp();
