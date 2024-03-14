<?php 
// customizer theme blog settings
add_action( 'customize_register', 'amigo_industri_customizer_blog_settings');
function amigo_industri_customizer_blog_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// blog section
	$wp_customize->add_section('blog_section', array(
		'title'    => esc_html__( 'Blog', 'amigo-extensions' ),
		'panel'	=> 'theme_homepage',
		'priority' => 1,		

	));	

	

	// is display
	$wp_customize->add_setting(
		'display_blog_section',
		array(
			'default' => true,
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_blog_section',
		array(
			'label'   		=> __('Show/Hide blog Section','amigo-extensions'),
			'section'		=> 'blog_section',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'blog_title', array(
		'selector'            => '.our-blog .section-title h5',				
		'render_callback'  => function() { return get_theme_mod( 'blog_title' ); },
	) );	

	// blog section title
	$wp_customize->add_setting(
		'blog_title',
		array(
			'default' => esc_html( $default['blog_title'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'blog_title',
		array(
			'label'   		=> __('Title','amigo-extensions'),
			'section'		=> 'blog_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'blog_subtitle', array(
		'selector'            => '.our-blog .section-title h3',				
		'render_callback'  => function() { return get_theme_mod( 'blog_subtitle' ); },
	) );

	// blog section sub title
	$wp_customize->add_setting(
		'blog_subtitle',
		array(
			'default' => esc_html( $default['blog_subtitle'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'blog_subtitle',
		array(
			'label'   		=> __('Sub Title','amigo-extensions'),
			'section'		=> 'blog_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'blog_text', array(
		'selector'            => '.our-blog .section-title p',				
		'render_callback'  => function() { return get_theme_mod( 'blog_text' ); },
	) );


	// blog section text
	$wp_customize->add_setting(
		'blog_text',
		array(
			'default' => esc_html( $default['blog_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'blog_text',
		array(
			'label'   		=> __('Text','amigo-extensions'),
			'section'		=> 'blog_section',
			'type' 			=> 'textarea',
			'transport'         => $selective_refresh,
		)  
	);

}