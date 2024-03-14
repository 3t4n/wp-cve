<?php

defined( 'ABSPATH' ) or die();

/**
 *  Portfolio Section 
 */
class wl_portfolio_customizer {
	
	public static function wl_enigma_portfolio_customizer( $wp_customize ) {

		$wp_customize->add_section(
	        'portfolio_sec',
	        array(
	            'title' 		  => __('Portfolio Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'enigma_theme_option',
	            'description' 	  => __('Here you can add your Portfolio',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'portfolio_home',
		array(
			'type'    => 'theme_mod',
			'default'=>1,
			'sanitize_callback'=>'enigma_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'enigma_show_portfolio', array(
			'label'        => __( 'Enable Portfolio Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'portfolio_sec',
			'settings'   => 'portfolio_home'
		) );

	    $wp_customize->add_setting(
			'port_heading',
			array(
				'type'              => 'theme_mod',
				'default'           => 'RECENT WORKS',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'enigma_sanitize_text'
			)
		);
		$wp_customize->add_control( 'port_heading', array(
			'label'    => 'Portfolio section title',
			'type'     =>'text',
			'section'  => 'portfolio_sec',
			'settings' => 'port_heading'
		) );

		$wp_customize->selective_refresh->add_partial( 'port_heading', array(
				'selector' => '.enigma_project_section .enigma_heading_title h3',
			) );

	

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/functions/portfolio-functions.php' );
		if ( class_exists( 'enigma_Customizer_portfolio_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'enigma_portfolio',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'enigma_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new enigma_Customizer_portfolio_fields( $wp_customize, 'portfolio_arr', array(
			'type'        => 'text',
			'section'     => 'portfolio_sec',
			'settings'    => 'enigma_portfolio',
			'label'       => __( 'Portfolio', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Portfolio.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'enigma_portfolio_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'enigma_sanitize_text'
			)
		);
		$wp_customize->add_control( 'enigma_portfolio_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'portfolio_sec',
			'settings' => 'enigma_portfolio_data'
		) );

	}
}
?>