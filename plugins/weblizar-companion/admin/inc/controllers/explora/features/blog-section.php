<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_explora_blog_customizer( $wp_customize ) {
		 /* Blog Option */
    $wp_customize->add_section( 'blog_section', array(
	    'title'      => __( 'Home Blog Options', WL_COMPANION_DOMAIN),
	    'panel'      => 'explora_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 40
    ) );

    $wp_customize->add_setting(
	    'blog_home',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'explora_sanitize_checkbox',
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
		    'default'           => __( 'Latest Posts', WL_COMPANION_DOMAIN ),
		    'sanitize_callback' => 'explora_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    )
    );

    $wp_customize->add_control( 'explora_latest_post', array(
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
            'default' => __( 'Read More', WL_COMPANION_DOMAIN ),
            'sanitize_callback' => 'explora_sanitize_text',
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

    //Blog Autoplay
    $wp_customize->add_setting(
		'blog_preview',
		array(
			'type'              => 'theme_mod',
			'default'           => 150,
			'sanitize_callback' => 'explora_sanitize_text',
			'capability'        => 'edit_theme_options',
		)
	);	

    require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/functions/general-functions.php' );
	$wp_customize->add_control( new explora_Customizer_Range_Value_Control( $wp_customize, 'blog_preview', array(
		'type'        => 'range-value',
		'section'     => 'blog_section',
		'settings'    => 'blog_preview',
		'label'       => __('Blog Preview', WL_COMPANION_DOMAIN ),
		'input_attrs' => array(
			'min'     => 2,
			'max'     => 4,
			'step'    => 2,
			'suffix'  => 'px', //optional suffix
	  	),
	)));

	//Blog Autoplay
	$wp_customize->add_setting(
		'autoplay',
		array(
			'type'              => 'theme_mod',
			'default'           => 1,
			'sanitize_callback' => 'explora_sanitize_checkbox',
			'capability'        => 'edit_theme_options',
		)
	);
	$wp_customize->add_control( 'autoplay', array(
		'label'    => __( 'Blog Autoplay on/off',  WL_COMPANION_DOMAIN ),
		'type'     => 'checkbox',
		'section'  => 'blog_section',
		'settings' => 'autoplay',
	) );
	}
}

?>