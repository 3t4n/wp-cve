<?php 
// customizer theme homepage settings
add_action( 'customize_register', 'amigo_industri_customizer_slider_settings');
function amigo_industri_customizer_slider_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// slider section
	$wp_customize->add_section('slider_section', array(
		'title'    => esc_html__( 'Slider', 'amigo-extensions' ),
		'panel'	=> 'theme_homepage',
		'priority' => 1,		

	));

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'slider_items', array(
		'selector'            => '.slider-item',				
		'render_callback'  => function() { return get_theme_mod( 'slider_items' ); },

	) );		

	// home slider
	$wp_customize->add_setting( 'slider_items', array(
		'sanitize_callback' => 'amigo_repeater_sanitize',
		'default' => amigo_industri_slider_section_default(),
	));

	$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'slider_items', array(
		'label'   => esc_html__('Slider Items','amigo-extensions'),
		'item_name' => esc_html__( 'Slide', 'amigo-extensions' ),
		'section' => 'slider_section',
		'priority' => 1,
		'customizer_repeater_image_control' => true,
		'customizer_repeater_title_control' => true,
		'customizer_repeater_subtitle_control' => true,
		'customizer_repeater_text_control' => true,
		'customizer_repeater_text2_control'=> true,
		'customizer_repeater_link_control' => true,
		'customizer_repeater_link2_control' => true,
		'customizer_repeater_button2_control' => true,
		'customizer_repeater_slide_align' => false,
		'customizer_repeater_icon_control' => true,		
		'customizer_repeater_checkbox_control' => true,
	) ) );	
}