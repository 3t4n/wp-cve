<?php
function cozigo_header_settings( $wp_customize ) {
	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
  // Header Careers 
	$wp_customize->add_setting(
		'abv_hdr_careers_heads'
		,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
		)
	);

	$wp_customize->add_control(
		'abv_hdr_careers_heads',
		array(
			'type' => 'hidden',
			'label' => __('Careers','cozigo'),
			'section' => 'above_header',
			'priority'  => 9,
		)
	);	
	
	$wp_customize->add_setting( 
		'hide_show_hdr_careers' , 
		array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
		'hide_show_hdr_careers', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozigo'),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 9,
		) 
	);	

	$wp_customize->add_setting(
		'hdr_careers_icon',
		array(
			'default' => 'fa-briefcase',   
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Cozipress_Icon_Picker_Control($wp_customize, 
		'hdr_careers_icon',
		array(
			'label'   		=> __('Icon','cozigo'),
			'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 9,
			
		))  
);	

	$wp_customize->add_setting(
		'hdr_careers_ttl',
		array(
			'default'			=> __('Careers','cozigo'),
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_careers_ttl',
		array(
			'label'   		=> __('Text','cozigo'),
			'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 9,
		)  
	);	

	$wp_customize->add_setting(
		'hdr_careers_url',
		array(
			'default'			=> '',
			'sanitize_callback' => 'cozipress_sanitize_url',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_careers_url',
		array(
			'label'   		=> __('Link','cozigo'),
			'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 9,
		)  
	);

	// Header Email 
	$wp_customize->add_setting(
		'abv_hdr_email_heads'
		,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
		)
	);

	$wp_customize->add_control(
		'abv_hdr_email_heads',
		array(
			'type' => 'hidden',
			'label' => __('Email Us','cozigo'),
			'section' => 'above_header',
			'priority'  => 9,
		)
	);

	$wp_customize->add_setting( 
		'hide_show_hdr_email' , 
		array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
		'hide_show_hdr_email', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozigo'),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 9,
		) 
	);

	$wp_customize->add_setting(
		'hdr_email_icon',
		array(
			'default' => 'fa-envelope-o',   
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Cozipress_Icon_Picker_Control($wp_customize, 
		'hdr_email_icon',
		array(
			'label'   		=> __('Icon','cozigo'),
			'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 9,
			
		))  
);

	$wp_customize->add_setting(
		'hdr_email_ttl',
		array(
			'default'			=> __('Email Us','cozigo'),
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_email_ttl',
		array(
			'label'   		=> __('Text','cozigo'),
			'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 9,
		)  
	);	

	$wp_customize->add_setting(
		'hdr_email_url',
		array(
			'default'			=> '',
			'sanitize_callback' => 'cozipress_sanitize_url',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_email_url',
		array(
			'label'   		=> __('Link','cozigo'),
			'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 9,
		)  
	);		

}
add_action( 'customize_register', 'cozigo_header_settings' );


// Careers selective refresh
function cozigo_header_careers_section_partials( $wp_customize ){	
	// Careers title
	$wp_customize->selective_refresh->add_partial( 'hdr_careers_ttl', array(
		'selector'            => '.careers-ttl',
		'settings'            => 'hdr_careers_ttl',
		'render_callback'  => 'cozigo_careers_title_render_callback',

	) );

	// Email title
	$wp_customize->selective_refresh->add_partial( 'hdr_email_ttl', array(
		'selector'            => '.email-ttl',
		'settings'            => 'hdr_email_ttl',
		'render_callback'  => 'cozigo_email_title_render_callback',

	) );
}

add_action( 'customize_register', 'cozigo_header_careers_section_partials' );

	// Careers title
function cozigo_careers_title_render_callback() {
	return get_theme_mod( 'hdr_careers_ttl' );
}

		// Email title
function cozigo_email_title_render_callback() {
	return get_theme_mod( 'hdr_email_ttl' );
}