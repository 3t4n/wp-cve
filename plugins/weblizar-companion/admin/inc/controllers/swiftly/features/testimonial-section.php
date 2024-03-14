<?php

defined( 'ABSPATH' ) or die();

/**
 *  Testimonial Section 
 */
class swiftly_testimonial_customizer {
	
	public static function wl_swiftly_testimonial_customizer( $wp_customize ) {

		$wp_customize->add_section(
	        'testimonial_sec',
	        array(
	            'title' 		  => __('Testimonial Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'enigma_theme_option',
	            'description' 	  => __('Here you can add you Testimonial',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 37,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'testimonial_home',
		array(
			'type'             => 'theme_mod',
			'default'          => 1,
			'sanitize_callback'=>'enigma_sanitize_checkbox',
			'capability'       => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'testimonial_home', array(
			'label'    => __( 'Enable Testimonial Section on Home', WL_COMPANION_DOMAIN ),
			'type'     =>'checkbox',
			'section'  => 'testimonial_sec',
			'settings' => 'testimonial_home'
		) );

	    $wp_customize->add_setting(
			'enigma_testimonial_title',
			array(
				'type'              => 'theme_mod',
				'default'           => 'TESTIMONIES',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'enigma_sanitize_text'
			)
		);
		$wp_customize->add_control( 'enigma_testimonial_title', array(
			'label'    => 'Testimonial Section Title',
			'type'     => 'text',
			'section'  => 'testimonial_sec',
			'settings' => 'enigma_testimonial_title'
		) );

		$wp_customize->add_setting(
			'enigma_testimonial_sub_title',
			array(
				'type'              => 'theme_mod',
				'default'           => 'What Our Client Say',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'enigma_sanitize_text'
			)
		);
		$wp_customize->add_control( 'enigma_testimonial_sub_title', array(
			'label'    => 'Testimonial Section Sub Title',
			'type'     => 'text',
			'section'  => 'testimonial_sec',
			'settings' => 'enigma_testimonial_sub_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'enigma_testimonial_title', array(
				'selector' => '.our-destination .section-title',
		) );

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/swiftly/functions/testimonial-functions.php' );
		if ( class_exists( 'enigma_Customizer_testimonial_fields_new') ) {

			$wp_customize->add_setting(
				'enigma_testimonial',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'enigma_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new enigma_Customizer_testimonial_fields_new( $wp_customize, 'testimonial_arr', array(
			'type'        => 'text',
			'section'     => 'testimonial_sec',
			'settings'    => 'enigma_testimonial',
			'label'       => __( 'Testimonial', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Testimonial members.', WL_COMPANION_DOMAIN ),
			)));
			
		}
		$wp_customize->add_setting(
			'enigma_testimonial_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'enigma_sanitize_text'
			)
		);
		$wp_customize->add_control( 'enigma_testimonial_data', array(
			'label'    => '',
			'type'     => 'hidden',
			'section'  => 'testimonial_sec',
			'settings' => 'enigma_testimonial_data'
		) );


	}
}

?>