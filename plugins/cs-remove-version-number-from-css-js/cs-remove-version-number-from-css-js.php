<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://chetansatasiya.com/blog
 * @since             1.0.0
 * @package           Cs_Remove_Version_Number_From_Css_Js
 *
 * @wordpress-plugin
 * Plugin Name:       CS Remove Version Number From CSS & JS
 * Plugin URI:        http://chetansatasiya.com
 * Description:       This plugin will remove the version number from CSS and JS files.
 * Version:           1.0.1
 * Author:            Chetan Satasiya
 * Author URI:        http://chetansatasiya.com/blog
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cs-remove-version-number-from-css-js
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cs-remove-version-number-from-css-js-activator.php
 */
function activate_cs_remove_version_number_from_css_js() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cs-remove-version-number-from-css-js-activator.php';
	Cs_Remove_Version_Number_From_Css_Js_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cs-remove-version-number-from-css-js-deactivator.php
 */
function deactivate_cs_remove_version_number_from_css_js() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cs-remove-version-number-from-css-js-deactivator.php';
	Cs_Remove_Version_Number_From_Css_Js_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cs_remove_version_number_from_css_js' );
register_deactivation_hook( __FILE__, 'deactivate_cs_remove_version_number_from_css_js' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cs-remove-version-number-from-css-js.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cs_remove_version_number_from_css_js() {

	$plugin = new Cs_Remove_Version_Number_From_Css_Js();
	$plugin->run();

}
run_cs_remove_version_number_from_css_js();
