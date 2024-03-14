<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_color_customizer {
	
	public static function wl_digitrails_color_customizer( $wp_customize ) {
		 /* Blog Option */
    $wp_customize->add_section( 'color_section', array(
	    'title'      => __( 'Theme Color', WL_COMPANION_DOMAIN),
	    'panel'      => 'digicrew_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 35
    ) );

   

    $wp_customize->add_setting(
	    'primary_color',
	    array(
		    'type'              => 'theme_mod',
		    'default'           => '#3eb1e6',
		    'sanitize_callback' => 'digicrew_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 
    	new WP_Customize_Color_Control( 
		    $wp_customize, 
		    'primary_color', 
		    array(
		        'label'      => __( 'Primary Color', 'spicyaroma' ),
		        'section'    => 'color_section',
		        'settings'   => 'primary_color',
		    ) 
		) 
	);

    $wp_customize->add_setting(
	    'secondary_color',
	    array(
		    'type'              => 'theme_mod',
		    'default'           => '#c70850',
		    'sanitize_callback' => 'digicrew_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 
    	new WP_Customize_Color_Control( 
		    $wp_customize, 
		    'secondary_color', 
		    array(
		        'label'      => __( 'Secondary Color', 'spicyaroma' ),
		        'section'    => 'color_section',
		        'settings'   => 'secondary_color',
		    ) 
		) 
	);
    
	}

}

?>