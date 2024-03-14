<?php
if ( ! function_exists( 'spiceb_customize_register' ) ) :
	/**
	 * honeypress Customize Register
	 */
	
	function spiceb_customize_register( $wp_customize ) {
		$honeypress_features_content_control = $wp_customize->get_setting( 'honeypress_service_content' );
		if ( ! empty( $honeypress_features_content_control ) ) {
			$honeypress_features_content_control->default = spiceb_honeypress_get_service_default();
		}
	}
	add_action( 'customize_register', 'spiceb_customize_register' );
endif;