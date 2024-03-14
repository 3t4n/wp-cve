<?php
/**
 * Plugin Name: Dark Mode Toggle
 * Plugin URI: https://scriptstown.com/wordpress-plugins/dark-mode-toggle/
 * Description: Bring dark mode toggle switch to your WordPress website. A simple switch to turn on and off the dark mode. Fast and easy to use.
 * Version: 1.3.8
 * Author: ScriptsTown
 * Author URI: https://scriptstown.com/
 * Text Domain: dark-mode-toggle
 * Requires at least: 5.3
 * Requires PHP: 7.0
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || exit;

define( 'DARKMODETG_PLUGIN_VER', '1.3.8' );
define( 'DARKMODETG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DARKMODETG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DARKMODETG_PLUGIN_BASE', plugin_basename( __FILE__ ) );

// Activation hooks.
require DARKMODETG_PLUGIN_PATH . 'inc/activation.php';

if ( apply_filters( 'darkmodetg_load', true ) ) {
	// Autoload classes.
	require DARKMODETG_PLUGIN_PATH . 'vendor/autoload.php';

	// Helper functions.
	require DARKMODETG_PLUGIN_PATH . 'inc/helpers.php';

	// Initialize plugin services.
	DarkModeToggle\Plugin::init_services();
}
