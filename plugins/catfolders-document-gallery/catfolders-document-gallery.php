<?php
/**
 * Plugin Name:       CatFolders Document Gallery
 * Description:       Display WordPress PDF gallery from folder. This WordPress document gallery is 100% free, supports unlimited file types, and works great with CatFolders - WP Media Folders.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           1.4.6
 * Author:            CatFolders
 * Author URI:        https://wpmediafolders.com/
 * Text Domain:       catfolders-document-gallery
 * Domain Path:       /languages
 * Requires at least: 4.7
 * Requires PHP: 5.4
 * WC requires at least: 3.0.0
 */

namespace CatFolder_Document_Gallery;

defined( 'ABSPATH' ) || exit;

global $wp_version;

if ( function_exists( 'CatFolder_Document_Gallery\\plugin_init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Fallback.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
}

if ( ! \version_compare( $wp_version, '5.9', '>=' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/FallbackMinimumWp.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
};

if ( ! defined( 'CATF_DG_FILE' ) ) {
	define( 'CATF_DG_FILE', __FILE__ );
}

if ( ! defined( 'CATF_DG_DIR' ) ) {
	define( 'CATF_DG_DIR', __DIR__ );
}

if ( ! defined( 'CATF_DG_URL' ) ) {
	define( 'CATF_DG_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'CATF_DG_IMAGES' ) ) {
	define( 'CATF_DG_IMAGES', CATF_DG_URL . 'assets/images/' );
}

if ( ! defined( 'CATF_DG_VERSION' ) ) {
	define( 'CATF_DG_VERSION', '1.4.6' );
}

spl_autoload_register(
	function ( $class ) {
		$prefix   = __NAMESPACE__;
		$base_dir = __DIR__ . '/includes';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class_name = substr( $class, $len );
		$file                = $base_dir . str_replace( '\\', '/', $relative_class_name ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

if ( ! function_exists( 'CatFolder_Document_Gallery\\plugin_init' ) ) {
	function plugin_init() {

		if ( ! defined( 'CATF_VERSION' ) ) {
			add_action( 'admin_notices', array( \CatFolder_Document_Gallery\Engine\ActDeact::class, 'install_catf_dg_admin_notice' ) );
			return;
		}

		Initialize::get_instance();
		I18n::loadPluginTextdomain();

	}
}

if ( ! wp_installing() ) {
	add_action( 'plugins_loaded', 'CatFolder_Document_Gallery\\plugin_init' );
}

register_activation_hook( CATF_DG_FILE, array( \CatFolder_Document_Gallery\Engine\ActDeact::class, 'activate' ) );
register_deactivation_hook( CATF_DG_FILE, array( \CatFolder_Document_Gallery\Engine\ActDeact::class, 'deactivate' ) );

