<?php

defined( 'ABSPATH' ) or die();

/**
 *  Slider Section 
 */
class wl_slider_customizer {
	
	public static function wl_guardian_slider_customizer( $wp_customize ) {
		/* Slider Section */
		$wp_customize->add_section(
	        'slider_sec',
	        array(
	            'title' 		  => __('Theme Slider Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'guardian_theme_option',
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
				'sanitize_callback' => 'guardian_sanitize_checkbox',
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
				'sanitize_callback' => 'guardian_sanitize_text',
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
		'slider_image_speed',
		array(
			'type'              => 'theme_mod',
			'default'           => '2000',
			'sanitize_callback' => 'guardian_sanitize_text',
			'capability'        => 'edit_theme_options',
		)
	);

	$wp_customize->add_control( 'guardian_slider_speed', array(
		'label'       => __( 'Slider Speed Option', WL_COMPANION_DOMAIN ),
		'description' => 'Value will be in milliseconds',
		'type'        => 'text',
		'section'     => 'slider_sec',
		'settings'    => 'slider_image_speed',
	) );

	
	

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/functions/slider-functions.php' );
		if ( class_exists( 'guardian_Customizer_slider_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'guardian_slider',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'guardian_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new guardian_Customizer_slider_fields( $wp_customize, 'slider_arr', array(
			'type'        => 'text',
			'section'     => 'slider_sec',
			'settings'    => 'guardian_slider',
			'label'       => __( 'Slider', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Slides.', WL_COMPANION_DOMAIN ),
			)));
		}
		$wp_customize->add_setting(
			'guardian_slider_data',
			array(
				'type'              => 'theme_mod',
				'default'           => serialize( array(
            /*Repeater's first item*/
            array(
				'slider_name' => 'Welcome to Guardian Theme',
				'slider_desc'      => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				'slider_image'       => get_template_directory_uri().'/images/slider.jpg' ,
				'slider_text'    => 'View More',
				'slider_link' => '#',
				),
			array(
				'slider_name' => 'Welcome to Guardian Theme',
				'slider_desc'      => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				'slider_image'       => get_template_directory_uri().'/images/slider.jpg' ,
				'slider_text'    => 'View More',
				'slider_link' => '#',
				),
			)),
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'guardian_sanitize_text'
			)
		);
		$wp_customize->add_control( 'guardian_slider_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'slider_sec',
			'settings' => 'guardian_slider_data'
		) );
	}
}

?>