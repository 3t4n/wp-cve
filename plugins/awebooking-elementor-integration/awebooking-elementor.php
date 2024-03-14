<?php
/**
 * Plugin Name:     AweBooking & Elementor Integration
 * Plugin URI:      http://awethemes.com/plugins/awebooking
 * Description:     Manage AweBooking shortcodes in Elementor page builder.
 * Author:          awethemes
 * Author URI:      http://awethemes.com
 * Text Domain:     awebooking-elementor
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         AweBooking/Elementor
 */

if ( ! defined( 'ABRS_ELEMENTOR_VERSION' ) ) {
	require trailingslashit( __DIR__ ) . 'vendor/autoload.php';

	/* Constants */
	define( 'ABRS_ELEMENTOR_VERSION', '1.0.0' );
	define( 'ABRS_ELEMENTOR_PLUGIN_FILE', __FILE__ );
	define( 'ABRS_ELEMENTOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'ABRS_ELEMENTOR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

	/* Define the premium constant */
	if ( ! defined( 'ABRS_PREMIUM' ) ) {
		define( 'ABRS_PREMIUM', true );
	}

	/* Init the addon */
	add_action( 'awebooking_init', function( $plugin ) {
		/* @var \AweBooking\Plugin $plugin */
		$plugin->provider( \AweBooking\Elementor\Service_Provider::class );
	});
}
