<?php

defined( 'ABSPATH' ) or die();

/**
 *  Service Section 
 */
class wl_about_customizer {
	
	public static function wl_nineteen_about_customizer( $wp_customize ) {

		/* Service Section */
		$wp_customize->add_section(
	        'about_sec',
	        array(
	            'title' 		  => __('About Us Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'nineteen_theme_option',
	            'description' 	  => __('Here you can edit about us section',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'about_home',
		array(
			'type'   			=> 'theme_mod',
			'default'			=>0,
			'sanitize_callback' =>'nineteen_sanitize_checkbox',
			'capability' 		=> 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'nineteen_show_about', array(
			'label'      => __( 'Enable About Us Section on Home', WL_COMPANION_DOMAIN ),
			'type'		 =>'checkbox',
			'section'    => 'about_sec',
			'settings'   => 'about_home'
		) );

	    $wp_customize->add_setting(
			'nineteen_about_title',
			array(
				'type'              => 'theme_mod',
				'default'           => 'About Us',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_about_title', array(
			'label'    => 'Details section title',
			'type'     =>'text',
			'section'  => 'about_sec',
			'settings' => 'nineteen_about_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_details_title', array(
				'selector' => '.home-about',
			) );

		if ( class_exists( 'One_Page_Editor') ) {

			$wp_customize->add_setting(
				'nineteen_about_desc',
				array(
					'type'              => 'theme_mod',
					'default'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore dolore magna aliqua.Sed ut perspiciatis omnis iste natus error sit voluptatem',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'nineteen_sanitize_text'
				)
			);

			$wp_customize->add_control(new One_Page_Editor($wp_customize, 'nineteen_about_desc', array(
				'label'                      => __( 'About Section Description', WL_COMPANION_DOMAIN ),
				'active_callback'            => 'show_on_front',
				'include_admin_print_footer' => true,
				'section'                    => 'about_sec',
				'settings'                   => 'nineteen_about_desc'
			) ));
			
			$wp_customize->selective_refresh->add_partial( 'nineteen_details_desc', array(
				'selector' => '.home-about',
			) );	
		}

		// button text
		$wp_customize->add_setting(
		'nineteen_about_button_text',
		array(
		'type'              => 'theme_mod',
		'default'           => 'Read more',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'nineteen_sanitize_text'
		)
		);

		$wp_customize->add_control( 'nineteen_about_button_text', array(
			'label'    => 'Button Text',
			'type'     =>'text',
			'section'  => 'about_sec',
			'settings' => 'nineteen_about_button_text'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_about_button_text', array(
				'selector' => '.home-about',
			) );

		// button link

		$wp_customize->add_setting(
		'nineteen_about_button_link',
		array(
		'type'              => 'theme_mod',
		'default'           => '#',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'nineteen_sanitize_text'
		)
		);
		
		$wp_customize->add_control( 'nineteen_about_button_link', array(
			'label'    => 'Button Title',
			'type'     =>'url',
			'section'  => 'about_sec',
			'settings' => 'nineteen_about_button_link'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_about_button_link', array(
				'selector' => '.home-about',
			) );

		// Image

		$wp_customize->add_setting(
		'nineteen_about_image',
		array(
		'type'              => 'theme_mod',
		'default'           => '',
		'capability'        => 'edit_theme_options',
		)
		);
		
		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'nineteen_about_image', array(
			    'section' => 'about_sec',
			    'label' => __( 'Image', 'nineteen' ),
			    'settings' => 'nineteen_about_image',
			    'mime_type' => 'image',
				)));


		$wp_customize->selective_refresh->add_partial( 'nineteen_about_image', array(
				'selector' => '.home-about',
			) );


	}
}

?>