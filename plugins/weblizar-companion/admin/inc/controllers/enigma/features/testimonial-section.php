<?php

defined( 'ABSPATH' ) or die();

/**
 *  testimonial Section 
 */
class wl_testimonial_customizer {
	
	public static function wl_enigma_testimonial_customizer( $wp_customize ) {

		/* testimonial Section */
		$wp_customize->add_section(
	        'testimonial_sec',
	        array(
	            'title' 		  => __('Testimonial Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'enigma_theme_option',
	            'description' 	  => __('Here you can add your testimonial',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'testimonial_home',
		array(
			'type'    => 'theme_mod',
			'default' => 1,
			'sanitize_callback'=>'enigma_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'testimonial_home', array(
			'label'        => __( 'Enable testimonial Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'testimonial_sec',
			'settings'   => 'testimonial_home'
		) );

	    $wp_customize->add_setting(
			'home_testimonial_heading',
			array(
				'type'              => 'theme_mod',
				'default'           => 'Our testimonial',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'enigma_sanitize_text'
			)
		);
		$wp_customize->add_control( 'home_testimonial_heading', array(
			'label'    => 'testimonial section title',
			'type'     =>'text',
			'section'  => 'testimonial_sec',
			'settings' => 'home_testimonial_heading'
		) );

		$wp_customize->selective_refresh->add_partial( 'home_testimonial_heading', array(
				'selector' => '.enigma_service .enigma_heading_title h3',
			) );

		
		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/functions/testimonial-functions.php' );
		if ( class_exists( 'enigma_Customizer_testimonial_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'enigma_testimonial',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'enigma_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

		$wp_customize->add_control( new enigma_Customizer_testimonial_fields( $wp_customize, 'testimonial_arr', array(
			'type'        => 'text',
			'section'     => 'testimonial_sec',
			'settings'    => 'enigma_testimonial',
			'label'       => __( 'testimonial', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your testimonial.', WL_COMPANION_DOMAIN ),
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
			'type'     =>'hidden',
			'section'  => 'testimonial_sec',
			'settings' => 'enigma_testimonial_data'
		) );

	}
}

?>