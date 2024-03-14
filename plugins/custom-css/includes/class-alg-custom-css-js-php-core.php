<?php
/**
 * Custom CSS, JS & PHP - Core Class
 *
 * @version 2.2.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_Custom_CSS_JS_PHP_Core' ) ) :

class Alg_Custom_CSS_JS_PHP_Core {

	/**
	 * Constructor.
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 *
	 * @todo    [next] (feature) add option to set "custom CSS / JS" on per product basis (i.e. single product page)
	 * @todo    [next] (feature) CSS and JS minimization
	 */
	function __construct() {
		// CSS & JS
		foreach ( array( 'css', 'js' ) as $css_or_js ) {
			foreach ( array( 'front', 'back' ) as $front_or_back ) {
				if ( 'yes' === get_alg_ccjp_option( "{$front_or_back}_end_{$css_or_js}_enabled", 'no' ) && '' != get_alg_ccjp_option( "{$front_or_back}_end_{$css_or_js}", '' ) ) {
					add_action( ( 'front' === $front_or_back ? 'wp' : 'admin' ) . '_' . get_alg_ccjp_option( "{$front_or_back}_end_{$css_or_js}_position", 'head' ),
						array( $this, "hook_custom_{$css_or_js}_{$front_or_back}_end" ), PHP_INT_MAX );
				}
			}
		}
		// PHP
		if ( 'yes' === get_alg_ccjp_option( 'php_enabled', 'no' ) ) {
			$this->run_custom_php();
		}
	}

	/**
	 * get_custom_php_file_path.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_custom_php_file_path( $do_mkdir = false, $do_return_dir_only = false ) {
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'alg-custom-php';
		if ( $do_mkdir && ! file_exists( $upload_dir ) ) {
			mkdir( $upload_dir, 0755, true );
		}
		return ( $do_return_dir_only ? $upload_dir : $upload_dir . DIRECTORY_SEPARATOR . 'custom-php.php' );
	}

	/**
	 * run_custom_php.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function run_custom_php() {
		// Check for custom PHP execution stops
		if ( isset( $_GET['alg_disable_custom_php'] ) ) {
			// Maybe stop custom PHP execution on `alg_disable_custom_php`
			if ( ! function_exists( 'wp_get_current_user' ) || ! function_exists( 'is_user_logged_in' ) ) {
				include( ABSPATH . 'wp-includes/pluggable.php' );
			}
			if ( current_user_can( 'manage_woocommerce' ) ) {
				// Stop custom PHP execution
				return;
			} elseif ( ! is_user_logged_in() ) {
				// Redirect to login page
				wp_redirect( wp_login_url( add_query_arg( '', '' ) ) );
				exit;
			}
		}
		if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
			// Stop custom PHP execution if it's the login page
			return;
		}
		// Executing custom PHP code
		$file_path = $this->get_custom_php_file_path();
		if ( file_exists( $file_path ) ) {
			include_once( $file_path );
		}
	}

	/**
	 * hook_custom_js_front_end.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function hook_custom_js_front_end() {
		echo '<script>' . get_alg_ccjp_option( 'front_end_js', '' ) . '</script>';
	}

	/**
	 * hook_custom_js_back_end.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function hook_custom_js_back_end() {
		echo '<script>' . get_alg_ccjp_option( 'back_end_js', '' ) . '</script>';
	}

	/**
	 * hook_custom_css_front_end.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function hook_custom_css_front_end() {
		echo '<style>' . get_alg_ccjp_option( 'front_end_css', '' ) . '</style>';
	}

	/**
	 * hook_custom_css_back_end.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function hook_custom_css_back_end() {
		echo '<style>' . get_alg_ccjp_option( 'back_end_css', '' ) . '</style>';
	}

}

endif;

return new Alg_Custom_CSS_JS_PHP_Core();
