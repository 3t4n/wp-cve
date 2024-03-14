<?php
/**
*Brands customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_brands_options_register')) {
	function walker_brands_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() && !get_theme_mod('disable_walker_core_brands') ) {
		$wp_customize->add_section('walker_core_brands_options', 
		 	array(
		        'title' => esc_html__('Brands', 'walker-core'),
		        'panel' =>'gridchamp_frontpage_option',
		        'priority' => 16,
		        'divider' => 'before',
	    	)
		 );
		$wp_customize->add_setting( 'brand_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'brand_status', 
			array(
			  'label'   => esc_html__( 'Enable Brands Logo', 'walker-core' ),
			  'section' => 'walker_core_brands_options',
			  'settings' => 'brand_status',
			  'type'    => 'checkbox',
			   'priority' => 1,
			)
		);
		$wp_customize->selective_refresh->add_partial( 'brand_status', array(
            'selector' => 'brands-wraper  .swiper-wrapper img',
        ) );
        $wp_customize->add_setting( 'enable_brand_full_width', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'enable_brand_full_width', 
			array(
			  'label'   => esc_html__( 'Enable Full Width Layout', 'walker-core' ),
			  'section' => 'walker_core_brands_options',
			  'settings' => 'enable_brand_full_width',
			  'type'    => 'checkbox',
			   'priority' => 1,
			   'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'brand_section_bg_color', 
			array(
		        'default'        => '#f2fbfc',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'brand_section_bg_color', 
			array(
		        'label'   => esc_html__( 'Section Color & settings', 'walker-core' ),
		        'description' => esc_html__('Background color','walker-core'),
		        'section' => 'walker_core_brands_options',
		        'settings'   => 'brand_section_bg_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},

		    ) ) 
		);
		$wp_customize->add_setting( 'brand_section_heading_color', 
			array(
		        'default'        => '#000000',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'brand_section_heading_color', 
			array(
				'label' => '',
		        'description'   => esc_html__( 'Heading color color', 'walker-core' ),
		        'section' => 'walker_core_brands_options',
		        'settings'   => 'brand_section_heading_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},

		    ) ) 
		);
		$wp_customize->add_setting( 'brand_section_text_color', 
			array(
		        'default'        => '#727272',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'brand_section_text_color', 
			array(
				'label' => '',
		        'description'   => esc_html__( 'Text color', 'walker-core' ),
		        'section' => 'walker_core_brands_options',
		        'settings'   => 'brand_section_text_color',
		        'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},

		    ) ) 
		);
		$wp_customize->add_setting( 'brands_heading_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'brands_heading_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_brands_options',
				'label' => esc_html__( 'Heading','walker-core' ),
				'description' => '',
				 'priority' => 2,
				'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'brands_desc_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'brands_desc_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_brands_options',
				'label' => esc_html__( 'Description','walker-core' ),
				'description' => '',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'gridchamp_brands_section_padding_top', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'walker_core_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_brands_section_padding_top', 
			array(
				'type' => 'number',
				'section' => 'walker_core_brands_options',
				'settings' => 'gridchamp_brands_section_padding_top',
				'label' => esc_html__( 'Section Top Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},
			) 
		);
		$wp_customize->add_setting( 'gridchamp_brands_section_padding_bottom', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'gridchamp_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_brands_section_padding_bottom', 
			array(
				'type' => 'number',
				'section' => 'walker_core_brands_options',
				'settings' => 'gridchamp_brands_section_padding_bottom',
				'label' => esc_html__( 'Section Bottom Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
				    return get_theme_mod( 'brand_status', true );
				},
			) 
		);
	}
}
}
add_action( 'customize_register', 'walker_brands_options_register' );