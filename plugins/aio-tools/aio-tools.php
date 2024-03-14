<?php

/**
 *
 * @wordpress-plugin
 * Plugin Name:       All in One Tools
 * Plugin URI:        https://shost.vn
 * Description:       Best WordPress utilities Plugin – Easily Improve Your website
 * Version:           2.2
 * Author:            Shost.vn
 * Author URI:        https://www.shost.vn
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       w2w
 *
 */



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin name.
 */
define( 'W2W_PLUGIN_NAME', 'All in One Tools' );

/**
 * Current plugin version.
 */
define( 'W2W_VERSION', '2.2' );

/**
 * Plugin website URL.
 */
define( 'W2W_PLUGIN_HOME', 'https://www.shost.vn' );

/**
 * Plugin owner's name.
 */
define( 'W2W_OWNER_NAME', 'All in One Tools' );

/**
 * Plugin owner's website URL.
 */
define( 'W2W_OWNER_HOME', 'https://www.shost.vn' );

define('W2W_BASE_NAME', plugin_basename(__FILE__));

/**
 * Plugin Path
 */
define( 'W2W_URL', plugin_dir_url( __FILE__ ) );
define( 'W2W_PATH', realpath( dirname( __FILE__ ) ) . '/' );

/**
 * Plugin includes path
 */
define( 'W2W_INC_PATH', W2W_PATH . 'includes/' );
/**
 * Plugin libraries path
 */
define( 'W2W_LIB_PATH', W2W_PATH . 'vendor/' );
/**
 * Admin Path
 */
define( 'W2W_ADMIN_PATH', W2W_PATH . 'admin/' );
/**
 * Public Path
 */
define( 'W2W_PUBLIC_PATH', W2W_PATH . 'public/' );

/**
 * Load all plugin options
 */
if ( ! function_exists( 'w2w_get_option' ) ) {
	function w2w_get_option( $option = '', $default = null ) {
		$w2w_options = get_option( 'w2w_options' );

		return ( isset( $w2w_options[ $option ] ) ) ? $w2w_options[ $option ] : $default;
	}
}
require W2W_INC_PATH . 'class-aio-tools.php';

spl_autoload_register( 'w2w_autoloader' );
function w2w_autoloader( $class_name ) {
	if ( false === strpos( $class_name, 'AIOTools\\' ) ) {
		return;
	}
	$class_name = str_replace( 'AIOTools\\', '', $class_name );
	$filename = strtolower( str_replace( '_', '-', $class_name ) );	
	$path     = W2W_INC_PATH . 'classes/class-' . $filename . '.php';
	if ( file_exists( $path ) ) {
		require_once( $path );
	}
}
function run_aio_tools() {
	if( preg_match( '/(\.txt|\.pdf|\.xml|\.ico|\.gz|\/feed\/?)/', $_SERVER['REQUEST_URI'] ) ) {return;}
	$plugin = new AIO_Tools();
	$plugin->run();
}
run_aio_tools();