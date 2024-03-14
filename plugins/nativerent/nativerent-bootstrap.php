<?php
/**
 * Bootstrap file
 *
 * @package NativeRent
 */

defined( 'ABSPATH' ) || exit;

// Plugin Folder Path.
if ( ! defined( 'NATIVERENT_PLUGIN_DIR' ) ) {
	define(
		'NATIVERENT_PLUGIN_DIR',
		function_exists( 'plugin_dir_path' ) ? plugin_dir_path( __FILE__ ) : rtrim( dirname( __FILE__ ), '/\\' ) . '/'
	);
}
// Plugin Folder URL.
if ( ! defined( 'NATIVERENT_PLUGIN_URL' ) ) {
	define( 'NATIVERENT_PLUGIN_URL', function_exists( 'plugin_dir_url' ) ? plugin_dir_url( __FILE__ ) : '' );
}
// Plugin Root File.
if ( ! defined( 'NATIVERENT_PLUGIN_FILE' ) ) {
	define( 'NATIVERENT_PLUGIN_FILE', __DIR__ . '/nativerent.php' );
}
// Value for minimal priority argument.
if ( ! defined( 'NATIVERENT_PLUGIN_MIN_PRIORITY' ) ) {
	define( 'NATIVERENT_PLUGIN_MIN_PRIORITY', ~PHP_INT_MAX );
}
// Value for maximal priority argument.
if ( ! defined( 'NATIVERENT_PLUGIN_MAX_PRIORITY' ) ) {
	define( 'NATIVERENT_PLUGIN_MAX_PRIORITY', PHP_INT_MAX );
}
// Get param name for updating expired auth data.
if ( ! defined( 'NATIVERENT_PARAM_AUTH' ) ) {
	define( 'NATIVERENT_PARAM_AUTH', '_nrpluginauth' );
}
// Interval in seconds for running auto-update monetizations.
if ( ! defined( 'NATIVERENT_UPDATE_MONETIZATIONS_INTERVAL' ) ) {
	$_nsci_sec = getenv( 'NATIVERENT_UPDATE_MONETIZATIONS_INTERVAL' );
	define( 'NATIVERENT_UPDATE_MONETIZATIONS_INTERVAL', is_numeric( $_nsci_sec ) ? $_nsci_sec : ( 12 * 60 * 60 ) );
	unset( $_nsci_sec );
}
// Plugin version.
if ( ! defined( 'NATIVERENT_PLUGIN_VERSION' ) ) {
	define( 'NATIVERENT_PLUGIN_VERSION', '1.9.1' );
}

require_once NATIVERENT_PLUGIN_DIR . 'includes/class-autoloader.php';
require_once NATIVERENT_PLUGIN_DIR . 'includes/functions.php';
