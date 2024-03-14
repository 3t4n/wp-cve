<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              thetechtribe.com
 * @since             0.1.0
 * @package           The_Tech_Tribe
 *
 * @wordpress-plugin
 * Plugin Name:       The Tribal Plugin
 * Plugin URI:        thetechtribe.com
 * Description:       This plugin is for members of The Tech Tribe to manage features such as Automated Blog Posting etc.
 * Version:           1.3.1
 * Author:            The Tech Tribe
 * Author URI:        https://thetechtribe.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       the-tech-tribe
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
define( 'THE_TRIBAL_PLUGIN_VERSION', '1.3.1' );

//date_default_timezone_set(wp_timezone_string());
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-the-tribal-plugin-activator.php
 */
function activate_the_tribal_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-tribal-plugin-activator.php';
	The_Tribal_Plugin_Activator::activate();
	
	tttInitCronJob();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-the-tribal-plugin-deactivator.php
 */
function deactivate_the_tribal_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-tribal-plugin-deactivator.php';
	The_Tribal_Plugin_Deactivator::deactivate();

	tttRemoveCronJob();
	tttCustomLogsDelete();
}

register_activation_hook( __FILE__, 'activate_the_tribal_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_the_tribal_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-the-tribal-plugin.php';

require plugin_dir_path( __FILE__ ) . 'helpers/utilities.php';

require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';

function tttc_get_plugin_details(){
	// Check if get_plugins() function exists. This is required on the front end of the
	// site, since it is in a file that is normally only loaded in the admin.
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$ret = get_plugin_data( __FILE__ );
	return $ret;
}

function tttc_get_text_domain(){
	$ret = tttc_get_plugin_details();
	return $ret['TextDomain'];
}

function tttc_get_plugin_dir(){
	return plugin_dir_path( __FILE__ );
}

/**
* get the plugin url path.
**/
function tttc_get_plugin_dir_url() {
	return plugin_dir_url( __FILE__ );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_the_tribal_plugin() {

	$plugin = new The_Tribal_Plugin();
	$plugin->run();
	
	\TheTribalPlugin\WPMenu::get_instance()->init();
	\TheTribalPlugin\AjaxImportPost::get_instance()->init();

}
add_action('init', 'run_the_tribal_plugin');

function ttt_init_client()
{
	if(!is_admin()){}
}
add_action('init', 'ttt_init_client');