<?php
function cozigo_marquee_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Marquee Section
	=========================================*/
	$wp_customize->add_section(
		'marquee_setting', array(
			'title' => esc_html__( 'Marquee Section', 'cozigo' ),
			'priority' => 3,
			'panel' => 'cozipress_frontpage_sections',
		)
	);

	// Marquee Settings Section // 
	$wp_customize->add_setting(
		'marquee_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'marquee_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozigo'),
			'section' => 'marquee_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_marquee' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_marquee', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozigo' ),
			'section'     => 'marquee_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Marquee Header Section // 
	$wp_customize->add_setting(
		'marquee_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'marquee_headings',
		array(
			'type' => 'hidden',
			'label' => __('Marquee title','cozigo'),
			'section' => 'marquee_setting',
		)
	);

	// Marquee Description // 
	$wp_customize->add_setting(
    	'marquee_description',
    	array(
	        'default'			=> __('* Advertising * Development * Design * Business * Marketing * Consultant * Advertising * Development * Design * Business * Marketing * Consultant','cozigo'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'marquee_description',
		array(
		    'label'   => __('Description','cozigo'),
		    'section' => 'marquee_setting',
			'type'           => 'textarea',
		)  
	);		

}
add_action( 'customize_register', 'cozigo_marquee_setting' );


function cozigo_home_marquee_section_partials( $wp_customize ){	
	
	// marquee description
	$wp_customize->selective_refresh->add_partial( 'marquee_description', array(
		'selector'            => '.text-marquee_home_section .marquee-title',
		'settings'            => 'marquee_description',
		'render_callback'  => 'cozigo_marquee_desc_render_callback',
	) );

	}

add_action( 'customize_register', 'cozigo_home_marquee_section_partials' );

// marquee description
function cozigo_marquee_desc_render_callback() {
	return get_theme_mod( 'marquee_description' );
}