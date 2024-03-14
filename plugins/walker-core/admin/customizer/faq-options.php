<?php
/**
*Brands customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_faqs_options_register')) {
	function walker_faqs_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() && !get_theme_mod('disable_walker_core_faq')) {
		$wp_customize->add_section('walker_core_faqs_options', 
		 	array(
		        'title' => esc_html__('FAQs', 'walker-core'),
		        'panel' =>'gridchamp_frontpage_option',
		        'priority' => 15,
		        'divider' => 'before',
	    	)
		 );

		$wp_customize->add_setting( 'faq_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'faq_status', 
			array(
			  'label'   => esc_html__( 'Enable FAQs', 'walker-core' ),
			  'section' => 'walker_core_faqs_options',
			  'settings' => 'faq_status',
			  'type'    => 'checkbox',
			   'priority' => 1,
			)
		);
		$wp_customize->selective_refresh->add_partial( 'faq_status', array(
            'selector' => '.faqs-wraper h1.section-heading',
        ) );
		$wp_customize->add_setting( 
	        'walker_faq_layout', 
	        array(
	            'default'           => 'faq-layout-1',
	            'sanitize_callback' => 'gridchamp_sanitize_choices'
	        ) 
	    );
	    
	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'walker_faq_layout',
				array(
					'section'	  => 'walker_core_faqs_options',
					'label'		  => esc_html__( 'Choose Section Layout', 'gridchamp' ),
					'description' => '',
					'type'        => 'select',
					'priority'	  => 2,
					'choices'	  => array(
						'faq-layout-1' => esc_html__('Layout 1','walker-core'),
						'faq-layout-2' => esc_html__('Layout 2','walker-core'),
					),
					'active_callback' => function(){
				    	return get_theme_mod( 'faq_status', true );
					},
				)
			)
		);
		$wp_customize->add_setting( 'faqs_total_items', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => 3,
				'sanitize_callback' => 'walker_core_sanitize_number_absint',
			) 
		);
		$wp_customize->add_control( 'faqs_total_items', 
			array(
				'type' => 'number',
				'section' => 'walker_core_faqs_options',
				'label' => esc_html__( 'Total Items to Show','walker-core' ),
				'description' => '',
				'active_callback' => function(){
				    return get_theme_mod( 'faq_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'faqs_heading_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'faqs_heading_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_faqs_options',
				'label' => esc_html__( 'Heading','walker-core' ),
				'description' => '',
				 'priority' => 2,
				'active_callback' => function(){
				    return get_theme_mod( 'faq_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'faq_desc_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'faq_desc_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_faqs_options',
				'label' => esc_html__( 'Description','walker-core' ),
				'description' => '',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'faq_status', true );
				},
			)
		);

		$wp_customize->add_setting( 'faq_viewall_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'faq_viewall_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_faqs_options',
				'label' => esc_html__( 'View all button Text','walker-core' ),
				'description' =>'',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'faq_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'faqs_viewall_btn_link', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'faqs_viewall_btn_link', 
			array(
				'type' => 'text',
				'section' => 'walker_core_faqs_options',
				'label' => esc_html__( 'View all button link','walker-core' ),
				'description' => '',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'faq_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'gridchamp_faq_section_padding_top', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'walker_core_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_faq_section_padding_top', 
			array(
				'type' => 'number',
				'section' => 'walker_core_faqs_options',
				'settings' => 'gridchamp_faq_section_padding_top',
				'label' => esc_html__( 'Section Top Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
			    	return get_theme_mod( 'faq_status', true );
				},
			) 
		);
		$wp_customize->add_setting( 'gridchamp_faq_section_padding_bottom', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'gridchamp_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_faq_section_padding_bottom', 
			array(
				'type' => 'number',
				'section' => 'walker_core_faqs_options',
				'settings' => 'gridchamp_faq_section_padding_bottom',
				'label' => esc_html__( 'Section Bottom Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
			    	return get_theme_mod( 'faq_status', true );
				},
			) 
		);
		/*FAQ listing Page*/
		$wp_customize->add_section('walker_core_faqs_inner_options', 
		 	array(
		        'title' => esc_html__('FAQs Page Settings', 'walker-core'),
		        'panel' =>'gridchamp_theme_option',
		        'priority' => 110,
		        'divider' => 'before',
	    	)
		 );

		$wp_customize->add_setting( 'faqs_page_heading_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'faqs_page_heading_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_faqs_inner_options',
				'label' => esc_html__( 'Heading','walker-core' ),
				'description' => '',
				 'priority' => 2,
			)
		);
		$wp_customize->add_setting( 'faq_page_desc_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'faq_page_desc_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_faqs_inner_options',
				'label' => esc_html__( 'Description','walker-core' ),
				'description' => '',
				'priority' => 3,
				
			)
		);

	}
}
}
add_action( 'customize_register', 'walker_faqs_options_register' );