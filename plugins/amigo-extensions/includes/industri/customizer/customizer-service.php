<?php 
// customizer theme service settings
add_action( 'customize_register', 'amigo_industri_customizer_service_settings');
function amigo_industri_customizer_service_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// about section
	$wp_customize->add_section('service_section', array(
		'title'    => esc_html__( 'Services', 'amigo-extensions' ),
		'panel'	=> 'theme_homepage',
		'priority' => 1,		

	));	

	// seprator	service section settings		
	$wp_customize->add_setting('separator_service_settings', array('priority'=> 1));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_service_settings', array(
			'label' => __('Settings','amigo-extensions'),
			'settings' => 'separator_service_settings',
			'section' => 'service_section',					
		)
	));

	// is display
	$wp_customize->add_setting(
		'display_service_section',
		array(
			'default' => true,
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_service_section',
		array(
			'label'   		=> __('Show/Hide Service Section','amigo-extensions'),
			'section'		=> 'service_section',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.service-section .section-title h5',				
		'render_callback'  => function() { return get_theme_mod( 'service_title' ); },
	) );		

	// service section title
	$wp_customize->add_setting(
		'service_title',
		array(
			'default' => esc_html( $default['service_title'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'service_title',
		array(
			'label'   		=> __('Title','amigo-extensions'),
			'section'		=> 'service_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'service_subtitle', array(
		'selector'            => '.service-section .section-title h3',				
		'render_callback'  => function() { return get_theme_mod( 'service_subtitle' ); },
	) );	

	// service section sub title
	$wp_customize->add_setting(
		'service_subtitle',
		array(
			'default' => esc_html( $default['service_subtitle'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'service_subtitle',
		array(
			'label'   		=> __('Sub Title','amigo-extensions'),
			'section'		=> 'service_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'service_text', array(
		'selector'            => '.service-section .section-title p',				
		'render_callback'  => function() { return get_theme_mod( 'service_text' ); },
	) );	

	// service section text
	$wp_customize->add_setting(
		'service_text',
		array(
			'default' => esc_html( $default['service_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'service_text',
		array(
			'label'   		=> __('Text','amigo-extensions'),
			'section'		=> 'service_section',
			'type' 			=> 'textarea',
			'transport'         => $selective_refresh,
		)  
	);

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'service_button_more', array(
		'selector'            => '.service-section  button.service-btn',				
		'render_callback'  => function() { return get_theme_mod( 'service_button_more' ); },
	) );	

	// service button text
	$wp_customize->add_setting(
		'service_button_more',
		array(
			'default' => esc_html( $default['service_button_more'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'service_button_more',
		array(
			'label'   		=> __('More Button Text','amigo-extensions'),
			'section'		=> 'service_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// service button link
	$wp_customize->add_setting(
		'service_button_link',
		array(
			'default' => esc_html( $default['service_button_link'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'service_button_link',
		array(
			'label'   		=> __('Button Link','amigo-extensions'),
			'section'		=> 'service_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);

	// seprator	service section content		
	$wp_customize->add_setting('separator_service_content', array('priority'=> 2));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_service_content', array(
			'label' => __('Content','amigo-extensions'),
			'settings' => 'separator_service_content',
			'section' => 'service_section',					
		)
	));

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'service_items', array(
		'selector'            => '.service-section  .container .service-items',				
		'render_callback'  => function() { return get_theme_mod( 'service_items' ); },
	) );	

	// service content
	$wp_customize->add_setting( 'service_items', array(
		'sanitize_callback' => 'amigo_repeater_sanitize',
		'default' => amigo_industri_default_service_items(),
		'priority' => 2,
	));

	$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'service_items', array(
		'label'   => esc_html__('Service Items','amigo-extensions'),
		'item_name' => esc_html__( 'Item', 'amigo-extensions' ),
		'section' => 'service_section',		
		'customizer_repeater_image_control' => false,
		'customizer_repeater_title_control' => true,
		'customizer_repeater_subtitle_control' => false,
		'customizer_repeater_text_control' => true,
		'customizer_repeater_link_control' => true,
		'customizer_repeater_text2_control'=> true,		
		'customizer_repeater_link2_control' => false,
		'customizer_repeater_button2_control' => false,
		'customizer_repeater_slide_align' => false,
		'customizer_repeater_icon_control' => true,		
		'customizer_repeater_checkbox_control' => true,
								

	) ) );


}