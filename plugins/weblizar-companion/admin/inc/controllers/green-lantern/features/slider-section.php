<?php

defined( 'ABSPATH' ) or die();

/**
 *  Slider Section 
 */
class wl_slider_customizer {
	
	public static function wl_green_lantern_slider_customizer( $wp_customize ) {
		/* Slider Section */
		$wp_customize->add_section(
	        'slider_sec',
	        array(
	            'title' 		  => __('Slider Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'green_lantern_theme_option',
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
				'sanitize_callback' => 'green_lantern_sanitize_checkbox',
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
				'sanitize_callback' => 'green_lantern_sanitize_text',
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
				'default'           => '4000',
				'sanitize_callback' => 'green_lantern_sanitize_text',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control( 'slider_image_speed', array(
			'label'       => __( 'Slider Speed Option', WL_COMPANION_DOMAIN ),
			'description' => 'Value will be in milliseconds',
			'type'        => 'text',
			'section'     => 'slider_sec',
			'settings'    => 'slider_image_speed',
		) ); 

		//search form
		$wp_customize->add_setting(
			'green_lantern_pause',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'green_lantern_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'green_lantern_pause', array(
			'label'    => __( 'Pause On Mouse Hover',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'slider_sec',
			'settings' => 'green_lantern_pause',
		) );

		$wp_customize->add_setting(
		'slider_background',
			array('type'=>'theme_mod',
			'default'=> '',
			'sanitize_callback'=>'esc_url_raw',
			'capability'        => 'edit_theme_options',
		));
		$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
		$wp_customize, 'slider_background',
		array(
			'label'    => esc_html__('Background Image For Slider 1',WL_COMPANION_DOMAIN), 
			'description'=>__('Select background image for Slider 1 ',WL_COMPANION_DOMAIN),
			'section'  => 'slider_sec',
			'settings' => 'slider_background',
		) ) );

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/functions/slider-functions.php' );
		if ( class_exists( 'green_lantern_Customizer_slider_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'green_lantern_slider',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'green_lantern_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new green_lantern_Customizer_slider_fields( $wp_customize, 'slider_arr', array(
			'type'        => 'text',
			'section'     => 'slider_sec',
			'settings'    => 'green_lantern_slider',
			'label'       => __( 'Slider', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Slides.', WL_COMPANION_DOMAIN ),
			)));
		}
		$wp_customize->add_setting(
			'green_lantern_slider_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'green_lantern_sanitize_text'
			)
		);
		$wp_customize->add_control( 'green_lantern_slider_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'slider_sec',
			'settings' => 'green_lantern_slider_data'
		) );
	}
}

?>