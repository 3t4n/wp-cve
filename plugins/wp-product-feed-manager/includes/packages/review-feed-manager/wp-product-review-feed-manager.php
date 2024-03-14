<?php
/**
 * Adds the option to generate Google Product Review Feeds in WooCommerce Product Feed Manager
 * @package WP-Product-Review-Feed-Manager
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only activate wpprfm support if all requirements are met.
if ( wpprfm_prerequisites() ) {
	wpprfm_define_constants();

	wpprfm_includes();
} else {
	if ( function_exists( 'wppfm_show_wp_error' ) ) {
		wppfm_show_wp_error( __( 'You need to update your Feed Manager plugin to the latest version in order to use the Google Review Feed add-on.', 'wp-product-review-feed-manager' ) );
	}
}

/**
 * Checks if all required plugins are installed and active.
 *
 * Required are minimum version 3.0.0 for the premium versions and 2.0.0 for the free one.
 */
function wpprfm_prerequisites() {
	if ( ( 'free' !== WPPFM_PLUGIN_VERSION_ID )
		 && version_compare( WPPFM_VERSION_NUM, '3.0.0', '>=' ) ) {
		return true;
	} elseif ( 'free' === WPPFM_PLUGIN_VERSION_ID
			   && version_compare( WPPFM_VERSION_NUM, '2.0.0', '>=' ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Includes all required files and classes.
 */
function wpprfm_includes() {

	// Do not load the other scripts unless a wppfm page is on.
	if ( ! wppfm_on_own_main_plugin_page() ) {
		return;
	}

	require_once __DIR__ . '/wpprfm-review-feed-form-functions.php';
	require_once __DIR__ . '/wpprfm-setup-feed-manager.php';
	require_once __DIR__ . '/wpprfm-include-classes-functions.php';
	require_once __DIR__ . '/wpprfm-feed-generation-functions.php';

	// Include the traits.
	require_once __DIR__ . '/traits/wpprfm-processing-support.php';
	require_once __DIR__ . '/traits/wpprfm-xml-element-functions.php';

	// Include the required classes.
	wpprfm_include_classes();
}

/**
 * Define the required constants.
 */
function wpprfm_define_constants() {
	// Store the name of the package.
	if ( ! defined( 'WPPRFM_PACKAGE_NAME' ) ) {
		define( 'WPPRFM_PACKAGE_NAME', 'review-feed-manager' );
	}

	// Store the url to the package.
	if ( ! defined( 'WPPRFM_PACKAGE_URL' ) ) {
		define( 'WPPRFM_PACKAGE_URL', WPPFM_PLUGIN_URL . '/includes/packages/' . WPPRFM_PACKAGE_NAME );
	}

	if ( ! defined( 'WPPRFM_FEED_VERSION' ) ) {
		define( 'WPPRFM_FEED_VERSION', '2.2' );
	}
}

/**
 * Include the background classes.
 */
function wpprfm_include_background_classes() {
	require_once __DIR__ . '/traits/wpprfm-processing-support.php';
	require_once __DIR__ . '/traits/wpprfm-xml-element-functions.php';

	if ( ! class_exists( 'WPPRFM_Review_Feed_Processor' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-review-feed-processor.php';
	}

	if ( ! class_exists( 'WPPRFM_Attributes_List' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-attributes-list.php';
	}
}

add_action( 'wppfm_includes', 'wpprfm_include_background_classes' );
