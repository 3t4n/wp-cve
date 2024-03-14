<?php
/*
 * Plugin Name: WP Photo Album Plus
 * Description: Easily manage and display your photo albums and slideshows within your WordPress site.
 * Version: 8.6.04.009
 * Author: J.N. Breetvelt a.k.a. OpaJaap
 * Author URI: http://wppa.opajaap.nl/
 * Plugin URI: http://wordpress.org/extend/plugins/wp-photo-album-plus/
 * Text Domain: wp-photo-album-plus
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly (1)" );

require_once 'wppa-init.php';
require_once 'wppa-defaults.php';

/* See explanation on activation hook in wppa-setup.php */
register_activation_hook( __FILE__, 'wppa_activate_plugin' );

/* WP GLOBALS */
global $wpdb;
global $wp_version;

/* WPPA Version */
global $wppa_version; 		$wppa_version = '8.6.04.009';							// WPPA software version
global $wppa_revno; 		$wppa_revno = str_replace( '.', '', $wppa_version );	// WPPA db version

/* Init page js data */
global $wppa_js_page_data; 	$wppa_js_page_data = '';

/* start timers */
add_action( 'plugins_loaded', 'wppa_init_timer', '1' );
function wppa_init_timer() {
global $wppa_endtime;

	$met = intval( ini_get( 'max_execution_time' ) );

	if ( wppa_is_cron() ) {
		$the_met = max ( min( $met, 120 ), 30 );
	}
	else {
		$the_met = max ( min( $met, 90 ), 30 );
	}

	$wppa_endtime = time() + $the_met;

	// for debuggging
//	$wppa_endtime = time() + 8; //
}

/* Set defaults */
// add_action( 'plugins_loaded', 'wppa_set_defaults', '2' );

add_action( 'plugins_loaded', 'wppa_get_session_id', 1 );

/* CONSTANTS
/*
/* Check for php version
/* PHP_VERSION_ID is available as of PHP 5.2.7, if our
/* version is lower than that, then emulate it
*/
if ( ! defined( 'PHP_VERSION_ID' ) ) {
	$version = explode( '.', PHP_VERSION );
	define( 'PHP_VERSION_ID', ( $version[0] * 10000 + $version[1] * 100 + $version[2] ) );
}

/* To run WPPA+ on a multisite in single site mode,
/* add to wp-config.php: define('WPPA_MULTISITE_GLOBAL', true); */
if ( ! defined('WPPA_MULTISITE_GLOBAL') ) {
	define( 'WPPA_MULTISITE_GLOBAL', false );
}

/* To run WPPA+ in a multisite old style mode,
/* add to wp-config.php: define('WPPA_MULTISITE_BLOGSDIR', true); */
if ( ! defined('WPPA_MULTISITE_BLOGSDIR') ) {
	define( 'WPPA_MULTISITE_BLOGSDIR', false );
}

/* To run WPPA+ in a multisite new style, new implementation mode,
/* add to wp-config.php: define('WPPA_MULTISITE_INDIVIDUAL', true); */
if ( ! defined('WPPA_MULTISITE_INDIVIDUAL') ) {
	define( 'WPPA_MULTISITE_INDIVIDUAL', false );
}

/* Choose the right db prifix */
if ( is_multisite() && WPPA_MULTISITE_GLOBAL ) {
	$wppa_prefix = $wpdb->base_prefix;
}
else {
	$wppa_prefix = $wpdb->prefix;
}

/* DB Tables */
define( 'WPPA_ALBUMS',   $wppa_prefix . 'wppa_albums' );
$wpdb->wppa_albums = WPPA_ALBUMS;
define( 'WPPA_PHOTOS',   $wppa_prefix . 'wppa_photos' );
$wpdb->wppa_photos = WPPA_PHOTOS;
define( 'WPPA_RATING',   $wppa_prefix . 'wppa_rating' );
$wpdb->wppa_rating = WPPA_RATING;
define( 'WPPA_COMMENTS', $wppa_prefix . 'wppa_comments' );
$wpdb->wppa_comments = WPPA_COMMENTS;
define( 'WPPA_IPTC',	 $wppa_prefix . 'wppa_iptc' );
$wpdb->wppa_iptc = WPPA_IPTC;
define( 'WPPA_EXIF', 	 $wppa_prefix . 'wppa_exif' );
$wpdb->wppa_exif = WPPA_EXIF;
define( 'WPPA_INDEX', 	 $wppa_prefix . 'wppa_index' );
$wpdb->wppa_index = WPPA_INDEX;
define( 'WPPA_SESSION',	 $wppa_prefix . 'wppa_session' );
$wpdb->wppa_session = WPPA_SESSION;
define( 'WPPA_CACHES', 	 $wppa_prefix . 'wppa_caches' );
$wpdb->wppa_caches = WPPA_CACHES;

// To fix a problem in Windows local host systems:
function wppa_trims( $txt ) {
	return trim( $txt, "\\/" );
}
function wppa_flips( $txt ) {
	return str_replace( "\\", "/", $txt );
}
function wppa_trimflips( $txt ) {
	return wppa_flips( wppa_trims ( $txt ) );
}

/* Paths and urls */ 									// Standard examples
define( 'WPPA_FILE', basename( __FILE__ ) );			// wppa.php
define( 'WPPA_PATH', dirname( __FILE__ ) );				// /.../wp-content/plugins/wp-photo-album-plus
define( 'WPPA_NAME', basename( dirname( __FILE__ ) ) );	// wp-photo-album-plus
define( 'WPPA_URL',  plugins_url() . '/' . WPPA_NAME ); // http://.../wp-photo-album-plus
define( 'WPPA_ABSPATH', wppa_flips( ABSPATH ) ); 		// ABSPATH formatted for Windows servers

// Although i may not use wp constants directly,
// there is no function that returns the path to wp-content,
// so, if you changed the location of wp-content, i have to use WP_CONTENT_DIR,
// because wp-content needs not to be relative to ABSPATH
if ( defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WPPA_CONTENT_PATH', wppa_flips( WP_CONTENT_DIR ) );
}

// In the normal case i use content_url() with the site_url() part replaced by WPPA_ABSPATH,
// i.e. ABSPATH with the slashes in the right direction (in case of windows server)
else {
	define( 'WPPA_CONTENT_PATH',
		str_replace( wppa_trimflips( site_url() ) . '/',
		WPPA_ABSPATH, wppa_flips( content_url() ) )
		);												// /.../wp-content
}

// Also define my url to wp-content:
define( 'WPPA_CONTENT_URL', content_url() );

// Now you can convert a path to an url vv form files inside wp-content as follows
// $path = str_replace( WPPA_CONTENT_URL, WPPA_CONTENT_PATH, $url );
// $url = str_replace( WPPA_CONTENT_PATH, WPPA_CONTENT_URL, $path );

define( 'WPPA_NONCE' , 'wppa-update-check' );

/* DONE with trivial constants */

/* Declare init actions */

/* Start session */
add_action( 'init', 'wppa_begin_session', 1 );
add_action( 'admin_init', 'wppa_begin_session', 1 );

/* Init path and url constants */
add_action( 'init', 'wppa_init_path_and_url_constants', 1 );

/* May not be there yet, so try again */
add_action( 'init', 'wppa_load_plugin_textdomain' );

/* Load adminbar menu if required, after translations loaded */
add_action( 'init', 'wppa_admin_bar_init', 12);

/* END SESSION */
add_action( 'shutdown', 'wppa_session_end' );
