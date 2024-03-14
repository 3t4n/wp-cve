<?php
if ( ! function_exists( 'spiceb_cloudpress_cta_customize_register' ) ) :
function spiceb_cloudpress_cta_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

	/* cta Section */
	$wp_customize->add_section( 'cta_section' , array(
			'title'      => __('CTA Settings', 'spicebox'),
			'panel'  => 'section_settings',
			'priority'   => 2,
	) );
		
	// Enable cta section
	$wp_customize->add_setting( 'cta_section_enable' , array( 'default' => 'on') );
	$wp_customize->add_control(	'cta_section_enable' , array(
			'label'    => __( 'Enable Home CTA Section', 'spicebox' ),
			'section'  => 'cta_section',
			'type'     => 'radio',
			'choices' => array(
				'on'=>__('ON', 'spicebox'),
				'off'=>__('OFF', 'spicebox')
			)
	));
		
	// cta section title
	$wp_customize->add_setting( 'home_cta_section_title',array(
	'capability'     => 'edit_theme_options',
	'default' => __('Lorem ipsum dolor sit amet?','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_cta_section_title',array(
	'label'   => __('Title','spicebox'),
	'section' => 'cta_section',
	'type' => 'text',
	));	
		
	//cta section discription
	$wp_customize->add_setting( 'home_cta_section_discription',array(
	'capability'     => 'edit_theme_options',
	'default'=> __('Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_cta_section_discription',array(
	'label'   => __('Sub Title','spicebox'),
	'section' => 'cta_section',
	'type' => 'text',
	));
			
	$wp_customize->add_setting( 'home_cta_btn',array(
	'capability'     => 'edit_theme_options',
	'default' => __('Nemo enim','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',

	));	
	$wp_customize->add_control( 'home_cta_btn',array(
	'label'   => __('Button','spicebox'),
	'section' => 'cta_section',
	'type' => 'text',
	));	

	// CTA button link
	$wp_customize->add_setting( 'home_cta_btn_link',array(
	'default' => __('#','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_cta_btn_link',array(
	'label'   => __('Button Link','spicebox'),
	'section' => 'cta_section',
	'type' => 'text',
	));

	//Callout Button Target Blank
	$wp_customize->add_setting('home_call_out_btn_link_target',
			array(
				'default' => false,
				'sanitize_callback' => 'sanitize_text_field',
			));
	$wp_customize->add_control(
			'home_call_out_btn_link_target',
			array(
				'type' => 'checkbox',
				'label' => __('Open link in new tab','cloudpress'),
				'section' => 'cta_section',
			)
		);
}

add_action( 'customize_register', 'spiceb_cloudpress_cta_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_cloudpress_register_home_cta_section_partials( $wp_customize ){
	
	//cta
	$wp_customize->selective_refresh->add_partial( 'home_cta_section_title', array(
		'selector'            => '.section-module.call-to-action-one .title',
		'settings'            => 'home_cta_section_title',
		'render_callback'  => 'spiceb_cloudpress_cta_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_cta_section_discription', array(
		'selector'            => '.section-module.call-to-action-one p',
		'settings'            => 'home_cta_section_discription',
		'render_callback'  => 'spiceb_cloudpress_cta_section_discription_render_callback',
	
	) );
	
	// $wp_customize->selective_refresh->add_partial( 'home_cta_desc', array(
	// 	'selector'            => '.testmonial-block.text-center .text-white',
	// 	'settings'            => 'home_cta_desc',
	// 	'render_callback'  => 'spiceb_cloudpress_cta_desc_render_callback',
	
	// ) );

	// $wp_customize->selective_refresh->add_partial( 'home_cta_title', array(
	// 	'selector'            => '.section-module.cta .name',
	// 	'settings'            => 'home_cta_title',
	// 	'render_callback'  => 'spiceb_cloudpress_cta_title_render_callback',
	
	// ) );
	
	$wp_customize->selective_refresh->add_partial( 'home_cta_btn', array(
		'selector'            => '.section-module.call-to-action-one .pbottom-5',
		'settings'            => 'home_cta_btn',
		'render_callback'  => 'spiceb_cloudpress_cta_designation_render_callback',
	
	) );
	
	// $wp_customize->selective_refresh->add_partial( 'home_cta_thumb', array(
	// 	'selector'            => '.section.cta-section .avatar ',
	// 	'settings'            => 'home_cta_thumb',
	
	// ) );
}

add_action( 'customize_register', 'spiceb_cloudpress_register_home_cta_section_partials' );

function spiceb_cloudpress_cta_section_title_render_callback() {
	return get_theme_mod( 'home_cta_section_title' );
}

function spiceb_cloudpress_cta_section_discription_render_callback() {
	return get_theme_mod( 'home_cta_section_discription' );
}

// function spiceb_cloudpress_cta_desc_render_callback() {
// 	return get_theme_mod( 'home_cta_desc' );
// }

// function spiceb_cloudpress_cta_title_render_callback() {
// 	return get_theme_mod( 'home_cta_title' );
// }

function spiceb_cloudpress_cta_designation_render_callback() {
	return get_theme_mod( 'home_cta_btn' );
}
?>