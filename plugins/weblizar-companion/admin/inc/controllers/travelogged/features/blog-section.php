<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_travelogged_blog_customizer( $wp_customize ) {
		/* Blog Option */
		$wp_customize->add_section('blog_section',array(
		'title'      => __("Home Blog Options",WL_COMPANION_DOMAIN),
		'panel'      => 'theme_options',
		'capability' => 'edit_theme_options',
	    'priority'   => 36
		));
		$wp_customize->add_setting(
		'blog_home',
		array(
			'type'              => 'theme_mod',
			'default'           => 1,
			'sanitize_callback' => 'travelogged_sanitize_checkbox',
			'capability'        => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'travelogged_show_blog', array(
			'label'    => __( 'Enable Blog on Home', WL_COMPANION_DOMAIN ),
			'type'     =>'checkbox',
			'section'  => 'blog_section',
			'settings' => 'blog_home'
		) );
		$wp_customize->add_setting(
		'travelogged_blog_title',
			array(
			'default'           => 'Latest News Update',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'travelogged_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);
		$wp_customize->selective_refresh->add_partial( 'travelogged_blog_title', array(
			'selector' => '.our-cases .section-title span',
		) );
		$wp_customize->add_control( 'travelogged_blog_title', array(
			'label'    =>  __( 'Home Blog Title', WL_COMPANION_DOMAIN ),
			'type'     => 'text',
			'section'  => 'blog_section',
			'settings' => 'travelogged_blog_title'
		) );

		$wp_customize->add_setting(
		'travelogged_blog_desc',
			array(
			'default'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'travelogged_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);
		$wp_customize->selective_refresh->add_partial( 'travelogged_blog_desc', array(
			'selector' => '.our-cases h4.section-description',
		) );
		$wp_customize->add_control( 'travelogged_blog_desc', array(
			'label'    => __( 'Home Blog Description', WL_COMPANION_DOMAIN ),
			'type'     => 'textarea',
			'section'  => 'blog_section',
			'settings' => 'travelogged_blog_desc'
		) );

		$wp_customize->add_setting( 'excerpt_blog', array(
	        'default'           => 200,
	        'type'              => 'theme_mod',
	        'sanitize_callback' => 'travelogged_sanitize_integer',
	        'capability'        => 'edit_theme_options'
	    ) );
	    $wp_customize->add_control( 'excerpt_blog', array(
	        'label'       => __( 'Excerpt length blog section', WL_COMPANION_DOMAIN ),
	        'type'        => 'number',
	        'section'     => 'blog_section',
			'description' => esc_html__('Excerpt length only for home blog section.', WL_COMPANION_DOMAIN),
			'settings'    => 'excerpt_blog'
	    ) );
	}
}

?>