<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.werkaandemuur.nl/
 * @since             1.0.0
 * @package           Wadm
 *
 * @wordpress-plugin
 * Plugin Name:       Werk aan de Muur
 * Plugin URI:        https://nl.wordpress.org/plugins/werk-aan-de-muur/
 * Description:       Use this plugin to display artworks on your own Wordpress site.
 * Version:           1.4
 * Author:            Sander van Leeuwen
 * Author URI:        https://www.werkaandemuur.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wadm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wadm-activator.php
 */
function activate_wadm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wadm-activator.php';
	Wadm_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wadm-deactivator.php
 */
function deactivate_wadm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wadm-deactivator.php';
	Wadm_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wadm' );
register_deactivation_hook( __FILE__, 'deactivate_wadm' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wadm.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wadm() {

	$plugin = new Wadm();
	$plugin->run();

}
run_wadm();

/**
 * Add settings link to plugin page
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wadm_plugin_settings_link');

function wadm_plugin_settings_link($links)
{
	$settings_link = '<a href="options-general.php?page=wadm">' . __('Settings', Wadm::TEXT_DOMAIN) . '</a>';
	array_unshift($links, $settings_link);

	return $links;
}