<?php
/**
 * Serve intermediate images on demand. Is called via mod_rewrite rule.
 *
 * @author Björn Ahrens
 * @package WP Performance Pack
 * @since 1.1
 */

include( sprintf( "%s/class.wppp_serve_image.php", dirname( __FILE__ ) ) );

class WPPP_Serve_Image_UT extends WPPP_Serve_Image {

	function init( $request ) {
		define( 'WP_USES_THEMES', false );
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

		return true;
	}

	function load_wppp() {
		global $wp_performance_pack;
		$this->wppp = $wp_performance_pack;
		$this->wppp->load_options();
		if ( $this->wppp->options[ 'dynamic_images' ] !== true )
			$this->exit404( 'WPPP dynamic images deactivated for this site' );
	}
}

$serve = new WPPP_Serve_Image_UT();
$serve->serve_image(  $_SERVER[ 'REQUEST_URI' ] );
