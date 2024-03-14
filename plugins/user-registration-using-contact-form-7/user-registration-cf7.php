<?php
/**
 * Plugin Name: User Registration Using Contact Form 7
 * Plugin URL: https://wordpress.org/plugin-url/
 * Description: User Registration Using Contact Form 7 plugin provide the feature to register the user to the website using Contact Form 7.
 * Version: 2.0
 * Author: ZealousWeb
 * Author URI: https://www.zealousweb.com/
 * Developer: The ZealousWeb Team
 * Developer E-Mail: opensource@zealousweb.com
 * Text Domain: zeal-user-reg-cf7
 * Domain Path: /languages
 *
 * Copyright: © 2009-2020 ZealousWeb.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 *
 * @package User Registration Using Contact Form 7
 * @since 1.0
 */

if ( !defined( 'ZURCF7_VERSION' ) ) {
	define( 'ZURCF7_VERSION', '2.0' ); // Version of plugin
}

if ( !defined( 'ZURCF7_FILE' ) ) {
	define( 'ZURCF7_FILE', __FILE__ ); // Plugin File
}

if ( !defined( 'ZURCF7_DIR' ) ) {
	define( 'ZURCF7_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if ( !defined( 'ZURCF7_URL' ) ) {
	define( 'ZURCF7_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if ( !defined( 'ZURCF7_PLUGIN_BASENAME' ) ) {
	define( 'ZURCF7_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}

if ( !defined( 'ZURCF7_META_PREFIX' ) ) {
	define( 'ZURCF7_META_PREFIX', 'zurcf7_' ); // Plugin metabox prefix
}

if ( !defined( 'ZURCF7_PREFIX' ) ) {
	define( 'ZURCF7_PREFIX', 'zurcf7' ); // Plugin prefix
}

if( !defined( 'ZURCF7_POST_TYPE' ) ) {
	define( 'ZURCF7_POST_TYPE', 'zuserreg_data' ); // Plugin registered post type name
}

/**
 * Initialize the main class
 */
if ( !function_exists( 'ZURCF7' ) ) {

	if ( is_admin() ) {
		require_once( ZURCF7_DIR . '/inc/admin/class.' . ZURCF7_PREFIX . '.admin.php' );
		require_once( ZURCF7_DIR . '/inc/admin/class.' . ZURCF7_PREFIX . '.admin.action.php' );
		require_once( ZURCF7_DIR . '/inc/admin/class.' . ZURCF7_PREFIX . '.admin.filter.php' );
	} else {
		require_once( ZURCF7_DIR . '/inc/front/class.' . ZURCF7_PREFIX . '.front.php' );
		require_once( ZURCF7_DIR . '/inc/front/class.' . ZURCF7_PREFIX . '.front.action.php' );
		require_once( ZURCF7_DIR . '/inc/front/class.' . ZURCF7_PREFIX . '.front.filter.php' );
	}

	// ZURCF7 Global ACF Function
	require_once( ZURCF7_DIR . '/inc/admin/' . ZURCF7_PREFIX . '.function.custom.php' );

	require_once( ZURCF7_DIR . '/inc/lib/class.' . ZURCF7_PREFIX . '.lib.php' );
	require_once( ZURCF7_DIR . '/inc/lib/class.' . ZURCF7_PREFIX . '.fb.signup.php' );

	//Initialize all the things.
	require_once( ZURCF7_DIR . '/inc/class.' . ZURCF7_PREFIX . '.php' );
}
