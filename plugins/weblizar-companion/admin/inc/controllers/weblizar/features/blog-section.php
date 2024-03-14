<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_wl_blog_customizer( $wp_customize ) {
		 /* Blog Option */
    $wp_customize->add_section( 'blog_section', array(
	    'title'      => __( 'Home Blog Options', WL_COMPANION_DOMAIN),
	    'panel'      => 'weblizar_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 40
    ) );

    $wp_customize->add_setting(
	    'blog_home',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'weblizar_sanitize_checkbox',
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
		    'sanitize_callback' => 'weblizar_sanitize_text',
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
	    'selector' => '.weblizar_blog_title',
    ));

    $wp_customize->add_setting(
	'blog_text',
		array(
		'default'		   => __("Lorem Ipsum is simply dummy text of the printing and typesetting industry..",WL_COMPANION_DOMAIN ),
		'type'              => 'theme_mod',
		'sanitize_callback'=> 'weblizar_sanitize_text',
		'capability'	   => 'edit_theme_options'
		)
	);
	$wp_customize->selective_refresh->add_partial( 'blog_text', array(
	'selector' 		  => '.weblizar_blog_text',
	) );
	require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/functions/one-page-editor.php' );
	$wp_customize->add_control(new One_Page_Editor($wp_customize, 'blog_text', array(
		'label'        	  => __( 'Home Blog Description', WL_COMPANION_DOMAIN),
		'section'    	  => 'blog_section',
		'include_admin_print_footer' => true,
		'settings'        => 'blog_text',
	)
	) );

    $wp_customize->add_setting( 'read_more', array(
	    'type' => 'theme_mod',
        'default' => __( '#', WL_COMPANION_DOMAIN ),
        'sanitize_callback' => 'weblizar_sanitize_text',
        'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_control( 'read_more', array(
	    'label'       => __( 'Blog Show More Button Link', WL_COMPANION_DOMAIN ),
	    'description' => 'Enter Show More button Link',
	    'type'        => 'text',
	    'section'     => 'blog_section',
	    'settings'    => 'read_more',
    ) );
    
	}
}

?>