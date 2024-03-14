<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_social_customizer {
	
	public static function wl_wl_social_customizer( $wp_customize ) {
		       /* Social options */
	    $wp_customize->add_section( 'social_section', array(
		    'title'      => __( "Social Options", WL_COMPANION_DOMAIN ),
		    'panel'      => 'weblizar_theme_option',
		    'capability' => 'edit_theme_options',
		    'priority'   => 48
	    ) );

	   

	    $wp_customize->add_setting(
		    'footer_section_social_media_enbled',
		    array(
			    'default'           => 1,
			    'type'              => 'theme_mod',
			    'sanitize_callback' => 'weblizar_sanitize_checkbox',
			    'capability'        => 'edit_theme_options'
		    )
	    );

	    $wp_customize->add_control( 'footer_section_social_media_enbled', array(
		    'label'    => __( 'Enable Social Media Icons in Footer', WL_COMPANION_DOMAIN ),
		    'type'     => 'checkbox',
		    'section'  => 'social_section',
		    'settings' => 'footer_section_social_media_enbled'
	    ) );

	    $wp_customize->selective_refresh->add_partial( 'footer_section_social_media_enbled', array(
		    'selector' => '.weblizar_social_media_enbled',
	    ));

	   

	    $wp_customize->add_setting(
		    'twitter_link',
		    array(
			    'default'           => '#',
			    'type'              => 'theme_mod',
			    'sanitize_callback' => 'esc_url_raw',
			    'capability'        => 'edit_theme_options'
		    )
	    );

	    $wp_customize->add_control( 'twitter_link', array(
		    'label'    => __( 'Twitter', WL_COMPANION_DOMAIN ),
		    'type'     => 'url',
		    'section'  => 'social_section',
		    'settings' => 'twitter_link'
	    ) );

	    $wp_customize->add_setting(
		    'fb_link',
		    array(
			    'default'           => '#',
			    'type'              => 'theme_mod',
			    'sanitize_callback' => 'esc_url_raw',
			    'capability'        => 'edit_theme_options'
		    )
	    );

	    $wp_customize->add_control( 'fb_link', array(
		    'label'    => __( 'Facebook', WL_COMPANION_DOMAIN ),
		    'type'     => 'url',
		    'section'  => 'social_section',
		    'settings' => 'fb_link'
	    ) );

	    $wp_customize->add_setting(
		    'linkedin_link',
		    array(
			    'default'           => '#',
			    'type'              => 'theme_mod',
			    'sanitize_callback' => 'esc_url_raw',
			    'capability'        => 'edit_theme_options'
		    )
	    );
	    $wp_customize->add_control( 'linkedin_link', array(
		    'label'    => __( 'LinkedIn', WL_COMPANION_DOMAIN ),
		    'type'     => 'url',
		    'section'  => 'social_section',
		    'settings' => 'linkedin_link'
	    ) );
	}
}
?>