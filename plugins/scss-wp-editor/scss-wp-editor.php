<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.itpathsolutions.com/
 * @since             1.0.0
 * @package           Scss_Wp_Editor
 *
 * @wordpress-plugin
 * Plugin Name:       SCSS WP Editor
 * Plugin URI:        https://wordpress.org/plugins/scss-wp-editor/
 * Description:       Easily Add, Compile and Optimize your SCSS to CSS within WordPress Admin.
 * Version:           1.1.6
 * Author:            IT Path Solutions
 * Author URI:        https://www.itpathsolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       scss-wp-editor
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
define( 'SCSS_WP_EDITOR_VERSION', '1.1.6' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-scss-wp-editor-activator.php
 */
function activate_scss_wp_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-scss-wp-editor-activator.php';
	Scss_Wp_Editor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-scss-wp-editor-deactivator.php
 */
function deactivate_scss_wp_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-scss-wp-editor-deactivator.php';
	Scss_Wp_Editor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_scss_wp_editor' );
register_deactivation_hook( __FILE__, 'deactivate_scss_wp_editor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-scss-wp-editor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_scss_wp_editor() {

	$plugin = new Scss_Wp_Editor();
	$plugin->run();

}
run_scss_wp_editor();
