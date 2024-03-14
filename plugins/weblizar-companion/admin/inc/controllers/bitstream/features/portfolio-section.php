<?php

defined( 'ABSPATH' ) or die();

/**
 *  Portfolio Section 
 */
class wl_portfolio_customizer {
	
	public static function wl_bitstream_portfolio_customizer( $wp_customize ) {

		$wp_customize->add_section(
	        'portfolio_sec',
	        array(
	            'title' 		  => __('Portfolio Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'bitstream_theme_option',
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
			'default'=>0,
			'sanitize_callback'=>'bitstream_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'bitstream_show_portfolio', array(
			'label'        => __( 'Enable Portfolio Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'portfolio_sec',
			'settings'   => 'portfolio_home'
		) );

	    $wp_customize->add_setting(
			'bitstream_portfolio_title',
			array(
				'type'              => 'theme_mod',
				'default'           => 'Our Projects',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'bitstream_sanitize_text'
			)
		);
		$wp_customize->add_control( 'bitstream_portfolio_title', array(
			'label'    => 'Portfolio section title',
			'type'     =>'text',
			'section'  => 'portfolio_sec',
			'settings' => 'bitstream_portfolio_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'bitstream_portfolio_title', array(
			'selector' => '.our-project-section .section-heading h2',
		) );

		if ( class_exists( 'One_Page_Editor') ) {

			$wp_customize->add_setting(
				'bitstream_portfolio_desc',
				array(
					'type'              => 'theme_mod',
					'default'           => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridi culus mus.',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'bitstream_sanitize_text'
				)
			);

			$wp_customize->add_control(new One_Page_Editor($wp_customize, 'bitstream_portfolio_desc', array(
				'label'                      => __( 'Portfolio Section Description', WL_COMPANION_DOMAIN ),
				'active_callback'            => 'show_on_front',
				'section'                    => 'portfolio_sec',
				'settings'                   => 'bitstream_portfolio_desc'
			) ));
		}

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/functions/portfolio-functions.php' );
		if ( class_exists( 'bitstream_Customizer_portfolio_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'bitstream_portfolio',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'bitstream_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new bitstream_Customizer_portfolio_fields( $wp_customize, 'portfolio_arr', array(
			'type'        => 'text',
			'section'     => 'portfolio_sec',
			'settings'    => 'bitstream_portfolio',
			'label'       => __( 'Portfolio', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Portfolio.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'bitstream_portfolio_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'bitstream_sanitize_text'
			)
		);
		$wp_customize->add_control( 'bitstream_portfolio_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'portfolio_sec',
			'settings' => 'bitstream_portfolio_data'
		) );
	}
}
?>