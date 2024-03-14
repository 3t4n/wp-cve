<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              codeless.co
 * @since             1.0.0
 * @package           Slider_Templates
 *
 * @wordpress-plugin
 * Plugin Name:       Slider Templates
 * Plugin URI:        wordpress.org/plugins/slider-templates
 * Description:       Easily Import slider-templates.com templates to WP
 * Version:           1.0.3
 * Author:            Codeless
 * Author URI:        codeless.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       slider-templates
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
define( 'SLIDER_TEMPLATES_VERSION', '1.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-slider-templates-activator.php
 */
function activate_slider_templates() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-slider-templates-activator.php';
	Slider_Templates_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-slider-templates-deactivator.php
 */
function deactivate_slider_templates() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-slider-templates-deactivator.php';
	Slider_Templates_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_slider_templates' );
register_deactivation_hook( __FILE__, 'deactivate_slider_templates' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-slider-templates.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_slider_templates() {

	$plugin = new Slider_Templates();
	$plugin->run();

}
run_slider_templates();
