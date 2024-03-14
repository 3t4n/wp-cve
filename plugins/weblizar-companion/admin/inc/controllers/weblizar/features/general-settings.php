<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_general_customizer {
	
	public static function wl_wl_general_customizer( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.nav-brand .site-title',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
		) );
		$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector' => '.navbar-brand img',
		) );

		$wp_customize->add_panel( 'weblizar_theme_option', 
			array( 'title'      => esc_html__( 'Weblizar Options', WL_COMPANION_DOMAIN ), 
				   'priority'   => 1,
				   'capability' => 'edit_theme_options',
				)
		);

		// general Settings start
		$wp_customize->add_section('general_sec',	
			array( 'title'       => esc_html__( 'Theme General Options', WL_COMPANION_DOMAIN ),
				   'panel'       =>'weblizar_theme_option',
		           'description' => esc_html__('Here you can manage General Options(Like:- Custom Css etc.)', WL_COMPANION_DOMAIN),
				   'capability'  =>'edit_theme_options',
		           'priority'    => 32,
	        ));

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/functions/general-functions.php' );
		if ( class_exists( 'weblizar_Customizer_Range_Value_Control') ) {

			// logo height width //
			$wp_customize->add_setting(
				'logo_height',
				array(
					'type'              => 'theme_mod',
					'default'           => 55,
					'sanitize_callback' => 'weblizar_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new weblizar_Customizer_Range_Value_Control( $wp_customize, 'logo_height', array(
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
					'sanitize_callback' => 'weblizar_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);	

			$wp_customize->add_control( new weblizar_Customizer_Range_Value_Control( $wp_customize, 'logo_width', array(
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

	

		$wp_customize->add_setting(
			'sticky_header',
			array(
				'type'              => 'theme_mod',
				'default'           => '0',
				'sanitize_callback' => 'weblizar_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'sticky_header', array(
			'label'    => __( 'Sticky header on/off', WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'sticky_header',
		) );

		$wp_customize->add_setting(	'web_mail', array(
			'default'			=>__( 'info@gmail.com', WL_COMPANION_DOMAIN ),
			'type'				=>'theme_mod',
			'capability'		=>'edit_theme_options',
			'sanitize_callback' =>'weblizar_sanitize_text',
			)
		);
		$wp_customize->add_control( 'web_mail', array(
			'label'      => __( 'Email', 'weblizar' ),
			'type'		 =>'text',
			'section'    => 'general_sec',
			'settings'   => 'web_mail'
		) );
		
		$wp_customize->add_setting(	'web_phone', array(
			'default'			=>__( '1234567890', WL_COMPANION_DOMAIN ),
			'type'				=>'theme_mod',
			'capability'		=>'edit_theme_options',
			'sanitize_callback' =>'weblizar_sanitize_text',
			)
		);
		$wp_customize->add_control( 'web_phone', array(
			'label'      => __( 'Phone', WL_COMPANION_DOMAIN ),
			'type'		 =>'text',
			'section'    => 'general_sec',
			'settings'   => 'web_phone'
		) );

		$wp_customize->add_setting(
			'search_form',
			array(
				'type'    			=> 'theme_mod',
				'default'			=>' ',
				'sanitize_callback' =>'weblizar_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		
		$wp_customize->add_control( 'search_form', array(
			'label'      => __( 'Enable Search For Header',WL_COMPANION_DOMAIN ),
			'type'		 =>'checkbox',
			'section'    => 'general_sec',
			'settings'   => 'search_form',
		) );
	
		//sanitize callbacks
		function weblizar_sanitize_text( $input ) {
	    	return wp_kses_post( force_balance_tags( $input ) );
		}
	 	function weblizar_sanitize_checkbox( $input ) {
		   if ( $input == 1 ) {
				return 1 ;
			} else {
				return 0;
			}
		}
		function weblizar_sanitize_integer( $input ) {
			return (int)($input);
		}
		function weblizar_sanitize_js( $input ) {
			return base64_encode($input);
		}
		function weblizar_sanitize_js_output( $input ) {
			return base64_decode($input);
		}
	}
}