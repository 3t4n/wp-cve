<?php

// Custom Page (About) 
$wp_customize->add_section( 'ecommerce_extra_about_section', array(
	'title'             => esc_html__( 'Custom Page','ecommerce-extra' ),
	'description'       => esc_html__( 'Select page to display. Ex: About , hero content etc.', 'ecommerce-extra' ),
	'panel'             => 'ecommerce_plus_home_panel',
));


//
$wp_customize->add_setting( 'ecommerce_extra_about_page', array(
	//'sanitize_callback' => 'ecommerce_extra_sanitize_select',
));


$wp_customize->selective_refresh->add_partial( 'ecommerce_extra_about_page', array(
	'selector' => '#about-us .page-section-container',
) );


$wp_customize->add_control( 'ecommerce_extra_about_page', array(
	'label'     => esc_html__( 'Select Page', 'ecommerce-plus' ),
	'section'   => 'ecommerce_extra_about_section',
	'type'		=> 'select',
	'choices'	=> ecommerce_extra_page_choices(),
) );



//Team Section
if ( class_exists( 'ecommerce_extra_customizer_repeater' ) ) {

	//
	$wp_customize->add_setting( 'ecommerce_extra_team_title', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'			=> esc_html__('Our Team', 'ecommerce-extra'),
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( 'ecommerce_extra_team_title', array(
		'label'           	=> esc_html__( 'Section Title', 'ecommerce-extra' ),
		'section'        	=> 'ecommerce_extra_team_section',
		'type'				=> 'text',
	));

	//section
	$wp_customize->add_section( 'ecommerce_extra_team_section', array(
		'title'             => esc_html__( 'Team','ecommerce-plus' ),
		'panel'             => 'ecommerce_plus_home_panel',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'ecommerce_extra_team_contents', array(
		'selector' => '.theme_team_section .section-title',
	) );
				
	$wp_customize->add_setting(  'ecommerce_extra_team_contents', array(
		'sanitize_callback' => 'ecommerce_extra_customizer_repeater_sanitize',
		'transport'         => 'postMessage',
	));
	
	// columns
	$wp_customize->add_setting( 'ecommerce_extra_about_colums', array(
		'default'          	=> 'col-md-4 col-sm-4 col-lg-4 col-xs-6',
		'sanitize_callback' => 'ecommerce_plus_sanitize_select',
	) );
	
	$wp_customize->add_control( 'ecommerce_extra_about_colums', array(
		'label'             => esc_html__( 'Number of Colums', 'ecommerce-plus' ),
		'section'           => 'ecommerce_extra_team_section',
		'type'				=> 'select',
		'choices'			=> 	array(
								"col-md-4 col-sm-4 col-lg-4 col-xs-6" 	=> 3,
								"col-md-3 col-sm-3 col-lg-3 col-xs-6" 	=> 4,
								"col-sm-2" 								=> 5,
								"col-md-2 col-sm-2 col-lg-2 col-xs-6" 	=> 6,		
							),
	));	
	
	$wp_customize->add_control(   new ecommerce_extra_customizer_repeater(  $wp_customize, 'ecommerce_extra_team_contents', array(
			'label'                                	=> esc_html__( 'Team Content', 'ecommerce-extra' ),
			'section'                              	=> 'ecommerce_extra_team_section',
			'add_field_label'                      	=> esc_html__( 'Add new Team', 'ecommerce-extra' ),
			'item_name'                            	=> esc_html__( 'Team Member', 'ecommerce-extra' ),
			'customizer_repeater_image_control'  	=> true,
			'customizer_repeater_title_control' 	=> true,
			'customizer_repeater_subtitle_control' 	=> true,
			'customizer_repeater_text_control'  	=> true,
		)
	));	  			 
}


//Testimonial Section
if ( class_exists( 'ecommerce_extra_customizer_repeater' ) ) {

	//
	$wp_customize->add_setting( 'ecommerce_extra_testimonial_title', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'			=> esc_html__('Our testimonial', 'ecommerce-extra'),
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( 'ecommerce_extra_testimonial_title', array(
		'label'           	=> esc_html__( 'Section Title', 'ecommerce-extra' ),
		'section'        	=> 'ecommerce_extra_testimonial_section',
		'type'				=> 'text',
	));
	
	//
	$wp_customize->add_section( 'ecommerce_extra_testimonial_section', array(
		'title'             => esc_html__( 'Testimonial','ecommerce-plus' ),
		'panel'             => 'ecommerce_plus_home_panel',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'ecommerce_extra_testimonial_contents', array(
		'selector' => '.theme_testimonial_section .section-title',
	) );	
					
	$wp_customize->add_setting(  'ecommerce_extra_testimonial_contents', array(
		'sanitize_callback' => 'ecommerce_extra_customizer_repeater_sanitize',			
		'transport'         => 'postMessage',
	));
	
	$wp_customize->add_control(   new ecommerce_extra_customizer_repeater(  $wp_customize, 'ecommerce_extra_testimonial_contents', array(
			'label'                                => esc_html__( 'Testimonial Content', 'ecommerce-extra' ),
			'section'                              => 'ecommerce_extra_testimonial_section',
			'add_field_label'                      => esc_html__( 'Add new Testimonial', 'ecommerce-extra' ),
			'item_name'                            => esc_html__( 'Testimonial', 'ecommerce-extra' ),
			'customizer_repeater_image_control'  	=> true,
			'customizer_repeater_title_control' 	=> true,
			'customizer_repeater_subtitle_control' 	=> true,
			'customizer_repeater_text_control'  	=> true,
			'customizer_repeater_shortcode_control' => true,
		)
	));
	
	 function ecommerce_extra_repeater_labels( $string, $id, $control ) {
		if ( $id === 'ecommerce_extra_testimonial_contents' ) {
			if ( $control === 'customizer_repeater_shortcode_control' ) {
				return esc_html__( 'Rating(1-5)','ecommerce-extra' );
			}
		}
		return $string;
	 }
	 add_filter( 'repeater_input_labels_filter','ecommerce_extra_repeater_labels', 10 , 3 );

		
}

//Service Section
if ( class_exists( 'ecommerce_extra_customizer_repeater' ) ) {


	$wp_customize->add_section( 'ecommerce_extra_service_section', array(
		'title'             => esc_html__( 'Service','ecommerce-plus' ),
		'panel'             => 'ecommerce_plus_home_panel',
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'ecommerce_extra_service_contents', array(
		'selector' => '.theme_service_section .section-title',
	) );
	
	
	//
	$wp_customize->add_setting( 'ecommerce_extra_service_title', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'			=> esc_html__('Our service', 'ecommerce-extra'),
		'transport'			=> 'postMessage',
	));
	
	$wp_customize->add_control( 'ecommerce_extra_service_title', array(
		'label'           	=> esc_html__( 'Section Title', 'ecommerce-extra' ),
		'section'        	=> 'ecommerce_extra_service_section',
		'type'				=> 'text',
	));
	
	
	//
	$wp_customize->add_setting( 'ecommerce_extra_service_style', array(
		'default'			=> 'card',
	) );
	
	$wp_customize->add_control( 'ecommerce_extra_service_style', array(
		'label'     		=> esc_html__( 'Service Box Style', 'ecommerce-plus' ),
		'section'   		=> 'ecommerce_extra_service_section',
		'type'				=> 'radio',
		'choices'			=> array(
								'card' => esc_html__('Card', 'ecommerce-plus' ),
								'list' => esc_html__('List', 'ecommerce-plus' ),
							),			  
	) );	
		
	// columns
	$wp_customize->add_setting( 'ecommerce_extra_service_colums', array(
		'default'          	=> 'col-md-4 col-sm-4 col-lg-4 col-xs-6',
		'sanitize_callback' => 'ecommerce_plus_sanitize_select',
	) );
	
	$wp_customize->add_control( 'ecommerce_extra_service_colums', array(
		'label'             => esc_html__( 'Number of Colums', 'ecommerce-plus' ),
		'section'           => 'ecommerce_extra_service_section',
		'type'				=> 'select',
		'choices'			=> 	array(
								"col-md-4 col-sm-4 col-lg-4 col-xs-6" 	=> 3,
								"col-md-3 col-sm-3 col-lg-3 col-xs-6" 	=> 4,
								"col-sm-2" 								=> 5,
								"col-md-2 col-sm-2 col-lg-2 col-xs-6" 	=> 6,		
							),
	));	
	
	//	
	$wp_customize->add_setting(  'ecommerce_extra_service_contents', array(
		'sanitize_callback' => 'ecommerce_extra_customizer_repeater_sanitize',			
		'transport'         => 'postMessage',
	));
	
	$wp_customize->add_control(   new ecommerce_extra_customizer_repeater(  $wp_customize, 'ecommerce_extra_service_contents', array(
			'label'                                => esc_html__( 'Service Content', 'ecommerce-extra' ),
			'section'                              => 'ecommerce_extra_service_section',
			'add_field_label'                      => esc_html__( 'Add new Service', 'ecommerce-extra' ),
			'item_name'                            => esc_html__( 'Service', 'ecommerce-extra' ),
			'customizer_repeater_icon_control'  => true,
			'customizer_repeater_image_control'  => true,
			'customizer_repeater_title_control' => true,
			'customizer_repeater_text_control'  => true,
			'customizer_repeater_link_control'  => true,
			'customizer_repeater_color_control' => true,
		)
	));	  			
}

