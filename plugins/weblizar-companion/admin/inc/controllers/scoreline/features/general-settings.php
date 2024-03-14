<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_general_customizer {
	
	public static function wl_scoreline_general_customizer( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.nav-brand .site-title',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'' => '.site-description',
		) );
		$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector' => '.navbar-brand img',
		) );

		$wp_customize->add_panel( 'scoreline_theme_option', 
			array( 'title'      => esc_html__( 'Scoreline Theme Options', WL_COMPANION_DOMAIN ), 
				   'priority'   => 1,
				   'capability' => 'edit_theme_options',
				)
		);

		// general Settings start
		$wp_customize->add_section('general_sec',	
			array( 'title'       => esc_html__( 'Theme General Options', WL_COMPANION_DOMAIN ),
				   'panel'       =>'scoreline_theme_option',
		           'description' => esc_html__('Here you can manage General Options', WL_COMPANION_DOMAIN),
				   'capability'  =>'edit_theme_options',
		           'priority'    => 32,
	        ));

		//search form
		$wp_customize->add_setting(
			'sticky_header',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'scoreline_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'sticky_header', array(
			'label'    => __( 'Enable Sticky Header',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'sticky_header',
		) );
		
		//scroll up button option
		$wp_customize->add_setting(
			'scoreline_return_top',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'scoreline_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'scoreline_return_top', array(
			'label'    => __( 'Enable scroll up button for the site',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'scoreline_return_top',
		) );
		

		$wp_customize->selective_refresh->add_partial( 'scoreline_return_top', array(
			'selector' => 'a#btn-to-top',
		) );
		//sanitize callbacks
		function scoreline_sanitize_text( $input ) {
	    	return wp_kses_post( force_balance_tags( $input ) );
		}
	 	function scoreline_sanitize_checkbox( $input ) {
		   if ( $input == 1 ) {
				return 1 ;
			} else {
				return 0;
			}
		}
		function scoreline_sanitize_integer( $input ) {
			return (int)($input);
		}
		function scoreline_sanitize_js( $input ) {
			return base64_encode($input);
		}
		function scoreline_sanitize_js_output( $input ) {
			return base64_decode($input);
		}
	}
}