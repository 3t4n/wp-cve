<?php
/**
*Team customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_pricing_table_options_register')) {
	function walker_pricing_table_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() ) {
		$wp_customize->add_section('walker_core_pricing_table_options', 
		 	array(
		        'title' => esc_html__('Pricing Table', 'walker-core'),
		        'panel' =>'gridchamp_frontpage_option',
		        'priority' => 14,
		        'divider' => 'before',
	    	)
		 );
		$wp_customize->add_setting( 'pricing_table_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'pricing_table_status', 
			array(
			  'label'   => esc_html__( 'Enable Pricing Table', 'walker-core' ),
			  'section' => 'walker_core_pricing_table_options',
			  'settings' => 'pricing_table_status',
			  'type'    => 'checkbox',
			   'priority' => 1,
			)
		);
		$wp_customize->add_setting( 'pricing_table_bg_color', 
			array(
		        'default'        => '#f2fbfc',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'pricing_table_bg_color', 
			array(
		        'label'   => esc_html__( 'Section Color & Settings', 'walker-core' ),
		        'description' => esc_html__('Background color','walker-core'),
		        'section' => 'walker_core_pricing_table_options',
		        'settings'   => 'pricing_table_bg_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'pricing_table_status', true );
				},

		    ) ) 
		);
	    
	     $wp_customize->add_setting( 'pricing_table_header_color', 
			array(
		        'default'        => '#000000',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'pricing_table_header_color', 
			array(
				'label' => '',
		        'description'   => esc_html__( 'Heading Color', 'walker-core' ),
		        'section' => 'walker_core_pricing_table_options',
		        'settings'   => 'pricing_table_header_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'pricing_table_status', true );
				},

		    ) ) 
		);
	    $wp_customize->add_setting( 'pricing_table_text_color', 
			array(
		        'default'        => '#727272',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'pricing_table_text_color', 
			array(
				'label' => '',
		        'description'   => esc_html__( 'Text color', 'walker-core' ),
		        'section' => 'walker_core_pricing_table_options',
		        'settings'   => 'pricing_table_text_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'pricing_table_status', true );
				},

		    ) ) 
		);
		$wp_customize->add_setting( 'pricing_table_section_heading_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'pricing_table_section_heading_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_pricing_table_options',
				'label' => esc_html__( 'Heading','walker-core' ),
				'description' => '',
				 'priority' => 2,
				'active_callback' => function(){
				    return get_theme_mod( 'pricing_table_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'pricing_table_section_desc_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'pricing_table_section_desc_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_pricing_table_options',
				'label' => esc_html__( 'Description','walker-core' ),
				'description' => '',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'pricing_table_status', true );
				},
			)
		);
	    
		$wp_customize->add_setting( 'gridchamp_pricing_section_padding_top', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'walker_core_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_pricing_section_padding_top', 
			array(
				'type' => 'number',
				'section' => 'walker_core_pricing_table_options',
				'settings' => 'gridchamp_pricing_section_padding_top',
				'label' => esc_html__( 'Section Top Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
				    return get_theme_mod( 'pricing_table_status', true );
				},
			) 
		);
		$wp_customize->add_setting( 'gridchamp_pricing_section_padding_bottom', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'gridchamp_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_pricing_section_padding_bottom', 
			array(
				'type' => 'number',
				'section' => 'walker_core_pricing_table_options',
				'settings' => 'gridchamp_pricing_section_padding_bottom',
				'label' => esc_html__( 'Section Bottom Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
				    return get_theme_mod( 'pricing_table_status', true );
				},
			) 
		);
	}
  }
}
add_action( 'customize_register', 'walker_pricing_table_options_register' );