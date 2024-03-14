<?php
if ( ! function_exists( 'icyclub_customize_register' ) ) :
	/**
	 * consultco Customize Register
	 */
	
	function icyclub_customize_register( $wp_customize ) {
		$industryup_features_content_control = $wp_customize->get_setting( 'industryup_service_content' );
		if ( ! empty( $industryup_features_content_control ) ) {
			$industryup_features_content_control->default = icycp_industryup_get_service_default();
		}
	}
	add_action( 'customize_register', 'icyclub_customize_register' );
endif;