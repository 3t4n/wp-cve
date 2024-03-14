<?php

defined( 'ABSPATH' ) or die();

/**
 *  Slider Section 
 */
class wl_slider_customizer {
	
	public static function wl_explora_slider_customizer( $wp_customize ) {
		/* Slider Section */
		$wp_customize->add_section(
	        'slider_sec',
	        array(
	            'title' 		  => __('Slider Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'explora_theme_option',
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
				'sanitize_callback' => 'explora_sanitize_checkbox',
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

			$wp_customize->add_setting(
			'slider_choise',
			array(
				'default'           => '1',
				'capability'        => 'edit_theme_options',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'explora_sanitize_text',
			)
		);

		$wp_customize->add_control(
			'slider_choise',
			array(
				'settings' => 'slider_choise',
				'label'    => __( 'Select Slider', WL_COMPANION_DOMAIN ),
				'section'  => 'slider_sec',
				'type'     => 'select',
				'choices'  => array(
					'1' => 'Slider 1',
					'2' => 'Slider 2',
				),
			)
		);

		$wp_customize->add_setting(
			'slider_speed',
			array(
				'type'              => 'theme_mod',
				'default'           => '2000',
				'sanitize_callback' => 'explora_sanitize_text',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control( 'slider_speed', array(
			'label'       => __( 'Slider Speed Option', WL_COMPANION_DOMAIN ),
			'description' => 'Value will be in milliseconds',
			'type'        => 'text',
			'section'     => 'slider_sec',
			'settings'    => 'slider_speed',
		) );
	
		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/functions/slider-functions.php' );
		if ( class_exists( 'explora_Customizer_slider_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'explora_slider',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'explora_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new explora_Customizer_slider_fields( $wp_customize, 'slider_arr', array(
			'type'        => 'text',
			'section'     => 'slider_sec',
			'settings'    => 'explora_slider',
			'label'       => __( 'Slider', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Slides.', WL_COMPANION_DOMAIN ),
			)));
		}
		$wp_customize->add_setting(
			'explora_slider_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'explora_sanitize_text'
			)
		);
		$wp_customize->add_control( 'explora_slider_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'slider_sec',
			'settings' => 'explora_slider_data'
		) );
	}
}

?>