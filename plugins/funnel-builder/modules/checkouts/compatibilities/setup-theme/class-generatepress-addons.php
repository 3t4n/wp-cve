<?php

/**
 * Generate Press GP plugin compatibility
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_GP_PLUGIN
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_GP_PLUGIN {

	public function __construct() {
		add_action( 'customize_register', [ $this, 'wfacp_temp_remove_controls' ], 1500 );
	}

	/**
	 * @param $wp_customize WP_Customize_Manager
	 */
	public function wfacp_temp_remove_controls( $wp_customize ) {
		if ( ( class_exists( 'GeneratePress_Pro_Typography_Customize_Control' ) || class_exists( 'Generate_Typography_Customize_Control' ) ) && class_exists( 'WFACP_Common' ) && WFACP_Common::is_customizer() ) {
			$all_controls = $wp_customize->controls();
			foreach ( $all_controls as $id => $control ) {
				if ( ( $control instanceof GeneratePress_Pro_Typography_Customize_Control ) || ( $control instanceof Generate_Typography_Customize_Control ) ) {
					$wp_customize->remove_control( $id );
				}
			}
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_GP_PLUGIN(), 'GP_PLUGIN' );