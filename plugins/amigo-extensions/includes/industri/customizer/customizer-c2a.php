<?php 
// customizer theme callout settings
add_action( 'customize_register', 'amigo_industri_customizer_callout_settings');
function amigo_industri_customizer_callout_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// callout section
	$wp_customize->add_section('c2a_section', array(
		'title'    => esc_html__( 'Call to Action', 'amigo-extensions' ),
		'panel'	=> 'theme_homepage',
		'priority' => 1,		

	));	

	// seprator	callout section settings		
	$wp_customize->add_setting('separator_callout_settings', array('priority'=> 1));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_callout_settings', array(
			'label' => __('Settings','amigo-extensions'),
			'settings' => 'separator_callout_settings',
			'section' => 'c2a_section',					
		)
	));

	// is display
	$wp_customize->add_setting(
		'display_c2a_section',
		array(
			'default' => esc_attr( $default['display_c2a_section'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_c2a_section',
		array(
			'label'   		=> __('Show/Hide Callout Section','amigo-extensions'),
			'section'		=> 'c2a_section',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'c2a_title', array(
		'selector'            => '.callout-section .section-title h3',				
		'render_callback'  => function() { return get_theme_mod( 'c2a_title' ); },
	) );		

	// callout section title
	$wp_customize->add_setting(
		'c2a_title',
		array(
			'default' => esc_html( $default['c2a_title'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'c2a_title',
		array(
			'label'   		=> __('Title','amigo-extensions'),
			'section'		=> 'c2a_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'c2a_text', array(
		'selector'            => '.callout-section .section-title p',				
		'render_callback'  => function() { return get_theme_mod( 'c2a_text' ); },
	) );	

	// callout section text
	$wp_customize->add_setting(
		'c2a_text',
		array(
			'default' => esc_html( $default['c2a_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'c2a_text',
		array(
			'label'   		=> __('Text','amigo-extensions'),
			'section'		=> 'c2a_section',
			'type' 			=> 'textarea',
			'transport'         => $selective_refresh,
		)  
	);	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'c2a_button_text', array(
		'selector'            => '.callout-section .callout-button a',				
		'render_callback'  => function() { return get_theme_mod( 'c2a_button_text' ); },
	) );

	// callout section button
	$wp_customize->add_setting(
		'c2a_button_text',
		array(
			'default' => esc_html( $default['c2a_button_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'c2a_button_text',
		array(
			'label'   		=> __('Button Text','amigo-extensions'),
			'section'		=> 'c2a_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// callout section button link
	$wp_customize->add_setting(
		'c2a_button_link',
		array(
			'default' => esc_html( $default['c2a_button_link'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'c2a_button_link',
		array(
			'label'   		=> __('Button Link','amigo-extensions'),
			'section'		=> 'c2a_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// seprator	c2a background settings		
	$wp_customize->add_setting('separator_c2a_background_settings', array('priority'=> 1));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_c2a_background_settings', array(
			'label' => __('Background','amigo-extensions'),
			'settings' => 'separator_c2a_background_settings',
			'section' => 'c2a_section',					
		)
	));

	// image
	$wp_customize->add_setting(
		'c2a_bg_image',
		array(
			'default' => esc_html( $default['c2a_bg_image'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_url',
			'priority'      => 3,
		)
	);	

	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'c2a_bg_image',
		array(
			'label'      => __( 'Image', 'amigo-extensions' ),
			'section'    => 'c2a_section',
			'settings'   => 'c2a_bg_image',					
		)
	)); 
}