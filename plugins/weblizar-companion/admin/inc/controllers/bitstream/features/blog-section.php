<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_blog_customizer {
	
	public static function wl_bitstream_blog_customizer( $wp_customize ) {
		/* Blog Option */
		$wp_customize->add_section('blog_section',
			array(
				'title'=>__("Home Blog Options",WL_COMPANION_DOMAIN),
				'panel'=>'bitstream_theme_option',
				'capability'=>'edit_theme_options',
			    'priority' => 40
			)
		);

		$wp_customize->add_setting(
			'blog_home',
			array(
				'type'    => 'theme_mod',
				'default'=>1,
				'sanitize_callback'=>'bitstream_sanitize_checkbox',
				'capability' => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'bitstream_show_blog', 
			array(
				'label'        => __( 'Enable Blog on Home', WL_COMPANION_DOMAIN ),
				'type'=>'checkbox',
				'section'    => 'blog_section',
				'settings'   => 'blog_home'
			) 
		);

		$wp_customize->add_setting(
			'bitstream_blog_title',
			array(
			'default'=>'Our Blog',
			'type'=>'theme_mod',
			'sanitize_callback'=>'bitstream_sanitize_text',
			'capability'=>'edit_theme_options'
			)
		);

		$wp_customize->selective_refresh->add_partial( 'bitstream_blog_title', array(
			'selector' => '.blog-section .section-heading h2',
		) );

		$wp_customize->add_control( 'bitstream_blog_title', 
			array(
				'label'        => __( 'Home Blog Title', WL_COMPANION_DOMAIN ),
				'type'=>'text',
				'section'    => 'blog_section',
				'settings'   => 'bitstream_blog_title'
			) 
		);

		$wp_customize->add_setting(
			'bitstream_blog_desc',
			array(
				'default'=>'',
				'type'=>'theme_mod',
				'sanitize_callback'=>'bitstream_sanitize_text',
				'capability'=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'bitstream_blog_desc', 
			array(
				'label'        => __( 'Home Blog Description', WL_COMPANION_DOMAIN ),
				'type'=>'textarea',
				'section'    => 'blog_section',
				'settings'   => 'bitstream_blog_desc'
			) 
		);

		$wp_customize->selective_refresh->add_partial( 'bitstream_blog_desc', array(
			'selector' => '.blog-section .section-heading p',
		) );
	}
}

?>