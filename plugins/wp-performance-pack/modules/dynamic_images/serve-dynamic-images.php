<?php
/**
 * Serve intermediate images on demand. Is called via mod_rewrite rule.
 * This file is used, when ShortInit is selected as server method.
 * 
 *
 * @author Björn Ahrens
 * @package WP Performance Pack
 * @since 1.1
 */

include( sprintf( "%s/class.wppp_serve_image.php", dirname( __FILE__ ) ) );

class WPPP_Serve_Image_SI extends WPPP_Serve_Image {

	function init( $request ) {
		define( 'SHORTINIT', true );
		parent::init( $request );

		// search and load wp-load.php
		$folder = dirname( __FILE__ );
		while ( $folder != dirname( $folder ) ) {
			if ( file_exists( $folder . '/wp-load.php' ) ) {
				break;
			} else {
				$folder = dirname( $folder );
			}
		}
		require( $folder . '/wp-load.php' ); // will fail if while loop didn't find wp-load.php
		unset( $folder );

		// dummy add_shortcode required for media.php - we don't need any shortcodes so don't load that file and use a dummy instead
		function add_shortcode() {}
		require( ABSPATH . 'wp-includes/media.php' ); // required for image_resize_dimensions
		if ( is_multisite() )
			require ( ABSPATH . 'wp-includes/ms-functions.php' );

		return true;
	}

	function load_wppp() {
		include( sprintf( "%s/wp-performance-pack.php", dirname( dirname( dirname( __FILE__ ) ) ) ) );

		global $wp_performance_pack;
		$this->wppp = $wp_performance_pack;
		$this->wppp->load_options();
		if ( $this->wppp->options[ 'dynamic_images' ] !== true )
			$this->exit404( 'WPPP dynamic images deactivated for this site' );

		if ( !$this->wppp->options[ 'dynamic_images_nosave' ] ) {
			// these are only required if intermediate images get saved

			// required for wp_get_attachment_metadata
			require_once( ABSPATH . 'wp-includes/post.php' );
			require_once( ABSPATH . 'wp-includes/meta.php' );
			require_once( ABSPATH . 'wp-includes/class-wp-post.php' );

			// required for wp_update_attachment_metadata
			require_once( ABSPATH . 'wp-includes/revision.php' );
		}
	}

	function get_local_filename() {
		// formatting.php is more than 120kb big - too big to include for just some small functions, so use copies of needed functions
		// untrailingslashit from wp-includes/formatting.php. is required for get_option
		// trailingslashit and sanitize_key are needed for meta data retrieval
		// wp_basename required when savin file
		if ( !$this->wppp->options[ 'dynamic_images_nosave' ] ) {
			require_once( ABSPATH . 'wp-includes/formatting.php' );
		} else {
			if ( !function_exists( 'untrailingslashit' ) ) {
				function untrailingslashit($string) {
					return rtrim($string, '/');
				}
				function trailingslashit($string) {
					return untrailingslashit($string) . '/';
				}
				function sanitize_key( $key ) {
					$raw_key = $key;
					$key = strtolower( $key );
					$key = preg_replace( '/[^a-z0-9_\-]/', '', $key );
					return apply_filters( 'sanitize_key', $key, $raw_key );
				}
			}
		}
		if ( ! defined( 'WP_CONTENT_URL' ) ) {
			define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
		}
		return parent::get_local_filename();
	}

	function prepare_resize() {
		if ( !$this->wppp->options['dynamic_images_nosave'] ) {
			// test for EWWW Image optimizer
			$plugins = get_option( 'active_plugins' );
			$ewww = false;
			if ( is_array( $plugins ) ) {
				if ( in_array( 'ewww-image-optimizer/ewww-image-optimizer.php', $plugins ) ) {
					$ewww = '/ewww-image-optimizer/ewww-image-optimizer.php';
				} else if ( in_array( 'ewww-image-optimizer-cloud/ewww-image-optimizer-cloud.php', $plugins ) ) {
					$ewww = '/ewww-image-optimizer-cloud/ewww-image-optimizer-cloud.php';
				}
				unset( $plugins );
			}

			if ( $ewww !== false ) {
				// load EWWW IO 
				require_once( ABSPATH . 'wp-includes/default-constants.php' ); // it seems that sometimes something else already includes this, so do require_once to not get redeclaration errors
				wp_plugin_directory_constants();
				wp_load_translations_early();
				$GLOBALS['wp_plugin_paths'] = array();
				wp_register_plugin_realpath( dirname( dirname( __FILE__ ) ) . $ewww );
				include ( dirname( dirname( __FILE__ ) ) . $ewww );
			}
		}
	}
}

$serve = new WPPP_Serve_Image_SI();
$serve->serve_image( $_SERVER[ 'REQUEST_URI' ] );
