<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_digicrew_blog_customizer( $wp_customize ) {
		 /* Blog Option */
    $wp_customize->add_section( 'blog_section', array(
	    'title'      => __( 'Home Blog Options', WL_COMPANION_DOMAIN),
	    'panel'      => 'digicrew_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 40
    ) );

    $wp_customize->add_setting(
	    'blog_home',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'digicrew_sanitize_checkbox',
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
		    'default'           => __( 'Latest News', WL_COMPANION_DOMAIN ),
		    'sanitize_callback' => 'digicrew_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 'digicrew_latest_post', array(
	    'label'    => __( 'Home Blog Title', WL_COMPANION_DOMAIN ),
	    'type'     => 'text',
	    'section'  => 'blog_section',
	    'settings' => 'blog_title',
    ) );

    $wp_customize->selective_refresh->add_partial( 'blog_title', array(
	    'selector' => '.digicrew_blog_area .digicrew_heading_title h3',
    ));

    
require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/functions/blog-category.php' );
     $wp_customize->add_setting( 'blog_category',
	    array(
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'digicrew_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( new digicrew_category_Control( $wp_customize, 'blog_category', array(
	    'label'    => __( 'Blog Category', 'digicrew' ),
	    'type'     => 'select',
	    'section'  => 'blog_section',
	    'settings' => 'blog_category',
    ) ) );
  

    $wp_customize->add_setting( 'read_more', array(
	    'type' => 'theme_mod',
            'default' => __( 'Read More', WL_COMPANION_DOMAIN ),
            'sanitize_callback' => 'digicrew_sanitize_text',
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