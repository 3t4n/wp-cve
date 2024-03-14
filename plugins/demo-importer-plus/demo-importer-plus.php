<?php
/**
 * Plugin Name:     Demo Importer Plus
 * Plugin URI:      https://kraftplugins.com/demo-importer-plus
 * Description:     Demo Importer Plus allows you to Import the demo content, widgets, customizer settings and theme settings with a single click without any hassle.
 * Author:          kraftplugins
 * Author URI:      https://kraftplugins.com/
 * Text Domain:     demo-importer-plus
 * Domain Path:     /languages
 * Version:         1.2.0
 * Tested up to:    6.4
 *
 * @package         Demo Importer Plus
 */

/**
 * Set constants.
 */
if ( !defined( 'DEMO_IMPORTER_PLUS_NAME' ) ) {
	define( 'DEMO_IMPORTER_PLUS_NAME', __( 'Demo Importer Plus', 'demo-importer-plus' ) );
}

if ( !defined( 'DEMO_IMPORTER_PLUS_VER' ) ) {
	define( 'DEMO_IMPORTER_PLUS_VER', '1.2.0' );
}

if ( !defined( 'DEMO_IMPORTER_PLUS_FILE' ) ) {
	define( 'DEMO_IMPORTER_PLUS_FILE', __FILE__ );
}

if ( !defined( 'DEMO_IMPORTER_PLUS_BASE' ) ) {
	define( 'DEMO_IMPORTER_PLUS_BASE', plugin_basename( DEMO_IMPORTER_PLUS_FILE ) );
}

if ( !defined( 'DEMO_IMPORTER_PLUS_DIR' ) ) {
	define( 'DEMO_IMPORTER_PLUS_DIR', plugin_dir_path( DEMO_IMPORTER_PLUS_FILE ) );
}

if ( !defined( 'DEMO_IMPORTER_PLUS_URI' ) ) {
	define( 'DEMO_IMPORTER_PLUS_URI', plugins_url( '/', DEMO_IMPORTER_PLUS_FILE ) );
}

require __DIR__ . '/vendor/autoload.php';

if ( !function_exists( 'DEMO_IMPORTER_PLUS_setup' ) ) :

	/**
	 * DEMO Importer plus Setup
	 *
	 * @since 1.0.0
	 */
	function demo_importer_plus_setup () {
		require_once DEMO_IMPORTER_PLUS_DIR . 'inc/constants.php';
		require_once DEMO_IMPORTER_PLUS_DIR . 'inc/functions.php';
		require_once DEMO_IMPORTER_PLUS_DIR . 'inc/classes/class-demo-importer-plus.php';
		require_once DEMO_IMPORTER_PLUS_DIR . 'inc/classes/class-demo-importer-plus-ajax.php';
		require_once DEMO_IMPORTER_PLUS_DIR . 'inc/classes/class-demo-importer-plus-elementor-page.php';
		require_once DEMO_IMPORTER_PLUS_DIR . 'inc/classes/class-demo-importer-plus-sites-importer.php';
		require_once DEMO_IMPORTER_PLUS_DIR . 'inc/classes/compatibility/class-demo-importer-plus-compatibility-elementor.php';
	}

	add_action( 'after_setup_theme', 'demo_importer_plus_setup' );

endif;

// Demo Importer Plus Notices.
require_once DEMO_IMPORTER_PLUS_DIR . 'admin/notices/class-demo-importer-plus-notices.php';
