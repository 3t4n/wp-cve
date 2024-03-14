<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_scoreline_blog_customizer( $wp_customize ) {
		 /* Blog Option */
    $wp_customize->add_section( 'blog_section', array(
	    'title'      => __( 'Home Blog Options', WL_COMPANION_DOMAIN),
	    'panel'      => 'scoreline_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 40
    ) );

    $wp_customize->add_setting(
	    'blog_home',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'scoreline_sanitize_checkbox',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'blog_home', array(
	    'label'    => __( 'Enable Blog on Home', WL_COMPANION_DOMAIN),
	    'type'     => 'checkbox',
	    'section'  => 'blog_section',
	    'settings' => 'blog_home'
    ) );

    $wp_customize->add_setting(
	    'blog_title',
	    array(
		    'type'              => 'theme_mod',
		    'default'           => __( 'Latest Blog', WL_COMPANION_DOMAIN ),
		    'sanitize_callback' => 'scoreline_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 'scoreline_latest_post', array(
	    'label'    => __( 'Home Blog Title', WL_COMPANION_DOMAIN ),
	    'type'     => 'text',
	    'section'  => 'blog_section',
	    'settings' => 'blog_title',
    ) );

    $wp_customize->selective_refresh->add_partial( 'blog_title', array(
	    'selector' => '#latest-posts h2',
    ));

    $wp_customize->add_setting( 'read_more', array(
	    'type' => 'theme_mod',
            'default' => __( 'Continue', WL_COMPANION_DOMAIN ),
            'sanitize_callback' => 'scoreline_sanitize_text',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control( 'read_more', array(
	    'label'       => __( 'Blog Read More Button', WL_COMPANION_DOMAIN ),
	    'description' => 'Enter Read More button text',
	    'type'        => 'text',
	    'section'     => 'blog_section',
	    'settings'    => 'read_more',
    ) );

	
	}
}

?>