<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_general_customizer {
	
	public static function wl_explora_general_customizer( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.nav-brand .site-title',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'' => '.site-description',
		) );
		$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector' => '.navbar-brand img',
		) );

		$wp_customize->add_panel( 'explora_theme_option', 
			array( 'title'      => esc_html__( 'Explora Theme Options', WL_COMPANION_DOMAIN ), 
				   'priority'   => 1,
				   'capability' => 'edit_theme_options',
				)
		);

		// general Settings start
		$wp_customize->add_section('general_sec',	
			array( 'title'       => esc_html__( 'Theme General Options', WL_COMPANION_DOMAIN ),
				   'panel'       =>'explora_theme_option',
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
				'sanitize_callback' => 'explora_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'sticky_header', array(
			'label'    => __( 'Enable Sticky Header',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'sticky_header',
		) );

		//search form
		$wp_customize->add_setting(
			'explora_search',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'explora_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'explora_search', array(
			'label'    => __( 'Enable search form for header',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'explora_search',
		) );
		
		//scroll up button option
		$wp_customize->add_setting(
			'explora_return_top',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'explora_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'explora_return_top', array(
			'label'    => __( 'Enable scroll up button for the site',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'explora_return_top',
		) );
		

		$wp_customize->selective_refresh->add_partial( 'explora_return_top', array(
			'selector' => 'a#btn-to-top',
		) );
		//sanitize callbacks
		function explora_sanitize_text( $input ) {
	    	return wp_kses_post( force_balance_tags( $input ) );
		}
	 	function explora_sanitize_checkbox( $input ) {
		   if ( $input == 1 ) {
				return 1 ;
			} else {
				return 0;
			}
		}
		function explora_sanitize_integer( $input ) {
			return (int)($input);
		}
		function explora_sanitize_js( $input ) {
			return base64_encode($input);
		}
		function explora_sanitize_js_output( $input ) {
			return base64_decode($input);
		}
	}
}