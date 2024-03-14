<?php

defined( 'ABSPATH' ) or die();

/**
 *  Service Section 
 */
class wl_contact_customizer {
	
	public static function wl_nineteen_contact_customizer( $wp_customize ) {

		/* Contact Section */
		$wp_customize->add_section(
	        'contact_sec',
	        array(
	            'title' 		  => __('Contact Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'nineteen_theme_option',
	            'description' 	  => __('Here you can add contact details',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );
		/* Show contact section on homepage */		
	    $wp_customize->add_setting(
		'contact_home',
		array(
			'type'   			=> 'theme_mod',
			'default'			=>0,
			'sanitize_callback' =>'nineteen_sanitize_checkbox',
			'capability' 		=> 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'nineteen_show_contact', array(
			'label'      => __( 'Enable contact Section on Home', WL_COMPANION_DOMAIN ),
			'type'		 =>'checkbox',
			'section'    => 'contact_sec',
			'settings'   => 'contact_home'
		) );
		$wp_customize->selective_refresh->add_partial( 'nineteen_show_contact', array(
			'selector' => '.header-topbar',
		) );
		/* Contact number*/
	    $wp_customize->add_setting(
			'nineteen_contact_number',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_contact_number', array(
			'label'    => 'Contact Number',
			'type'     =>'text',
			'section'  => 'contact_sec',
			'settings' => 'nineteen_contact_number'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_contact_number', array(
				'selector' => '.info_header-box',
			) );
		/* Contact Email*/
	    $wp_customize->add_setting(
			'nineteen_contact_email',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_contact_email', array(
			'label'    => 'Contact Email',
			'type'     =>'text',
			'section'  => 'contact_sec',
			'settings' => 'nineteen_contact_email'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_contact_email', array(
				'selector' => '.info_header-box',
			) );
				/* Contact address*/
	    $wp_customize->add_setting(
			'nineteen_contact_address',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_contact_address', array(
			'label'    => 'Contact Address',
			'type'     =>'text',
			'section'  => 'contact_sec',
			'settings' => 'nineteen_contact_address'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_contact_address', array(
				'selector' => '.info_header-box',
			) );


	}
}

?>