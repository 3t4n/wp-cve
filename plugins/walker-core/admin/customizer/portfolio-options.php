<?php
/**
*Team customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_portfolio_options_register')) {
	function walker_portfolio_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() && !get_theme_mod('disable_walker_core_portfolio') ) {
		$wp_customize->add_section('walker_core_portfolio_options', 
		
		 	array(
		        'title' => esc_html__('Portfolio', 'walker-core'),
		        'panel' =>'gridchamp_frontpage_option',
		        'priority' => 13,
		        'divider' => 'before',
	    	)
		 );
		$wp_customize->add_setting( 'portfolio_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'portfolio_status', 
			array(
			  'label'   => esc_html__( 'Enable Portfolio', 'walker-core' ),
			  'section' => 'walker_core_portfolio_options',
			  'settings' => 'portfolio_status',
			  'type'    => 'checkbox',
			   'priority' => 1,
			)
		);
		$wp_customize->selective_refresh->add_partial( 'portfolio_status', array(
            'selector' => '.portfolio-wraper h1.section-heading',
        ) );
		$wp_customize->add_setting( 
	        'walker_portfolio_layout', 
	        array(
	            'default'           => 'carousel-layout',
	            'sanitize_callback' => 'walker_core_sanitize_choices'
	        ) 
	    );
	    
	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'walker_portfolio_layout',
				array(
					'section'	  => 'walker_core_portfolio_options',
					'label'		  => esc_html__( 'Choose Section Layout', 'walker-core' ),
					'description' => '',
					'type'        => 'select',
					'priority'	  => 2,
					'choices'	  => array(
						'carousel-layout'  => esc_html__('Carousel Layout','walker-core'),
						'grid-layout'  => esc_html__('Grid Layout','walker-core'),
					),
					'active_callback' => function(){
				    	return get_theme_mod( 'portfolio_status', true );
					},
				)
			)
		);
		$wp_customize->add_setting( 'enable_portfolio_full_width_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'enable_portfolio_full_width_status', 
			array(
			  'label'   => esc_html__( 'Enable Full Width Layout', 'walker-core' ),
			  'section' => 'walker_core_portfolio_options',
			  'settings' => 'enable_portfolio_full_width_status',
			  'type'    => 'checkbox',
			  'priority'	  => 2,
			   'active_callback' => 'gridchamp_portfolio_full_width_check',
			)
		);
		$wp_customize->add_setting( 'portfolio_heading_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'portfolio_heading_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_portfolio_options',
				'label' => esc_html__( 'Heading','walker-core' ),
				'description' => '',
				 'priority' => 2,
				'active_callback' => function(){
				    return get_theme_mod( 'portfolio_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'portfolio_total_items', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_number_absint',
			) 
		);
		$wp_customize->add_control( 'portfolio_total_items', 
			array(
				'type' => 'number',
				'section' => 'walker_core_portfolio_options',
				'label' => esc_html__( 'Total Items to Show','walker-core' ),
				'description' => '',
				 'priority' => 2,
				'active_callback' => function(){
				    return get_theme_mod( 'portfolio_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'portfolio_desc_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'portfolio_desc_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_portfolio_options',
				'label' => esc_html__( 'Description','walker-core' ),
				'description' => '',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'portfolio_status', true );
				},
			)
		);

		$wp_customize->add_setting( 'portfolio_btn_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'portfolio_btn_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_portfolio_options',
				'label' => esc_html__( 'More Text','walker-core' ),
				'description' =>'',
				 'priority' => 5,
				'active_callback' => function(){
				    return get_theme_mod( 'portfolio_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'portfolio_btn_url', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_url',
			) 
		);
		$wp_customize->add_control( 'portfolio_btn_url', 
			array(
				'type' => 'text',
				'section' => 'walker_core_portfolio_options',
				'label' => esc_html__( 'Button Link','walker-core' ),
				 'priority' => 6,
				'active_callback' => function(){
				    return get_theme_mod( 'portfolio_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'portfolio_btn_target', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'portfolio_btn_target', 
			array(
				'label'   => esc_html__( 'Open in New Tab', 'walker-core' ),
				'section' => 'walker_core_portfolio_options',
				'settings' => 'portfolio_btn_target',
				'type'    => 'checkbox',
				'priority' => 8,
				'active_callback' => function(){
				    return get_theme_mod( 'portfolio_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'gridchamp_portfolio_section_padding_top', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'walker_core_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_portfolio_section_padding_top', 
			array(
				'type' => 'number',
				'section' => 'walker_core_portfolio_options',
				'settings' => 'gridchamp_portfolio_section_padding_top',
				'label' => esc_html__( 'Section Top Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
			    	return get_theme_mod( 'portfolio_status', true );
				},
			) 
		);
		$wp_customize->add_setting( 'gridchamp_portfolio_section_padding_bottom', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'gridchamp_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_portfolio_section_padding_bottom', 
			array(
				'type' => 'number',
				'section' => 'walker_core_portfolio_options',
				'settings' => 'gridchamp_portfolio_section_padding_bottom',
				'label' => esc_html__( 'Section Bottom Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
			    	return get_theme_mod( 'portfolio_status', true );
				},
			) 
		);
		
	}
	function gridchamp_portfolio_full_width_check(){
	    $gridchamp_portfolio_status= get_theme_mod( 'portfolio_status' );
	    $gridchamp_portfolio_layout_chk= get_theme_mod( 'walker_portfolio_layout' );
		$gridchamp_full_width_status = false;
		if($gridchamp_portfolio_status == true && $gridchamp_portfolio_layout_chk=='grid-layout'){
			$gridchamp_full_width_status = true;
		}
		return $gridchamp_full_width_status;
	}
}


}
add_action( 'customize_register', 'walker_portfolio_options_register' );