<?php
/**
 * Freemius helper function for easy SDK access. 
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'lswss_fs' ) ) {

	// Create a helper function for easy SDK access.
	function lswss_fs() {

		global $lswss_fs ;

		if ( ! isset( $lswss_fs ) ) {

			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$lswss_fs = fs_dynamic_init( array(
				'id'				=> '6734',
				'slug'				=> 'logo-showcase-with-slick-slider',
				'premium_slug'		=> 'logo-showcase-with-slick-slider-pro',
				'type'				=> 'plugin',
				'public_key'		=> 'pk_c471cf965a589c625df71e719a53e',
				'is_premium'		=> false,
				'premium_suffix'	=> 'Pro',
				'has_addons'		=> false,
				'has_paid_plans'	=> true,
				'menu'				=> array(
										'slug'			=> 'edit.php?post_type=lswss_gallery',
										'first-path'	=> 'edit.php?post_type=lswss_gallery',
										'support'		=> false,
									),
				'is_live'			=> true,
			) );
		}

		return $lswss_fs;
	}

	// Init Freemius.
	lswss_fs();

	// Signal that SDK was initiated.
	do_action( 'lswss_fs_loaded' );
}