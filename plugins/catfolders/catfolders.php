<?php
/**
 * Plugin Name: CatFolders Lite - WP Media Folders
 * Plugin URI: https://wpmediafolders.com/
 * Description: Organize and manage your files with WordPress media library folders. Unlimitedly fast, flexible, and professional.
 * Version: 2.3.2
 * Author: CatFolders
 * Author URI: https://wpmediafolders.com/
 * Text Domain: catfolders
 * Domain Path: /languages
 *
 * @package CatFolders Plugin
 */
namespace CatFolders;

defined( 'ABSPATH' ) || exit;

global $wp_version;

if ( function_exists( 'CatFolders\\init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Views/fallback-exists.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

if ( ! \version_compare( phpversion(), '7.2', '>=' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Views/fallback-minimum-php.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
};

if ( ! \version_compare( $wp_version, '5.2', '>=' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Views/fallback-minimum-wp.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

if ( ! defined( 'CATF_PREFIX' ) ) {
	define( 'CATF_PREFIX', 'catf' );
}

if ( ! defined( 'CATF_VERSION' ) ) {
	define( 'CATF_VERSION', '2.3.2' );
}

if ( ! defined( 'CATF_PLUGIN_FILE' ) ) {
	define( 'CATF_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'CATF_PLUGIN_URL' ) ) {
	define( 'CATF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'CATF_PLUGIN_PATH' ) ) {
	define( 'CATF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CATF_PLUGIN_BASE_NAME' ) ) {
	define( 'CATF_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'CATF_ROUTE_NAMESPACE' ) ) {
	define( 'CATF_ROUTE_NAMESPACE', 'CatFolders/v1' );
}

if ( ! defined( 'CATF_LICENSE_API_URL' ) ) {
	define( 'CATF_LICENSE_API_URL', 'https://catfolders.com/' );
}

if ( ! defined( 'CATF_LICENSE_API_ITEM_ID' ) ) {
	define( 'CATF_LICENSE_API_ITEM_ID', 10 );
}

if ( ! defined( 'CATF_IS_DEVELOPMENT' ) ) {
	define( 'CATF_IS_DEVELOPMENT', false );
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

if ( ! function_exists( 'CatFolders\\init' ) ) {
	function init() {
		new \CatFolders\Classes\Svg();
		new \CatFolders\Core\Initialize();
	}
}
add_action( 'plugins_loaded', 'CatFolders\\init' );

register_activation_hook( __FILE__, array( 'CatFolders\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CatFolders\\Plugin', 'deactivate' ) );
