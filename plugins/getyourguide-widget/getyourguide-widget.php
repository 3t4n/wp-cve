<?php
/**
 * @package   GetYourGuide_Widget
 * @author    GetYourGuide
 * @link      https://www.getyourguide.com
 *
 * @wordpress-plugin
 * Plugin Name:       GetYourGuide Widget
 * Plugin URI:        https://github.com/getyourguide/wordpress-plugin
 * Description:       Displays GetYourGuide tours and activities.
 * Version:           1.3.10
 * Author:            GetYourGuide
 * Author URI:        http://partner.getyourguide.com
 * Text Domain:       getyourguide-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/getyourguide/wordpress-plugin
 * Domain Path:       /languages
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Make sure the plugin does not expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	if ( ! headers_sent() ) {
		if ( function_exists( 'http_response_code' ) ) {
			http_response_code( 403 );
		} else {
			header( 'HTTP/1.1 403 Forbidden', true, 403 );
		}
	}
	exit( 'Hi there! The GetYourGuide Widget requires functions included with WordPress. I am not meant to be addressed directly.' );
}

// Plugin requires a minimum PHP version to run.
const REQUIRED_PHP_VERSION = '5.4.0';
if ( version_compare( PHP_VERSION, REQUIRED_PHP_VERSION, '<' ) ) {
	exit( 'Hi there! The GetYourGuide Widget requires at least PHP version ' . REQUIRED_PHP_VERSION . ' to run. Please contact your web hosting service.' );
}

require_once __DIR__ . '/includes/widget-settings.class.php';
require_once __DIR__ . '/includes/widget-options.class.php';
require_once __DIR__ . '/includes/widget.class.php';

/**
 * Identify plugin as a relative path.
 * @return string
 */
function getyourguide_widget_plugin_self() {
	static $handle;
	isset( $handle ) || $handle = plugin_basename( __FILE__ );

	return $handle;
}

// Register the widget
add_action( 'widgets_init', create_function( '', 'register_widget("GetYourGuide_Widget");' ) );

// Create the other objects to register their hooks.
new GetYourGuide_Widget_Settings();

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
