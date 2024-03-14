<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_nineteen_blog_customizer( $wp_customize ) {
		/* Blog Option */
		$wp_customize->add_section('blog_section',array(
		'title'=>__("Home Blog Options",WL_COMPANION_DOMAIN),
		'panel'=>'nineteen_theme_option',
		'capability'=>'edit_theme_options',
	    'priority' => 40
		));
		$wp_customize->add_setting(
		'blog_home',
		array(
			'type'    => 'theme_mod',
			'default'=>1,
			'sanitize_callback'=>'nineteen_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'nineteen_show_blog', array(
			'label'        => __( 'Enable Blog on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'blog_section',
			'settings'   => 'blog_home'
		) );
		$wp_customize->add_setting(
		'nineteen_blog_title',
			array(
			'default'=>'Our Blogs',
			'type'=>'theme_mod',
			'sanitize_callback'=>'nineteen_sanitize_text',
			'capability'=>'edit_theme_options'
			)
		);
		$wp_customize->selective_refresh->add_partial( 'nineteen_blog_title', array(
			'selector' => '.our-cases .section-title span',
		) );
		$wp_customize->add_control( 'nineteen_blog_title', array(
			'label'        => __( 'Home Blog Title', WL_COMPANION_DOMAIN ),
			'type'=>'text',
			'section'    => 'blog_section',
			'settings'   => 'nineteen_blog_title'
		) );

		$wp_customize->add_setting(
		'nineteen_blog_desc',
			array(
			'default'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
			'type'=>'theme_mod',
			'sanitize_callback'=>'nineteen_sanitize_text',
			'capability'=>'edit_theme_options'
			)
		);
		$wp_customize->selective_refresh->add_partial( 'nineteen_blog_desc', array(
			'selector' => '.our-cases h4.section-description',
		) );
		$wp_customize->add_control( 'nineteen_blog_desc', array(
			'label'        => __( 'Home Blog Description', WL_COMPANION_DOMAIN ),
			'type'=>'textarea',
			'section'    => 'blog_section',
			'settings'   => 'nineteen_blog_desc'
		) );
	}
}
?>