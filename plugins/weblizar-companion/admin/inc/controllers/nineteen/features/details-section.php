<?php

defined( 'ABSPATH' ) or die();

/**
 *  Service Section 
 */
class wl_details_customizer {
	
	public static function wl_nineteen_details_customizer( $wp_customize ) {

		/* Service Section */
		$wp_customize->add_section(
	        'details_sec',
	        array(
	            'title' 		  => __('Details Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'nineteen_theme_option',
	            'description' 	  => __('Here you can add details',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'details_home',
		array(
			'type'   			=> 'theme_mod',
			'default'			=>1,
			'sanitize_callback' =>'nineteen_sanitize_checkbox',
			'capability' 		=> 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'nineteen_show_details', array(
			'label'      => __( 'Enable Details Section on Home', WL_COMPANION_DOMAIN ),
			'type'		 =>'checkbox',
			'section'    => 'details_sec',
			'settings'   => 'details_home'
		) );

	    $wp_customize->add_setting(
			'nineteen_details_title',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_details_title', array(
			'label'    => 'Details section title',
			'type'     =>'text',
			'section'  => 'details_sec',
			'settings' => 'nineteen_details_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_details_title', array(
				'selector' => '.home-welcome',
			) );


	    $wp_customize->add_setting(
			'nineteen_details_desc',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_details_desc', array(
			'label'    => 'Details section description',
			'type'     =>'textarea',
			'section'  => 'details_sec',
			'settings' => 'nineteen_details_desc'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_details_desc', array(
				'selector' => '.home-welcome',
		) );
	}
}

?>