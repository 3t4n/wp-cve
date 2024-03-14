<?php
/*
Plugin Name: CM E-Mail Registration Blacklist
Plugin URI: https://www.cminds.com/wordpress-plugins-library/email-registration-blacklist-plugin-for-wordpress/
Description: Block users from certain domains from registering in your site
Author: CreativeMindsSolutions
Version: 1.4.7
*/

if ( version_compare( '5.3', phpversion(), '>' ) ) {
	die( 'We are sorry, but you need to have at least PHP 5.3 to run this plugin (currently installed version: ' . phpversion() . ') - please upgrade or contact your system administrator.' );
}

//Define constants
/**
 * Define Plugin Version
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_VERSION' ) ) {
	define( 'CMEB_VERSION', '1.4.7' );
}

/**
 * Define Plugin name
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_NAME' ) ) {
	define( 'CMEB_NAME', 'CM E-Mail Registration Blacklist' );
}

/**
 * Define Plugin canonical name
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_CANONICAL_NAME' ) ) {
	define( 'CMEB_CANONICAL_NAME', 'CM E-Mail Registration Blacklist' );
}

/**
 * Define Plugin license name
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_LICENSE_NAME' ) ) {
	define( 'CMEB_LICENSE_NAME', 'CM E-Mail Registration Blacklist' );
}

/**
 * Define Plugin File Name
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_PLUGIN_FILE' ) ) {
	define( 'CMEB_PLUGIN_FILE', __FILE__ );
}

/**
 * Define Plugin Slug name
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_SLUG_NAME' ) ) {
	define( 'CMEB_SLUG_NAME', 'cm-email-blacklist' );
}

/**
 * Define Plugin Slug Name
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_MENU_ITEM' ) ) {
	define( 'CMEB_MENU_ITEM', 'cmeb_menu' );
}

/**
 * Define Plugin release notes url
 *
 * @since 1.0
 */
if ( !defined( 'CMEB_RELEASE_NOTES' ) ) {
	define( 'CMEB_RELEASE_NOTES', 'https://www.cminds.com/wordpress-plugins-library/email-registration-blacklist-plugin-for-wordpress/' );
}

if ( !defined( 'CMEB_PATH' ) ) {
	define( 'CMEB_PATH', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) );
}
if ( !defined( 'CMEB_URL' ) ) {
	define( 'CMEB_URL', plugins_url( '', __FILE__ ) );
}

//Init the plugin
require_once CMEB_PATH . '/lib/CMEB.php';
register_activation_hook( __FILE__, array( 'CMEB', 'install' ) );
register_uninstall_hook( __FILE__, array( 'CMEB', 'uninstall' ) );
CMEB::init();