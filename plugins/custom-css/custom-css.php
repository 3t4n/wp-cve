<?php
/*
Plugin Name: Custom CSS, JS & PHP
Plugin URI: https://wpfactory.com
Description: Just another custom CSS, JavaScript & PHP tool for WordPress.
Version: 2.2.1
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: custom-css
Domain Path: /langs
*/

defined( 'ABSPATH' ) || exit;

defined( 'ALG_CCJP_VERSION' ) || define( 'ALG_CCJP_VERSION', '2.2.1' );

defined( 'ALG_CCJP_ID' ) || define( 'ALG_CCJP_ID', 'alg_custom_css' ); // Should be named `alg_ccjp`, but is named `alg_custom_css` for backwards compatibility

defined( 'ALG_CCJP_PLUGIN_FILE' ) || define( 'ALG_CCJP_PLUGIN_FILE', __FILE__ );

require_once( 'includes/class-alg-custom-css-js-php.php' );

if ( ! function_exists( 'get_alg_ccjp_option' ) ) {
	/**
	 * get_alg_ccjp_option.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function get_alg_ccjp_option( $option, $default = false ) {
		return get_option( ALG_CCJP_ID . '_' . $option, $default );
	}
}

if ( ! function_exists( 'alg_ccjp' ) ) {
	/**
	 * Returns the main instance of Alg_CCJP to prevent the need to use globals.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function alg_ccjp() {
		return Alg_CCJP::instance();
	}
}

add_action( 'plugins_loaded', 'alg_ccjp' );
