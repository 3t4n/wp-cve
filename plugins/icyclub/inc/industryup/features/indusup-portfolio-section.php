<?php //Project Section
if ( ! function_exists( 'icycp_industryup_project_customizer' ) ) :
function icycp_industryup_project_customizer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	/* project Section */
	$wp_customize->add_section( 'project_section' , array(
			'title'      => __('Project/Portfolio settings', 'industryup'),
			'panel'  => 'homepage_sections',
			'priority'   => 3,
		) );
		
		$wp_customize->add_setting( 'project_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_industryup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Industryup_Toggle_Switch_Custom_control( $wp_customize, 'project_section_enable',
		   array(
			  'label' => esc_html__( 'Project Enable/Disable' ),
			  'section' => 'project_section'
		   )
		) );


		// project section title
		$wp_customize->add_setting( 'portfolio_section_subtitle',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'default' => __('OUR PORTFOLIO','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'portfolio_section_subtitle',array(
		'label'   => __('Subtitle','icyclub'),
		'section' => 'project_section',
		'type' => 'text',
		));	
		
		// project section title
		$wp_customize->add_setting( 'portfolio_section_title',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'default' => __('Our Portfolio','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'portfolio_section_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'project_section',
		'type' => 'text',
		));	
		
		//project section discription
		$wp_customize->add_setting( 'portfolio_section_discription',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'default' => 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'portfolio_section_discription',array(
		'label'   => __('Description','industryup'),
		'section' => 'project_section',
		'type' => 'textarea',
		));	
	 
	 
		//project one image
		$wp_customize->add_setting( 'project_image_one',array('default' => ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio1.jpg',
		'sanitize_callback' => 'esc_url_raw', 
		//'transport' => $selective_refresh, 
	));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_one',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_one',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		
		//project one Title
		$wp_customize->add_setting(
		'project_title_one', array(
			'default'        => __('Financial Project','industryup'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_one', array(
			'label'   => __('Title', 'icyclub'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		
		
		
		//project two image
		$wp_customize->add_setting( 'project_image_two',array('default' => ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio2.jpg',
		'sanitize_callback' => 'esc_url_raw',
		//'transport'         => $selective_refresh,
	));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_two',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_two',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		
		//project two Title
		$wp_customize->add_setting(
		'project_title_two', array(
			'default'        => __('Investment','icyclub'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_two', array(
			'label'   => __('Title', 'industryup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		
		//project three image
		$wp_customize->add_setting( 'project_image_three',array('default' => ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio3.jpg',
		'sanitize_callback' => 'esc_url_raw',
		//'transport'         => $selective_refresh,
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_three',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_three',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		//Portfolio three Title
		$wp_customize->add_setting(
		'project_title_three', array(
			'default'        => __('Invoicing','icyclub'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_three', array(
			'label'   => __('Title', 'industryup'),
			'section' => 'project_section',
			'type' => 'text',
		));


		//project three image
		$wp_customize->add_setting( 'project_image_four',array('default' => ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio4.jpg',
		'sanitize_callback' => 'esc_url_raw',
		//'transport'         => $selective_refresh,
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_four',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_four',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		//Portfolio three Title
		$wp_customize->add_setting(
		'project_title_four', array(
			'default'        => __('Team Management','icyclub'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_four', array(
			'label'   => __('Title', 'industryup'),
			'section' => 'project_section',
			'type' => 'text',
		));

}		
add_action( 'customize_register', 'icycp_industryup_project_customizer' );
endif;


/**
 * Add selective refresh for project section.
 */
function icycp_industryup_register_project_section_partials( $wp_customize ){

	
	//Portfolio section

	$wp_customize->selective_refresh->add_partial( 'portfolio_section_subtitle', array(
		'selector'            => '.portfolios h3',
		'settings'            => 'portfolio_section_subtitle',
		'render_callback'  => 'icycp_industryup_portfolio_section_subtitle_render_callback',
	
	) );


	$wp_customize->selective_refresh->add_partial( 'portfolio_section_title', array(
		'selector'            => '.portfolios h2',
		'settings'            => 'portfolio_section_title',
		'render_callback'  => 'icycp_industryup_portfolio_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'portfolio_section_discription', array(
		'selector'            => '.portfolios p',
		'settings'            => 'portfolio_section_discription',
		'render_callback'  => 'icycp_industryup_portfolio_section_discription_render_callback',
	
	) );
	
	
	
	$wp_customize->selective_refresh->add_partial( 'project_title_one', array(
		'selector'            => '.project-one h2 a',
		'settings'            => 'project_title_one',
		'render_callback'  => 'icycp_industryup_project_title_one_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'project_title_two', array(
		'selector'            => '.project-two h2 a',
		'settings'            => 'project_title_two',
		'render_callback'  => 'icycp_industryup_project_title_two_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'project_title_three', array(
		'selector'            => '.project-three h2 a',
		'settings'            => 'project_title_three',
		'render_callback'  => 'icycp_industryup_project_title_three_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'project_title_four', array(
		'selector'            => '.project-four h2 a',
		'settings'            => 'project_title_four',
		'render_callback'  => 'icycp_industryup_project_title_four_render_callback',
	
	) );
	
	
	
}

add_action( 'customize_register', 'icycp_industryup_register_project_section_partials' );

//Project Section
function icycp_industryup_portfolio_section_subtitle_render_callback() {
	return get_theme_mod( 'portfolio_section_subtitle' );
}



function icycp_industryup_portfolio_section_title_render_callback() {
	return get_theme_mod( 'portfolio_section_title' );
}

function icycp_industryup_portfolio_section_discription_render_callback() {
	return get_theme_mod( 'portfolio_section_discription' );
}

//Project


function icycp_industryup_project_title_one_render_callback() {
	return get_theme_mod( 'project_title_one' );
}


function icycp_industryup_project_title_two_render_callback() {
	return get_theme_mod( 'project_title_two' );
}


function icycp_industryup_project_title_three_render_callback() {
	return get_theme_mod( 'project_title_three' );
}

function icycp_industryup_project_title_four_render_callback() {
	return get_theme_mod( 'project_title_four' );
}