<?php
/**
 * Shortcode Lister Plugin
 *
 * @package Shortcode_Lister
 *
 * @wordpress-plugin
 * Plugin Name: Shortcode Lister
 * Description: A plugin to display a list of all the shortcodes available for use on the post and page edit screens.
 * Author: AMP-MODE
 * Author URI: https://amplifyplugins.com
 * Version: 2.1.1
 * License: GPL2
 * Plugin URI: https://github.com/AmplifyPlugins/edit-screen-shortcode-lister
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BTGBPYSDDUGVN
 * Text Domain: shortcode-lister
 */

/* Prevent direct access to the plugin */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, you are not allowed to access this page directly.' );
}
/**
 * Load text domain
 * ---------------------------------------------
 */
add_action( 'plugins_loaded', 'shortcode_lister_load_textdomain' );

/**
 * Load Text Domain
 *
 * @return void
 */
function shortcode_lister_load_textdomain() {
	load_plugin_textdomain( 'shortcode-lister', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}

/*
 * Includes for Shortcode Lister Plugin
 */
if ( ! defined( 'SHORTCODE_LISTER' ) ) {
	define( 'SHORTCODE_LISTER', __FILE__ );
}
if ( ! defined( 'SHORTCODE_LISTER_PLUGIN_DIR' ) ) {
	define( 'SHORTCODE_LISTER_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'SHORTCODE_LISTER_PLUGIN_URL' ) ) {
	define( 'SHORTCODE_LISTER_PLUGIN_URL', plugins_url( '', __FILE__ ) );
}
if ( ! defined( 'SHORTCODE_LISTER_PLUGIN_BASENAME' ) ) {
	define( 'SHORTCODE_LISTER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'SHORTCODE_LISTER_VERSION' ) ) {
	define( 'SHORTCODE_LISTER_VERSION', '2.1.1' );
}

if ( is_admin() ) {
	include SHORTCODE_LISTER_PLUGIN_DIR . '/includes/scripts-styles.php';
	include SHORTCODE_LISTER_PLUGIN_DIR . '/includes/get-shortcodes.php';
	include SHORTCODE_LISTER_PLUGIN_DIR . '/includes/shortcode-lister-settings.php';
}
