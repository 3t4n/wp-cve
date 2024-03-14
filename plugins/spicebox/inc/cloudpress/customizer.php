<?php
if ( ! function_exists( 'spiceb_cloudpress_customize_register' ) ) :
	/**
	 * cloudpress Customize Register
	 */

	function spiceb_cloudpress_customize_register( $wp_customize ) {
		$cloudpress_features_content_control = $wp_customize->get_setting( 'cloudpress_service_content' );
		if ( ! empty( $cloudpress_features_content_control ) ) {
			$cloudpress_features_content_control->default = spiceb_cloudpress_get_service_default();
		}
	}
	add_action( 'customize_register', 'spiceb_cloudpress_customize_register' );
endif;


if ( ! function_exists( 'spiceb_cloudpress_fun_customize_register' ) ) :
	/**
	 * cloudpress Customize Register
	 */

	function spiceb_cloudpress_fun_customize_register( $wp_customize ) {
		$cloudpress_features_content_controls = $wp_customize->get_setting( 'cloudpress_funfact_content' );
		if ( ! empty( $cloudpress_features_content_controls ) ) {
			$cloudpress_features_content_controls->default = spiceb_cloudpress_get_funfact_default();
		}
	}
	add_action( 'customize_register', 'spiceb_cloudpress_fun_customize_register' );
endif;
