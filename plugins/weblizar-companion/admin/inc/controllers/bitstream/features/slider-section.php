<?php

defined( 'ABSPATH' ) or die();

/**
 *  Slider Section 
 */
class wl_slider_customizer {
	
	public static function wl_bitstream_slider_customizer( $wp_customize ) {
		/* Slider Section */
		$wp_customize->add_section(
	        'slider_sec',
	        array(
	            'title' 		  => __('Slider Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'bitstream_theme_option',
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
				'default'			=> 0,
				'sanitize_callback' => 'bitstream_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'slider_home', array(
			'label'    => __( 'Enable Slider on Homepage', WL_COMPANION_DOMAIN ),
			'type'	   => 'checkbox',
			'section'  => 'slider_sec',
			'settings' => 'slider_home',
		) );

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/functions/slider-functions.php' );
		if ( class_exists( 'bitstream_Customizer_slider_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'bitstream_slider',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'bitstream_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new bitstream_Customizer_slider_fields( $wp_customize, 'slider_arr', array(
			'type'        => 'text',
			'section'     => 'slider_sec',
			'settings'    => 'bitstream_slider',
			'label'       => __( 'Slider', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Slides.', WL_COMPANION_DOMAIN ),
			)));
		}
		$wp_customize->add_setting(
			'bitstream_slider_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'bitstream_sanitize_text'
			)
		);
		$wp_customize->add_control( 'bitstream_slider_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'slider_sec',
			'settings' => 'bitstream_slider_data'
		) );
	}
}

?>