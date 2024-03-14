<?php if ( ! function_exists( 'icycp_industryup_contact_info_customize_register' ) ) :
function icycp_industryup_contact_info_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';


/* Services section */
	$wp_customize->add_section( 'contact_info_section' , array(
		'title'      => __('Contact Info settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 2,
	) );


	$wp_customize->add_setting( 'contact_info_section_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_industryup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Industryup_Toggle_Switch_Custom_control( $wp_customize, 'contact_info_section_show',
		   array(
			  'label' => esc_html__( 'Conact Info Enable/Disable' ),
			  'section' => 'contact_info_section'
		   )
		) );


		// contact icon feature setting
		$wp_customize->add_setting( 'contact_one_icon',array(
		'default' => 'fa-map-marker',
		));	
		$wp_customize->add_control( 'contact_one_icon',array(
		'label'   => __('Contact Icon','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
		));	
		
		
		
		// conact section title
		$wp_customize->add_setting( 'contact_one_title',array(
		'default' => __('Head Office','icyclub'),
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'contact_one_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
		));	
		
		//conatct section discription
		$wp_customize->add_setting( 'contact_one_description',array(
		'default' => '4578 Marmora Road, Glasgow',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'contact_one_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'textarea',
		));


		//Contact Icon two settings
		$wp_customize->add_setting( 'contact_two_icon',array(
		'default' => 'fa-phone',
		));	
		$wp_customize->add_control( 'contact_two_icon',array(
		'label'   => __('Contact Icon','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
		));	
		
		
		
		// Service section title
		$wp_customize->add_setting( 'contact_two_title',array(
		'default' => __('Call Us','icyclub'),
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'contact_two_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
		));	
		
		//Service section discription
		$wp_customize->add_setting( 'contact_two_description',array(
		'default' => '(+81) 123-456-7890',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'contact_two_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'textarea',
		));

		//Contact Icon three settings
		$wp_customize->add_setting( 'contact_three_icon',array(
		'default' => 'fa-envelope-open',
		));	
		$wp_customize->add_control( 'contact_three_icon',array(
		'label'   => __('Contact Icon','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
		));
		
		
		
		// contact section title
		$wp_customize->add_setting( 'contact_three_title',array(
		'default' => __('7:30 AM - 7:30 PM','icyclub'),
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'contact_three_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
		));	
		
		//Service section discription
		$wp_customize->add_setting( 'contact_three_description',array(
		'default' => 'Monday to Saturday',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'contact_three_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'textarea',
		));



}

add_action( 'customize_register', 'icycp_industryup_contact_info_customize_register' );
endif;


// contcat selective refresh
function consultco_contact_section_partials( $wp_customize ){	
	// contact icon
	$wp_customize->selective_refresh->add_partial( 'contact_one_icon', array(
		'selector'            => '.contact-info-one i',
		'settings'            => 'contact_one_icon',
		'render_callback'  => 'consultco_contact_one_icon_render_callback',
	
	) );
	
	// contact title 
	$wp_customize->selective_refresh->add_partial( 'contact_one_title', array(
		'selector'            => '.contact-info-one h5',
		'settings'            => 'contact_one_title',
		'render_callback'  => 'consultco_contact_one_title_render_callback',
	
	) );


	$wp_customize->selective_refresh->add_partial( 'contact_two_icon', array(
		'selector'            => '.contact-info-two i',
		'settings'            => 'contact_two_icon',
		'render_callback'  => 'consultco_contact_two_icon_render_callback',
	
	) );
	
	// contact title 
	$wp_customize->selective_refresh->add_partial( 'contact_two_title', array(
		'selector'            => '.contact-info-two h5',
		'settings'            => 'contact_two_title',
		'render_callback'  => 'consultco_contact_two_title_render_callback',
	
	) );


	$wp_customize->selective_refresh->add_partial( 'contact_three_icon', array(
		'selector'            => '.contact-info-three i',
		'settings'            => 'contact_three_icon',
		'render_callback'  => 'consultco_contact_three_icon_render_callback',
	
	) );
	
	// contact title 
	$wp_customize->selective_refresh->add_partial( 'contact_three_title', array(
		'selector'            => '.contact-info-three h5',
		'settings'            => 'contact_three_title',
		'render_callback'  => 'consultco_contact_three_title_render_callback',
	
	) );
	
	
	
	}

add_action( 'customize_register', 'consultco_contact_section_partials' );

// contact_one_icon
function consultco_contact_one_icon_render_callback() {
	return get_theme_mod( 'contact_one_icon' );
}