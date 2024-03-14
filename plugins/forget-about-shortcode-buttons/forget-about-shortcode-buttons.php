<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://designsandcode.com
 * @since             1.0.0
 * @package           Forget_About_Shortcode_Buttons
 *
 * @wordpress-plugin
 * Plugin Name:       Forget About Shortcode Buttons
 * Plugin URI:        http://www.designsandcode.com/wordpress-plugins/forget-about-shortcode-buttons-plugin/
 * Description:       A visual way to add CSS buttons in the rich text editor and to your themes.
 * Version:           2.1.3
 * Author:            Designs & Code
 * Author URI:        http://designsandcode.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       forget-about-shortcode-buttons
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-forget-about-shortcode-buttons-activator.php
 */
function activate_forget_about_shortcode_buttons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-forget-about-shortcode-buttons-activator.php';
	Forget_About_Shortcode_Buttons_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-forget-about-shortcode-buttons-deactivator.php
 */
function deactivate_forget_about_shortcode_buttons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-forget-about-shortcode-buttons-deactivator.php';
	Forget_About_Shortcode_Buttons_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_forget_about_shortcode_buttons' );
register_deactivation_hook( __FILE__, 'deactivate_forget_about_shortcode_buttons' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-forget-about-shortcode-buttons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_forget_about_shortcode_buttons() {

	$plugin = new Forget_About_Shortcode_Buttons();
	$plugin->run();

}
run_forget_about_shortcode_buttons();
