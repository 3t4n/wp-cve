<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_general_customizer {
	
	public static function wl_guardian_general_customizer( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.nav-brand .site-title',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'' => '.site-description',
		) );
		$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector' => '.navbar-brand img',
		) );

		$wp_customize->add_panel( 'guardian_theme_option', 
			array( 'title'      => esc_html__( 'Guardian Theme Options', WL_COMPANION_DOMAIN ), 
				   'priority'   => 1,
				   'capability' => 'edit_theme_options',
				)
		);

		// general Settings start
		$wp_customize->add_section('general_sec',	
			array( 'title'       => esc_html__( 'Theme General Options', WL_COMPANION_DOMAIN ),
				   'panel'       =>'guardian_theme_option',
		           'description' => esc_html__('Here you can manage General Options(Like:- Custom Css etc.)', WL_COMPANION_DOMAIN),
				   'capability'  =>'edit_theme_options',
		           'priority'    => 32,
	        ));

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/functions/general-functions.php' );
		if ( class_exists( 'guardian_Customizer_Range_Value_Control') ) {

			// logo height width //
			$wp_customize->add_setting(
				'logo_height',
				array(
					'type'              => 'theme_mod',
					'default'           => 55,
					'sanitize_callback' => 'guardian_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new guardian_Customizer_Range_Value_Control( $wp_customize, 'logo_height', array(
					'type'        => 'range-value',
					'section'     => 'general_sec',
					'settings'    => 'logo_height',
					'label'       => __( 'Logo Height', WL_COMPANION_DOMAIN ),
					'input_attrs' => array(
						'min'     => 1,
						'max'     => 500,
						'step'    => 1,
						'suffix'  => 'px', //optional suffix
				  	),
				)
			));
			
			$wp_customize->add_setting(
				'logo_width',
				array(
					'type'              => 'theme_mod',
					'default'           => 150,
					'sanitize_callback' => 'guardian_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);	

			$wp_customize->add_control( new guardian_Customizer_Range_Value_Control( $wp_customize, 'logo_width', array(
				'type'        => 'range-value',
				'section'     => 'general_sec',
				'settings'    => 'logo_width',
				'label'       => __('Logo Width', WL_COMPANION_DOMAIN ),
				'input_attrs' => array(
					'min'     => 1,
					'max'     => 310,
					'step'    => 1,
					'suffix'  => 'px', //optional suffix
			  	),
			)));

			// logo height width //
		}
		
		//scroll up button option
		$wp_customize->add_setting(
			'guardian_header_top',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'guardian_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'guardian_header_top', array(
			'label'    => __( 'Enable top header',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'guardian_header_top',
		) );
		
		
		//scroll up button option
		$wp_customize->add_setting(
			'guardian_return_top',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'guardian_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'guardian_return_top', array(
			'label'    => __( 'Enable scroll up button for the site',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'guardian_return_top',
		) );

		$wp_customize->selective_refresh->add_partial( 'guardian_return_top', array(
			'selector' => '.back-to-top',
		) );
		//sanitize callbacks
		function guardian_sanitize_text( $input ) {
	    	return wp_kses_post( force_balance_tags( $input ) );
		}
	 	function guardian_sanitize_checkbox( $input ) {
		   if ( $input == 1 ) {
				return 1 ;
			} else {
				return 0;
			}
		}
		function guardian_sanitize_integer( $input ) {
			return (int)($input);
		}
		function guardian_sanitize_js( $input ) {
			return base64_encode($input);
		}
		function guardian_sanitize_js_output( $input ) {
			return base64_decode($input);
		}
	}
}