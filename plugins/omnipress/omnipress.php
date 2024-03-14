<?php
/**
* Plugin Name: Omnipress
* Description: Omnipress is a ready-made WordPress Design Blocks, similar to the Gutenberg WordPress block editor, that takes a holistic approach to changing your complete site.
* Author: omnipressteam
* Author URI: https://omnipressteam.com/
* Version: 1.2.2
* Text Domain: omnipress
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*
* @package Omnipress
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Omnipress is initialized.
 *
 * @since 1.1.0
 */
do_action( 'omnipress_init' );

/**
 * ===============================
 * Omnipress core constants starts.
 * ===============================
 */

if ( ! defined( 'OMNIPRESS_VERSION' ) ) {

	/**
	 * Omnipress current version.
	 */
	define( 'OMNIPRESS_VERSION', '1.2.2' );
}

if ( ! defined( 'OMNIPRESS_FILE' ) ) {

	/**
	 * Omnipress core file.
	 */
	define( 'OMNIPRESS_FILE', __FILE__ );
}

if ( ! defined( 'OMNIPRESS_PATH' ) ) {

	/**
	 * Path to Omnipress plugin folder.
	 */
	define( 'OMNIPRESS_PATH', trailingslashit( plugin_dir_path( OMNIPRESS_FILE ) ) );
}

if ( ! defined( 'OMNIPRESS_URL' ) ) {

	/**
	 * URL to Omnipress plugin folder.
	 */
	define( 'OMNIPRESS_URL', trailingslashit( plugin_dir_url( OMNIPRESS_FILE ) ) );
}

/**
 * ==============================
 * Omnipress core constants ends.
 * ==============================
 */


/**
 * Bootstrap Omnipress plugin.
 */
function omnipress() {

	static $loaded = false;

	if ( $loaded ) {
		return;
	}

	require_once OMNIPRESS_PATH . 'vendor/autoload.php';

	$instance = Omnipress\Init::instance();

	/**
	 * Omnipress is loaded.
	 *
	 * @since 1.1.0
	 */
	do_action( 'omnipress_loaded', $instance );

	$loaded = true;

	return $instance;

}
add_action( 'plugins_loaded', 'omnipress' );
