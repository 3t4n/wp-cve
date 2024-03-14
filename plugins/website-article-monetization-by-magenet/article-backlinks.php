<?php
/**
 * Plugin Name: Website Article Monetization By MageNet
 * Description: Enables you to monetize your site through the content placement. This plugin automatically creates a new webpage on your site and place third-party content there. Article Plugin is a hassle-free tool that helps you earn more with your website.
 * Author URI:  https://www.magenet.com/
 * Author:      MageNet.com
 * Version:     1.0.11
 * Text Domain: website-article-monetization-by-magenet
 *
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 *
 */

defined( 'ABSPATH' ) || die( 'Bye.' );

define( 'ABP_MAGENET_API_VERSION', 1 );

define( 'ABP_PLUGIN_FILE_PATH', plugin_basename( __FILE__ ) );
define( 'ABP_PLUGIN_FILE', __FILE__ );
define( 'ABP_HOST_SITE', get_site_url() );

$abp_cache_time_const = get_option( 'abp_cache_time_const', '' );
if ( ! empty( $abp_cache_time_const ) ) {
	define( 'ABP_CACHE_TIME', $abp_cache_time_const );
} else {
	update_option( 'abp_cache_time_const', 3600 );
	define( 'ABP_CACHE_TIME', 3600 );
}

$abp_magenet_api_url = get_option( 'abp_magenet_api_url', '' );
if ( ! empty( $abp_magenet_api_url ) ) {
	if ( strpos( $abp_magenet_api_url, 'https://' ) !== false ) {
		$abp_magenet_api_url = str_replace( 'https://', 'http://', $abp_magenet_api_url );
	}
	define( 'ABP_MAGENET_API_URL', $abp_magenet_api_url );
} else {
	update_option( 'abp_magenet_api_url', 'http://api.magenet.com' );
	define( 'ABP_MAGENET_API_URL', 'http://api.magenet.com' );
}

$abp_cp_host = get_option( 'abp_cp_host', '' );
if ( ! empty( $abp_cp_host ) ) {
	define( 'ABP_CP_HOST', $abp_cp_host );
} else {
	update_option( 'abp_cp_host', 'https://cp.magenet.com' );
	define( 'ABP_CP_HOST', 'https://cp.magenet.com' );
}

define( 'ABP_TABLE_NAME', 'abp_posts' );

define( 'ABP_STATUS_ADD', 'add' );
define( 'ABP_STATUS_UPDATE', 'update' );
define( 'ABP_STATUS_DELETE', 'delete' );

if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$plugin_data    = get_plugin_data( __FILE__, false, false );
$plugin_version = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : 1;

define( 'ABP_VERSION_PLUGIN', $plugin_version );
define( 'ABP_VERSION_PHP', phpversion() );
define( 'ABP_VERSION_WORDPRESS', isset( $wp_version ) ? $wp_version : 0 );

require_once plugin_dir_path( __FILE__ ) . '/abp-functions.php';

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . '/admin/article-backlinks-admin.php';
}
