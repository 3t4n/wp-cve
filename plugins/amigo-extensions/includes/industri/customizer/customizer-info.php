<?php 
// customizer theme Info settings
add_action( 'customize_register', 'amigo_industri_customizer_info_settings');
function amigo_industri_customizer_info_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// info section
	$wp_customize->add_section('info_section', array(
		'title'    => esc_html__( 'Info', 'amigo-extensions' ),
		'panel'	=> 'theme_homepage',
		'priority' => 1,		

	));	

	// seprator	info section settings		
	$wp_customize->add_setting('separator_info_settings', array('priority'=> 1));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_info_settings', array(
			'label' => __('Settings','amigo-extensions'),
			'settings' => 'separator_info_settings',
			'section' => 'info_section',					
		)
	));

	// is display
	$wp_customize->add_setting(
		'display_info_section',
		array(
			'default' => true,
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_info_section',
		array(
			'label'   		=> __('Show/Hide Info Section','amigo-extensions'),
			'section'		=> 'info_section',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);	

	// seprator	info section content		
	$wp_customize->add_setting('separator_info_clm', array('priority'=> 1));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_info_clm', array(
			'label' => __('Single Column','amigo-extensions'),
			'settings' => 'separator_info_clm',
			'section' => 'info_section',					
		)
	));

	// icon 
	$wp_customize->add_setting('info_clm_icon',array(
		'default' => esc_attr( $default['info_clm_icon'] ),
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
		'priority'  => 1,
	));

	$wp_customize->add_control(new amigo_Customizer_Icon_Picker_Control($wp_customize,'info_clm_icon',
		array(
			'label'   		=> __('Icon','amigo-extensions'),
			'section' 		=> 'info_section',
			'iconset' => 'fa',
		)
	));	

	// clm title
	$wp_customize->add_setting(
		'info_clm_title',
		array(
			'default' => esc_html( $default['info_clm_title'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'info_clm_title',
		array(
			'label'   		=> __('Title','amigo-extensions'),
			'section'		=> 'info_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	


	// clm subtitle
	$wp_customize->add_setting(
		'info_clm_subtitle',
		array(
			'default' => esc_html( $default['info_clm_subtitle'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'info_clm_subtitle',
		array(
			'label'   		=> __('Subtitle','amigo-extensions'),
			'section'		=> 'info_section',
			'type' 			=> 'text',
			'transport'         => $selective_refresh,
		)  
	);	

	// clm text
	$wp_customize->add_setting(
		'info_clm_text',
		array(
			'default' => esc_html( $default['info_clm_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_html',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'info_clm_text',
		array(
			'label'   		=> __('Text','amigo-extensions'),
			'section'		=> 'info_section',
			'type' 			=> 'textarea',
			'transport'         => $selective_refresh,
		)  
	);	

	// seprator	info section content		
	$wp_customize->add_setting('separator_info_content', array('priority'=> 2));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_info_content', array(
			'label' => __('Content','amigo-extensions'),
			'settings' => 'separator_info_content',
			'section' => 'info_section',					
		)
	));

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'info_items', array(
		'selector'            => '.info-section .container',				
		'render_callback'  => function() { return get_theme_mod( 'info_items' ); },
	) );	

	// info content
	$wp_customize->add_setting( 'info_items', array(
		'sanitize_callback' => 'amigo_repeater_sanitize',
		'default' => amigo_industri_default_info_items(),
		'priority' => 2,
	));

	$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'info_items', array(
		'label'   => esc_html__('info Items','amigo-extensions'),
		'item_name' => esc_html__( 'Item', 'amigo-extensions' ),
		'section' => 'info_section',		
		'customizer_repeater_image_control' => false,
		'customizer_repeater_title_control' => true,
		'customizer_repeater_subtitle_control' => true,
		'customizer_repeater_text_control' => true,
		'customizer_repeater_link_control' => false,
		'customizer_repeater_text2_control'=> false,		
		'customizer_repeater_link2_control' => false,
		'customizer_repeater_button2_control' => false,
		'customizer_repeater_slide_align' => false,
		'customizer_repeater_icon_control' => true,		
		'customizer_repeater_checkbox_control' => false,
	) ) );

}