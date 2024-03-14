<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'Z_COMPANION_SITES_VER' ) ) {
	define( 'Z_COMPANION_SITES_VER', '1.0.0' );
}

if ( ! defined( 'Z_COMPANION_SLUG' ) ) {
	define( 'Z_COMPANION_SLUG', 'z-companion-sites' );
}


if ( ! defined( 'ALLOW_UNFILTERED_UPLOADS' ) ) {
	define( 'ALLOW_UNFILTERED_UPLOADS', true );
}

if ( ! defined( 'Z_COMPANION_SITES_NAME' ) ) {
	define( 'Z_COMPANION_SITES_NAME', __( 'Z Companion Sites', 'zita-site-library' ) );
}

if ( ! defined( 'Z_COMPANION_SITES_FILE' ) ) {
	define( 'Z_COMPANION_SITES_FILE', __FILE__ );
}


if ( ! defined( 'Z_COMPANION_SITES_BASE' ) ) {
	define( 'Z_COMPANION_SITES_BASE', plugin_basename( Z_COMPANION_SITES_FILE ) );
}

if ( ! defined( 'Z_COMPANION_SITES_DIR' ) ) {
	define( 'Z_COMPANION_SITES_DIR', Z_COMPANION_DIR_PATH.'import/' );
}

if ( ! defined( 'Z_COMPANION_SITES_URI' ) ) {
	define( 'Z_COMPANION_SITES_URI', Z_COMPANION_PLUGIN_DIR_URL.'import/' );
}

if ( ! function_exists( 'z_companion_site_setup' ) ) :
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );


	/**
	 * Zita Sites Setup
	 *
	 * @since 1.4.7
	 */
	function z_companion_site_setup() {
	require_once Z_COMPANION_SITES_DIR . 'inc/zita-library-page.php';
	require_once Z_COMPANION_SITES_DIR . 'inc/admin-load-page.php';
	}

	add_action( 'plugins_loaded', 'z_companion_site_setup' );

endif;