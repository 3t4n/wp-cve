<?php

/**
 * Description: Adds the option to generate Google Merchant Promotions Feeds in WooCommerce Product Feed Manager
 * Version: 1.0.0
 * Modified: 11-03-2023
 * Author: Michel Jongbloed
 * Author URI: https://www.wpmarketingrobot.com
 *
 * @since 2.39.0
 * @package WP-Product-Merchant-Promotions-Feed-Manager
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only activate wpprfm support if all requirements are met.
if ( wpppfm_prerequisites() ) {
	$package_version = '1.0.0';

	wpppfm_define_constants( $package_version );

	wpppfm_includes();
} else {
	if ( function_exists( 'wppfm_show_wp_error' ) ) {
		wppfm_show_wp_error( __( 'You need at least version 2.39.0 of the WooCommerce Product Feed Manager plugin to use the WooCommerce Google Review Feed Manager add-on', 'wp-product-review-feed-manager' ) );
	}
}

/**
 * Checks if all required plugins are installed and active.
 *
 * Required are minimum version 3.0.0 for the premium versions and 2.0.0 for the free one.
 */
function wpppfm_prerequisites(): bool {
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
function wpppfm_includes() {

	// Do not load the other scripts unless a wppfm page is on.
	if ( ! wppfm_on_own_main_plugin_page() ) {
		return;
	}

	require_once __DIR__ . '/wpppfm-include-classes-functions.php';
	require_once __DIR__ . '/wpppfm-promotions-feed-form-functions.php';
	require_once __DIR__ . '/wpppfm-setup-feed-manager.php';

	// Include the traits.
	require_once __DIR__ . '/traits/wpppfm-product-details-selector-box.php';

	// Include the required classes.
	wpppfm_include_classes();
}

/**
 * Define the required constants.
 */
function wpppfm_define_constants( $package_version ) {
	// Store the version of this package.
	if ( ! defined( 'WPPPFM_PACKAGE_VERSION' ) ) {
		define( 'WPPPFM_PACKAGE_VERSION', $package_version );
	}

	// Store the name of the package.
	if ( ! defined( 'WPPPFM_PACKAGE_NAME' ) ) {
		define( 'WPPPFM_PACKAGE_NAME', 'promotions-feed-manager' );
	}

	// Store the url to the package.
	if ( ! defined( 'WPPPFM_PACKAGE_URL' ) ) {
		define( 'WPPPFM_PACKAGE_URL', WPPFM_PLUGIN_URL . '/includes/packages/' . WPPPFM_PACKAGE_NAME );
	}
}

/**
 * Include the background classes.
 */
function wpppfm_include_background_classes() {
	require_once __DIR__ . '/traits/wpppfm-processing-support.php';
	require_once __DIR__ . '/traits/wpppfm-xml-element-functions.php';

	if ( ! class_exists( 'WPPPFM_Promotions_Feed_Processor' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-promotions-feed-processor.php';
	}

	if ( ! class_exists( 'WPPPFM_Attributes_List' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-attributes-list.php';
	}
}

add_action( 'wppfm_includes', 'wpppfm_include_background_classes' );
