<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wptools.dev/
 * @since             1.1.0
 * @package           Disable_Block_Editor_Fullscreen_Mode
 *
 * @wordpress-plugin
 * Plugin Name:       Disable Block Editor FullScreen mode
 * Plugin URI:        https://iamankitpanchal.com/
 * Description:       This plugin is useful to Disable Block Editor default FullScreen mode in Latest WordPress 5.4+
 * Version:           2.7.1
 * Author:            Ankit Panchal
 * Author URI:        https://iamankitpanchal.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disable-block-editor-fullscreen-mode
 * Domain Path:       /languages
 *
 */
/* Code Credits: Jean-Baptiste Audras */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DISABLE_BLOCK_EDITOR_FULLSCREEN_MODE_VERSION', '2.7.1' );
define ( 'DBEF_REQUIRED_WP_VERSION', '5.4' ) ;

register_activation_hook( __FILE__, 'dbef_activate_plugin' );
function dbef_activate_plugin(){}

register_deactivation_hook( __FILE__, 'dbef_deactivate_plugin' );
function dbef_deactivate_plugin(){}

/* Checking current WordPress version */
global $wp_version;
if ( $wp_version < DBEF_REQUIRED_WP_VERSION ) {
    add_action( 'admin_init', 'dbef_deactivate_plugin_now' );
    add_action( 'admin_notices', 'dbef_errormsg' );
    add_action( 'admin_notices', 'dbef_deactivation_notice' );
}

/* hook to disable the fullscreen mode in editor. */
add_action( 'enqueue_block_editor_assets','dbef_disable_editor_fullscreen_by_default' );
function dbef_disable_editor_fullscreen_by_default() {
	$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
	wp_add_inline_script( 'wp-blocks', $script );
}

/* If current WP version is lower than 5.4 then deactivating the plugin */
function dbef_deactivate_plugin_now() {
	if ( is_plugin_active('disable-block-editor-fullscreen-mode/disable-block-editor-fullscreen-mode.php') ) {
	    deactivate_plugins('disable-block-editor-fullscreen-mode/disable-block-editor-fullscreen-mode.php');
	}
}

/* Show warning if WordPress version is not greater than or equal to 5.4 */
function dbef_errormsg () {
	$class = 'notice notice-error';
	$message = __( 'Error you did not meet the WordPress minimum version 5.4', 'dbef-plugin' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}

/* Show plugin deactivation notice if WordPress version not compatible. */
function dbef_deactivation_notice () {
	$class = 'notice notice-error';
	$message = __( 'Plugin Deactivated', 'dbef-plugin' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}