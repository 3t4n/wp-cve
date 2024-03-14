<?php
/*
Plugin Name: WPFactory Helper
Plugin URI: https://wpfactory.com/
Description: Plugin helps you manage subscriptions for your products from WPFactory.com.
Version: 1.5.9
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: wpcodefactory-helper
Domain Path: /langs
*/

defined( 'ABSPATH' ) || exit;

defined( 'ALG_WPCODEFACTORY_HELPER_UPDATE_SERVER' ) || define( 'ALG_WPCODEFACTORY_HELPER_UPDATE_SERVER', 'https://wpfactory.com' );

defined( 'ALG_WPCODEFACTORY_HELPER_VERSION' ) || define( 'ALG_WPCODEFACTORY_HELPER_VERSION', '1.5.9' );

defined( 'ALG_WPCODEFACTORY_HELPER_FILE' ) || define( 'ALG_WPCODEFACTORY_HELPER_FILE', __FILE__ );

require_once( 'includes/class-alg-wpcodefactory-helper.php' );

if ( ! function_exists( 'alg_wpcodefactory_helper' ) ) {
	/**
	 * Returns the main instance of Alg_WPCodeFactory_Helper to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wpcodefactory_helper() {
		return Alg_WPCodeFactory_Helper::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wpcodefactory_helper' );
