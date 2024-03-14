<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_general_customizer {
	
	public static function wl_nineteen_general_customizer( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.navbar-brand .site-title',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
		) );
		$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector' => '.navbar-brand img',
		) );

		$wp_customize->add_panel( 'nineteen_theme_option', 
			array( 'title'      => esc_html__( 'Theme Options', WL_COMPANION_DOMAIN ), 
				   'priority'   => 1,
				   'capability' => 'edit_theme_options',
				)
		);

		// general Settings start
		$wp_customize->add_section('general_sec',	
			array( 'title'       => esc_html__( 'General Options', WL_COMPANION_DOMAIN ),
				   'panel'       =>'nineteen_theme_option',
		           'description' => esc_html__('Here you can manage General Options(Like:- Custom Css etc.)', WL_COMPANION_DOMAIN),
				   'capability'  =>'edit_theme_options',
		           'priority'    => 32,
	        ));

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/functions/general-functions.php' );
		if ( class_exists( 'nineteen_Customizer_Range_Value_Control') ) {

			// logo height width //
			$wp_customize->add_setting(
				'nineteen_logo_heigth',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'nineteen_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new nineteen_Customizer_Range_Value_Control( $wp_customize, 'logo_height', array(
			'type'        => 'range-value',
			'section'     => 'general_sec',
			'settings'    => 'nineteen_logo_heigth',
			'label'       => __( 'Logo Height', WL_COMPANION_DOMAIN ),
			'input_attrs' => array(
				'min'     => 1,
				'max'     => 500,
				'step'    => 1,
				'suffix'  => 'px', //optional suffix
		  	),
			)));
			
			$wp_customize->add_setting(
				'nineteen_logo_width',
				array(
					'type'              => 'theme_mod',
					'default'           => 200,
					'sanitize_callback' => 'nineteen_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);	

			$wp_customize->add_control( new nineteen_Customizer_Range_Value_Control( $wp_customize, 'logo_width', array(
			'type'        => 'range-value',
			'section'     => 'general_sec',
			'settings'    => 'nineteen_logo_width',
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
		
		$wp_customize->add_setting(
			'nineteen_search_box',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'nineteen_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'nineteen_search_box', array(
			'label'    => __( 'Enable Search Box on Homepage',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'nineteen_search_box',
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_search_box', array(
			'selector' => '.search_form.flex-row i.fa.fa-search',
		) );
		
		//scroll up button option
		$wp_customize->add_setting(
			'nineteen_return_top',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'nineteen_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'nineteen_return_top', array(
			'label'    => __( 'Disable scroll up button for the site',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'nineteen_return_top',
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_return_top', array(
			'selector' => 'a#return-to-top',
		) );
		//sanitize callbacks
		function nineteen_sanitize_text( $input ) {
	    	return wp_kses_post( force_balance_tags( $input ) );
		}
	 	function nineteen_sanitize_checkbox( $input ) {
		   if ( $input == 1 ) {
				return 1 ;
			} else {
				return 0;
			}
		}
		function nineteen_sanitize_integer( $input ) {
			return (int)($input);
		}
		function nineteen_sanitize_js( $input ) {
			return base64_encode($input);
		}
		function nineteen_sanitize_js_output( $input ) {
			return base64_decode($input);
		}
	}
}