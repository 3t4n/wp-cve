<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_about_customizer {
	
	public static function wl_guardian_about_customizer( $wp_customize ) {
		 /* Blog Option */
    $wp_customize->add_section( 'about_section', array(
	    'title'      => __( 'Home About Options', WL_COMPANION_DOMAIN),
	    'panel'      => 'guardian_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 40
    ) );

    $wp_customize->add_setting(
	    'about_home',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'guardian_sanitize_checkbox',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'about_home', array(
	    'label'    => __( 'Enable About Section on Home', WL_COMPANION_DOMAIN),
	    'type'     => 'checkbox',
	    'section'  => 'about_section',
	    'settings' => 'about_home'
    ) );

    $wp_customize->add_setting(
	    'about_title',
	    array(
		    'type'              => 'theme_mod',
		    'default'           => __( 'We Are the Best Digital Agency', WL_COMPANION_DOMAIN ),
		    'sanitize_callback' => 'guardian_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 'about_title', array(
	    'label'    => __( 'Home Blog Title', WL_COMPANION_DOMAIN ),
	    'type'     => 'text',
	    'section'  => 'about_section',
	    'settings' => 'about_title',
    ) );

    $wp_customize->selective_refresh->add_partial( 'about_title', array(
	    'selector' => '.about-txt h2',
    ));

    $wp_customize->add_setting( 'about_txt', array(
	    'type' => 'theme_mod',
            'sanitize_callback' => 'guardian_sanitize_text',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control( 'about_txt', array(
	    'label'       => __( 'About Description', WL_COMPANION_DOMAIN ),
	    'type'        => 'text',
	    'section'     => 'about_section',
	    'settings'    => 'about_txt',
    ) );
	
	$wp_customize->add_setting( 
		'About_Image', 
		array( 
			'type' => 'theme_mod',
            'sanitize_callback' => 'guardian_sanitize_text',
            'capability' => 'edit_theme_options',
	)); 
					
	$wp_customize->add_control( new WP_Customize_Image_Control( 
		$wp_customize, 'About_Image', array(
		'label'    => ' About Section Image ',
		'priority' => 1,
		'section'  => 'about_section',
		'settings' => 'About_Image',
		'button_labels' => array(
					// All These labels are optional
					'select' => __( 'Select Image', 'markito' ),
					'remove' => __( 'Remove Image', 'markito' ),
					'change' => __( 'Change Image', 'markito' )


		)
	)));
	
	$wp_customize->add_setting( 'about_link', array(
	    'type' => 'theme_mod',
        'sanitize_callback' => 'guardian_sanitize_text',
        'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control( 'about_link', array(
	    'label'       => __( 'About Link', WL_COMPANION_DOMAIN ),
	    'type'        => 'text',
	    'section'     => 'about_section',
	    'settings'    => 'about_link',
    ) );
    
	}
}

?>