<?php
/**
 * Theme Option Customizer Settings  
 * 
 * 
 */

if ( ! class_exists( 'Aqwa_Theme_General' ) ) {

	class Aqwa_Theme_General{

		public function __construct() {

			add_action( 'customize_register', array( $this, 'breadcrumb_settings' ) );
			
		}

		public function breadcrumb_settings( $wp_customize ) {			

			if ( class_exists( 'Amigo_Extensions_Range_Control' ) ) {
				$wp_customize->add_setting('aqwa_breadcrumb_min_height',
					array(
						'default'     	=> '420',
						'capability'     	=> 'edit_theme_options',
						'sanitize_callback' => 'aqwa_sanitize_range',
						'transport'         => 'postMessage',						
					)
				);
				$wp_customize->add_control( 
					new Amigo_Extensions_Range_Control( $wp_customize, 'aqwa_breadcrumb_min_height', 
						array(
							'label'      => __( 'Min Height', 'amigo-extensions'),
							'section'  => 'breadcrumb_section',
							'priority' => 2,
							'input_attrs' => array(
								'min'    => 1,
								'step'   => 1,
								'max'    => 1000,		
								
							),
						) ) 
				);
			}	

		}

	}

	new Aqwa_Theme_General();
}