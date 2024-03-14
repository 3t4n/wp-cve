<?php

defined( 'ABSPATH' ) or die();

/**
 *  Slider Section 
 */
class wl_slider_customizer {
	
	public static function wl_digicrew_slider_customizer( $wp_customize ) {
		/* Slider Section */
		$wp_customize->add_section(
	        'slider_sec',
	        array(
	            'title' 		  => __('Banner Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'digicrew_theme_option',
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
				'sanitize_callback' => 'digicrew_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		
		$wp_customize->add_control( 
			'slider_home', array(
			'label'    => __( 'Enable Banner on Homepage', WL_COMPANION_DOMAIN ),
			'type'	   => 'checkbox',
			'section'  => 'slider_sec',
			'settings' => 'slider_home',
			) 
		);

		$wp_customize->add_setting(
		'banner_background',
			array(
				'type'=>'theme_mod',
				'default'=> '',
				'sanitize_callback'=>'esc_url_raw',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
		$wp_customize, 'banner_background',
		array(
			'label'    => esc_html__('Background Image',WL_COMPANION_DOMAIN), 
			'description'=>__('Select background image for banner ',WL_COMPANION_DOMAIN),
			'section'  => 'slider_sec',
			'settings' => 'banner_background',
		) ) );

		$wp_customize->add_setting(
			'banner_heading',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'digicrew_sanitize_text'
			)
		);
		$wp_customize->add_control( 'banner_heading', array(
			'label'    => 'Banner Title',
			'type'     =>'text',
			'section'  => 'slider_sec',
			'settings' => 'banner_heading'
		) );

		$wp_customize->selective_refresh->add_partial( 'banner_heading', array(
				'selector' => '.slide-content h1',
			) );


		$wp_customize->add_setting( 'banner_desc', array(
		    'type' => 'theme_mod',
	            'default' => __( '', WL_COMPANION_DOMAIN ),
	            'sanitize_callback' => 'digicrew_sanitize_text',
	            'capability' => 'edit_theme_options',
	        )
	    );

	    $wp_customize->add_control( 'banner_desc', array(
		    'label'       => __( 'Banner Description', WL_COMPANION_DOMAIN ),
		    'description' => '',
		    'type'        => 'textarea',
		    'section'     => 'slider_sec',
		    'settings'    => 'banner_desc',
	    ) );


		$wp_customize->add_setting( 'btn_txt', array(
		    'type' => 'theme_mod',
	            'default' => __( '', WL_COMPANION_DOMAIN ),
	            'sanitize_callback' => 'digicrew_sanitize_text',
	            'capability' => 'edit_theme_options',
	        )
	    );

	    $wp_customize->add_control( 'btn_txt', array(
		    'label'       => __( 'Banner Button Text', WL_COMPANION_DOMAIN ),
		    'description' => '',
		    'type'        => 'text',
		    'section'     => 'slider_sec',
		    'settings'    => 'btn_txt',
	    ) );

		$wp_customize->add_setting( 'btn_url', array(
		    'type' => 'theme_mod',
	            'default' => __( '', WL_COMPANION_DOMAIN ),
	            'sanitize_callback' => 'digicrew_sanitize_text',
	            'capability' => 'edit_theme_options',
	        )
	    );

	    $wp_customize->add_control( 'btn_url', array(
		    'label'       => __( 'Banner Button Url', WL_COMPANION_DOMAIN ),
		    'description' => '',
		    'type'        => 'text',
		    'section'     => 'slider_sec',
		    'settings'    => 'btn_url',
	    ) );


		$wp_customize->add_setting(
		'banner_sidebackground',
			array(
				'type'=>'theme_mod',
				'default'=> '',
				'sanitize_callback'=>'esc_url_raw',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control( 
			new WP_Customize_Image_Control( 
				$wp_customize, 'banner_sidebackground',
				array(
					'label'    => esc_html__('Banner Right Side Image',WL_COMPANION_DOMAIN), 
					'description'=>__('Select Right Side image for banner ',WL_COMPANION_DOMAIN),
					'section'  => 'slider_sec',
					'settings' => 'banner_sidebackground',
				) 
			) 
		);
	}
}

?>