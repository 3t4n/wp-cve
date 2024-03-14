<?php
/*
Plugin Name: SKT Themes Demo Importer
Plugin URI: https://wordpress.org/plugins/skt-themes-demo-import/
Description: Quickly import theme live demo content, widgets and settings. This provides a basic layout to build your website and speed up the development process.
Version: 1.2
Author: SKT Themes
Author URI: https://sktthemes.org/
License: GPL3
License URI: https://www.gnu.org/licenses/license-list.html#GNUGPLv3
Text Domain: skt-themes-demo-import
Tested up to: 6.3
Requires PHP: 5.6
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Display admin error message if PHP version is older than 5.6.
 * Otherwise execute the main plugin class.
 */
if ( version_compare( phpversion(), '5.6', '<' ) ) {

	/**
	 * Display an admin error notice when PHP is older the version 5.6.
	 * Hook it to the 'admin_notices' action.
	 */
	function SKT_old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$sSKT Themes Demo Importer%3$s plugin requires %2$sPHP 5.6+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.6.%4$s Your current version of PHP: %2$s%1$s%3$s', 'skt-themes-demo-import' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}
	add_action( 'admin_notices', 'SKT_old_php_admin_error_notice' );
}
else {

	// Current version of the plugin.
	define( 'SKT_VERSION', '1.0' );

	// Path/URL to root of this plugin, with trailing slash.
	define( 'SKT_PATH', plugin_dir_path( __FILE__ ) );
	define( 'SKT_URL', plugin_dir_url( __FILE__ ) );

	// Require main plugin file.
	require SKT_PATH . 'inc/class-skt-main.php';
	// Instantiate the main plugin class *Singleton*.
	$SKT_Demo_Import = SKT_Demo_Import::getInstance();
}