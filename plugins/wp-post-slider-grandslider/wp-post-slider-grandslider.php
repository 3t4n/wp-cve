<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://forhad.net/
 * @since             1.0.0
 * @package           Wp_Post_Slider_Grandslider
 *
 * @wordpress-plugin
 * Plugin Name:       WP Post Slider GrandSlider
 * Plugin URI:        https://forhad.net/plugins/wp-post-slider-grandslider/
 * Description:       This plugin represent a slider.
 * Version:           2.0.0
 * Author:            Forhad
 * Author URI:        https://forhad.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-post-slider-grandslider
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
define( 'WP_POST_SLIDER_GRANDSLIDER_VERSION', '2.0.0' );
define( 'WPPSGS_DIR_URL_FILE', plugin_dir_url( dirname( __FILE__ ) ) );
define( 'WPPSGS_BASENAME_FILE', plugin_basename( __FILE__ ) );
define( 'WPPSGS_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-post-slider-grandslider-activator.php
 */
function activate_wp_post_slider_grandslider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-slider-grandslider-activator.php';
	Wp_Post_Slider_Grandslider_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-post-slider-grandslider-deactivator.php
 */
function deactivate_wp_post_slider_grandslider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-slider-grandslider-deactivator.php';
	Wp_Post_Slider_Grandslider_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_post_slider_grandslider' );
register_deactivation_hook( __FILE__, 'deactivate_wp_post_slider_grandslider' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-slider-grandslider.php';
require_once plugin_dir_path( __FILE__ ) .'admin/skeleton/classes/setup.class.php';
require_once plugin_dir_path( __FILE__ ) .'admin/partials/pslider-options.php';
require_once plugin_dir_path( __FILE__ ) .'admin/partials/tmonial-options.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_post_slider_grandslider() {

	$plugin = new Wp_Post_Slider_Grandslider();
	$plugin->run();

}
run_wp_post_slider_grandslider();
