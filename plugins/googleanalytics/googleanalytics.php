<?php
/**
 * Plugin Name: ShareThis Dashboard for Google Analytics
 * Plugin URI: http://wordpress.org/extend/plugins/googleanalytics/
 * Description: Use Google Analytics on your WordPress site without touching any code, and view visitor reports right in your WordPress admin dashboard!
 * Version: 3.1.7
 * Author: ShareThis
 * Author URI: http://sharethis.com
 *
 * @package GoogleAnalytics
 */

if ( ! defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
}
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}
if ( ! defined( 'WP_PLUGIN_URL' ) ) {
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );
}
if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
}
if ( ! defined( 'GA_NAME' ) ) {
	define( 'GA_NAME', 'googleanalytics' );
}
if ( ! defined( 'GA_PLUGIN_DIR' ) ) {
	define( 'GA_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . GA_NAME );
}
if ( ! defined( 'GA_PLUGIN_URL' ) ) {
	define( 'GA_PLUGIN_URL', WP_PLUGIN_URL . '/' . GA_NAME );
}
if ( ! defined( 'GA_MAIN_FILE_PATH' ) ) {
	define( 'GA_MAIN_FILE_PATH', __FILE__ );
}
if ( ! defined( 'GA_SHARETHIS_SCRIPTS_INCLUDED' ) ) {
	define( 'GA_SHARETHIS_SCRIPTS_INCLUDED', 0 );
}

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . WP_PLUGIN_DIR . '/googleanalytics/credentials.json');
define('GOOGLE_APPLICATION_CREDENTIALS', WP_PLUGIN_DIR . '/googleanalytics/credentials.json');

/**
 * Prevent to launch the plugin within different plugin dir name
 */
if ( false === preg_match( '/(\/|\\\)' . GA_NAME . '(\/|\\\)/', realpath( __FILE__ ), $test ) ) {
	echo esc_html(
		sprintf(
		/* translators: %s refers to the Google Analytics directory name. */
			__(
				'Invalid plugin installation directory. Please verify if the plugin\'s dir name is equal to "%s".'
			),
			esc_attr( GA_NAME )
		)
	);

	// To make able the message above to be displayed in the activation error notice.
	die();
}

const GOOGLEANALYTICS_VERSION = '3.1.7';

// Requires.
require_once GA_PLUGIN_DIR . '/lib/analytics-admin/vendor/autoload.php';
require_once GA_PLUGIN_DIR . '/overwrite/ga-overwrite.php';
require_once GA_PLUGIN_DIR . '/class/class-ga-autoloader.php';
require_once GA_PLUGIN_DIR . '/class/class-ga-autoloader.php';
require_once GA_PLUGIN_DIR . '/tools/class-ga-supportlogger.php';

if ( version_compare( phpversion(), '7.4', '>=' ) ) {
    Ga_Autoloader::register();
    Ga_Hook::add_hooks( GA_MAIN_FILE_PATH );

    add_action( 'plugins_loaded', 'Ga_Admin::loaded_googleanalytics' );
    add_action( 'init', 'Ga_Helper::init' );
} else {
    if ( defined( 'WP_CLI' ) ) {
        WP_CLI::warning( _google_analytics_php_version_text() );
    } else {
        add_action( 'admin_notices', '_google_analytics_php_version_error' );
    }
}

/**
 * String describing the minimum PHP version.
 *
 * @return string
 */
function _google_analytics_php_version_text() {
    return __( 'ShareThis Dashboard for Google Analytics plugin error: Your version of PHP is too old to run this plugin. You must be running PHP 7.4 or higher.', 'googlanalytics' );
}


/**
 * Admin notice for incompatible versions of PHP.
 */
function _google_analytics_php_version_error() {
    printf( '<div class="error"><p>%s</p></div>', esc_html( _google_analytics_php_version_text() ) );
}
