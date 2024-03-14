<?php

defined( 'ABSPATH' ) or die();

/**
 *  Slider Section 
 */
class wl_slider_customizer {
	
	public static function wl_enigma_parallax_slider_customizer( $wp_customize ) {
		/* Slider Section */
		$wp_customize->add_section(
	        'slider_sec',
	        array(
	            'title' 		  => __('Slider Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'enigma_parallax_theme_option',
	            'description' 	  => __('Here you can add slider images',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
			'slider_home',
			array(
				'type'              => 'theme_mod',
				'default'			=> 1,
				'sanitize_callback' => 'enigma_parallax_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		
		$wp_customize->add_control( 
			'slider_home', array(
			'label'    => __( 'Enable Slider on Homepage', WL_COMPANION_DOMAIN ),
			'type'	   => 'checkbox',
			'section'  => 'slider_sec',
			'settings' => 'slider_home',
			) 
		);

		//  =============================
		//  = Select Box                =
		//  =============================
		$wp_customize->add_setting(
			'slider_choise',
			array(
				'default'           => '1',
				'capability'        => 'edit_theme_options',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'enigma_parallax_sanitize_text',
			)
		);

		$wp_customize->add_control(
			'slider_choise',
			array(
				'settings' => 'slider_choise',
				'label'    => __( 'Select Something:', WL_COMPANION_DOMAIN ),
				'section'  => 'slider_sec',
				'type'     => 'select',
				'choices'  => array(
					'1' => 'Carousel Slider',
					'2' => 'Touch Slider',
				),
			)
		);

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/functions/animation.php' );

		$wp_customize->add_setting(
		'slider_image_speed',
		array(
			'type'              => 'theme_mod',
			'default'           => '2000',
			'sanitize_callback' => 'enigma_parallax_sanitize_text',
			'capability'        => 'edit_theme_options',
		)
	);

	$wp_customize->add_control( 'enigma_slider_speed', array(
		'label'       => __( 'Slider Speed Option', WL_COMPANION_DOMAIN ),
		'description' => 'Value will be in milliseconds',
		'type'        => 'text',
		'section'     => 'slider_sec',
		'settings'    => 'slider_image_speed',
	) );

	
	//slider animation
	$wp_customize->add_setting( 'slider_anim',
		array(
			'type'              => 'theme_mod',
			'default'           => 'slide',
			'sanitize_callback' => 'enigma_parallax_sanitize_text',
			'capability'        => 'edit_theme_options',
		)
	);

	$wp_customize->add_control( 'slider_anim', array(
		'label'      => __( 'Slider Animation', WL_COMPANION_DOMAIN ),
		'type'       => 'select',
		'section'    => 'slider_sec',
		'settings'   => 'slider_anim',
		'choices'    => array(
			'slide'  => __( 'Slide', WL_COMPANION_DOMAIN ),
			'fadeIn' => __( 'Fade', WL_COMPANION_DOMAIN ),
		)
	) );

	$wp_customize->add_setting( 'animate_type_title',
		array(
			'type'              => 'theme_mod',
			'default'           => '',
			'sanitize_callback' => 'enigma_parallax_sanitize_text',
			'capability'        => 'edit_theme_options',
		)
	);

	$wp_customize->add_control( new enigma_animation( $wp_customize, 'animate_type_title', array(
		'label'    => __( 'Animation for Slider Title', WL_COMPANION_DOMAIN ),
		'type'     => 'select',
		'section'  => 'slider_sec',
		'settings' => 'animate_type_title',
	) ) );

	$wp_customize->add_setting( 'animate_type_desc',
		array(
			'type'              => 'theme_mod',
			'default'           => '',
			'sanitize_callback' => 'enigma_parallax_sanitize_text',
			'capability'        => 'edit_theme_options',
		)
	);

	$wp_customize->add_control( new enigma_animation( $wp_customize, 'animate_type_desc', array(
		'label'    => __( 'Animation for Slider Description', WL_COMPANION_DOMAIN ),
		'type'     => 'select',
		'section'  => 'slider_sec',
		'settings' => 'animate_type_desc',
	) ) );

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/functions/slider-functions.php' );
		if ( class_exists( 'enigma_Customizer_slider_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'enigma_slider',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'enigma_parallax_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new enigma_Customizer_slider_fields( $wp_customize, 'slider_arr', array(
			'type'        => 'text',
			'section'     => 'slider_sec',
			'settings'    => 'enigma_slider',
			'label'       => __( 'Slider', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Slides.', WL_COMPANION_DOMAIN ),
			)));
		}
		$wp_customize->add_setting(
			'enigma_slider_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'enigma_parallax_sanitize_text'
			)
		);
		$wp_customize->add_control( 'enigma_slider_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'slider_sec',
			'settings' => 'enigma_slider_data'
		) );
	}
}

?>