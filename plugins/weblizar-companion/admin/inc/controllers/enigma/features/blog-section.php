<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_enigma_blog_customizer( $wp_customize ) {
		 /* Blog Option */
    $wp_customize->add_section( 'blog_section', array(
	    'title'      => __( 'Home Blog Options', WL_COMPANION_DOMAIN),
	    'panel'      => 'enigma_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 40
    ) );

    $wp_customize->add_setting(
	    'blog_home',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'enigma_sanitize_checkbox',
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
		    'sanitize_callback' => 'enigma_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 'enigma_latest_post', array(
	    'label'    => __( 'Home Blog Title', WL_COMPANION_DOMAIN ),
	    'type'     => 'text',
	    'section'  => 'blog_section',
	    'settings' => 'blog_title',
    ) );

    $wp_customize->selective_refresh->add_partial( 'blog_title', array(
	    'selector' => '.enigma_blog_area .enigma_heading_title h3',
    ));

    $wp_customize->add_setting(
	    'blog_speed',
	    array(
		    'type'              => 'theme_mod',
		    'default'           => '2000',
		    'sanitize_callback' => 'enigma_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 'blog_speed', array(
	    'label'       => __( 'Slider Speed Option', WL_COMPANION_DOMAIN ),
	    'description' => 'Value will be in milliseconds',
	    'type'        => 'text',
	    'section'     => 'blog_section',
	    'settings'    => 'blog_speed',
    ) );
require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/functions/blog-category.php' );
     $wp_customize->add_setting( 'blog_category',
	    array(
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'enigma_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( new enigma_category_Control( $wp_customize, 'blog_category', array(
	    'label'    => __( 'Blog Category', 'enigma' ),
	    'type'     => 'select',
	    'section'  => 'blog_section',
	    'settings' => 'blog_category',
    ) ) );
  

    $wp_customize->add_setting( 'read_more', array(
	    'type' => 'theme_mod',
            'default' => __( 'Read More', WL_COMPANION_DOMAIN ),
            'sanitize_callback' => 'enigma_sanitize_text',
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

    $wp_customize->add_setting( 'autoplay', array(
	    'type' => 'theme_mod',
            'default' => 1,
            'sanitize_callback' => 'enigma_sanitize_checkbox',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control( 'autoplay', array(
	    'label'       => __( 'Blog AutoPlay', WL_COMPANION_DOMAIN ),
	    'description' => 'blog autoplay on/off',
	    'type'        => 'checkbox',
	    'section'     => 'blog_section',
	    'settings'    => 'autoplay',
    ) );
	}

}

?>