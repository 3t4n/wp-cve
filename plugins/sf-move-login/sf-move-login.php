<?php
/**
 * Plugin Name: SF Move Login
 * Plugin URI: https://www.screenfeed.fr/plugin-wp/move-login/
 * Description: Change your login URL.
 * Version: 2.5.3
 * Author: Grégory Viguier
 * Author URI: https://www.screenfeed.fr/
 * License: GPLv3
 * License URI: https://www.screenfeed.fr/gpl-v3.txt
 * Network: true
 * Text Domain: sf-move-login
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

if ( empty( $GLOBALS['wp_version'] ) || version_compare( $GLOBALS['wp_version'], '3.1' ) < 0 || version_compare( phpversion(), '5.3' ) < 0 ) {
	return;
}

/*------------------------------------------------------------------------------------------------*/
/* !CONSTANTS =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

define( 'SFML_VERSION',         '2.5.3' );
define( 'SFML_FILE',            __FILE__ );
define( 'SFML_PLUGIN_BASENAME', plugin_basename( SFML_FILE ) );
define( 'SFML_PLUGIN_DIR',      plugin_dir_path( SFML_FILE ) );


/*------------------------------------------------------------------------------------------------*/
/* !INCLUDES ==================================================================================== */
/*------------------------------------------------------------------------------------------------*/

include( SFML_PLUGIN_DIR . 'inc/functions/compat.php' );
include( SFML_PLUGIN_DIR . 'inc/functions/utilities.php' );
include( SFML_PLUGIN_DIR . 'inc/classes/class-sfml-singleton.php' );
include( SFML_PLUGIN_DIR . 'inc/classes/class-sfml-options.php' );

if ( is_admin() ) {
	include( SFML_PLUGIN_DIR . 'inc/activate.php' );
}


add_action( 'plugins_loaded', 'sfml_init', 20 );
/**
 * Plugin init: include files.
 */
function sfml_init() {
	sfml_lang_init();

	SFML_Options::get_instance();

	// Administration.
	if ( is_admin() ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			include_once( SFML_PLUGIN_DIR . 'inc/ajax.php' );
		} else {
			include_once( SFML_PLUGIN_DIR . 'inc/admin.php' );
		}
	}

	// !EMERGENCY BYPASS.
	if ( defined( 'SFML_ALLOW_LOGIN_ACCESS' ) && SFML_ALLOW_LOGIN_ACCESS ) {
		return;
	}

	include_once( SFML_PLUGIN_DIR . 'inc/functions/deprecated.php' );
	include_once( SFML_PLUGIN_DIR . 'inc/url-filters.php' );
	include_once( SFML_PLUGIN_DIR . 'inc/redirections-and-dies.php' );
}


/*------------------------------------------------------------------------------------------------*/
/* !I18N SUPPORT ================================================================================ */
/*------------------------------------------------------------------------------------------------*/

/**
 * Load translations.
 */
function sfml_lang_init() {
	static $done = false;

	if ( $done ) {
		return;
	}

	$done = true;

	load_plugin_textdomain( 'sf-move-login', false, dirname( plugin_basename( SFML_FILE ) ) . '/languages' );
}
