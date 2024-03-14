<?php
/**
 * Plugin Name: Supreme Maps
 * Version: 1.0.9
 * Plugin URI: https://divisupreme.com/supreme-maps-for-divi/
 * Description: Display beautiful and mobile-friendly interactive maps for your Divi websites.
 * Author: Divi Supreme
 * Author URI: http://www.divisupreme.com/
 * Requires at least: 5.6
 * Tested up to: 5.8.2
 *
 * Text Domain: supreme-maps
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Divi Supreme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'supreme-maps-pro/supreme-maps-pro.php' ) ) {
	return;
}

// Load plugin class files.
require_once 'includes/class-supreme-maps.php';
require_once 'includes/class-supreme-maps-settings.php';

// Load plugin libraries.
require_once 'includes/lib/class-supreme-maps-admin-api.php';
require_once 'includes/lib/class-supreme-maps-post-type.php';
require_once 'includes/lib/class-supreme-maps-taxonomy.php';

/**
 * Returns the main instance of Supreme_Maps to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Supreme_Maps
 */
function supreme_maps() {
	$instance = Supreme_Maps::instance( __FILE__, '1.0.9' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Supreme_Maps_Settings::instance( $instance );
	}

	return $instance;
}

supreme_maps();
