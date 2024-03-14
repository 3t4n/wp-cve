<?php
if ( ! function_exists( 'icyclub_customize_register' ) ) :
	/**
	 * consultco Customize Register
	 */
	
	function icyclub_customize_register( $wp_customize ) {
		$consultco_features_content_control = $wp_customize->get_setting( 'consultco_service_content' );
		if ( ! empty( $consultco_features_content_control ) ) {
			$consultco_features_content_control->default = icycp_consultco_get_service_default();
		}
	}
	add_action( 'customize_register', 'icyclub_customize_register' );
endif;