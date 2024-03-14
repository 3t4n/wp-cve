<?php
/**
*Features customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_features_options_register')) {
	function walker_features_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() ) {
		$wp_customize->add_section('walker_core_feature_options', 
		 	array(
		        'title' => esc_html__('Features', 'walker-core'),
		        'panel' =>'gridchamp_frontpage_option',
		        'priority' => 4,
		        'divider' => 'before',
	    	)
		 );
		$wp_customize->add_setting( 'features_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'features_status', 
			array(
			  'label'   => esc_html__( 'Enable Features', 'walker-core' ),
			  'section' => 'walker_core_feature_options',
			  'settings' => 'features_status',
			  'type'    => 'checkbox',
			   'priority' => 1,
			)
		);
		$wp_customize->selective_refresh->add_partial( 'features_status', array(
            'selector' => '.features-wraper .features-list',
        ) );
		$wp_customize->add_setting( 'gridchamp_feature_page', array(
		        'default' => '',
		        'capability' => 'edit_theme_options',
		        'sanitize_callback' =>'gridchamp_sanitize_text'
		        ));
		    $wp_customize->add_control(
				new Walker_Core_Dropdown_Pages_Control($wp_customize, 
				'gridchamp_feature_page',
			    	array(
						'label'    => esc_html__( 'Select Parent Page', 'walker-core' ),
						'description' => '',
						'section'  => 'walker_core_feature_options',
						'type'     => 'dropdown-pages',
						'settings' => 'gridchamp_feature_page',
						'active_callback' => function(){
						    return get_theme_mod( 'features_status', true );
						},
						'priority' => 1,
			    	) 
		    	)
		    );	
		$wp_customize->add_setting( 
	        'walker_features_layout', 
	        array(
	            'default'           => 'features-layout-1',
	            'sanitize_callback' => 'gridchamp_sanitize_choices'
	        ) 
	    );
	    
	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'walker_features_layout',
				array(
					'section'	  => 'walker_core_feature_options',
					'label'		  => esc_html__( 'Choose Section Layout', 'gridchamp' ),
					'description' => '',
					'type'        => 'select',
					'priority'	  => 2,
					'choices'	  => array(
						'features-layout-1' => esc_html__('Layout 1 - Icon Image','walker-core'),
						'features-layout-2' => esc_html__('Layout 2 - Big Image','walker-core'),
						'features-layout-3' => esc_html__('Layout 3','walker-core'),
					),
					'active_callback' => function(){
				    	return get_theme_mod( 'features_status', true );
					},
					'priority' => 1,
				)
			)
		);
		$wp_customize->add_setting( 'enable_full_width_status', 
	    	array(
		      'default'  =>  false,
		      'sanitize_callback' => 'walker_core_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'enable_full_width_status', 
			array(
			  'label'   => esc_html__( 'Enable Full Width Layout', 'walker-core' ),
			  'section' => 'walker_core_feature_options',
			  'settings' => 'enable_full_width_status',
			  'type'    => 'checkbox',
			   'priority' => 1,
			   'active_callback' => 'gridchamp_feature_full_width_check',
			)
		);
		
		$wp_customize->add_setting( 'features_bg_color', 
			array(
		        'default'        => '#f2fbfc',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'features_bg_color', 
			array(
		        'label'   => esc_html__( 'Section color & Settings', 'walker-core' ),
		        'description' => esc_html__('Background Color','gridchamp'),
		        'section' => 'walker_core_feature_options',
		        'settings'   => 'features_bg_color',
		        'active_callback' => 'gridchamp_feature_layout_check',

		    ) ) 
		);
		$wp_customize->add_setting('features_bg_image', array(
	        'transport'         => 'refresh',
	        'sanitize_callback'     =>  'gridchamp_sanitize_file',
	    ));
	    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'features_bg_image', array(
	    	'label' => '',
	        'description'             => esc_html__('Background Image', 'gridchamp'),
	        'section'           => 'walker_core_feature_options',
	        'settings'          => 'features_bg_image',
	        'active_callback' => 'gridchamp_feature_layout_check',
	    )));
	    $wp_customize->add_setting( 'features_heading_color', 
			array(
		        'default'        => '#000000',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'features_heading_color', 
			array(
				'label' => '',
		        'description'   => esc_html__( 'Heading color', 'walker-core' ),
		        'section' => 'walker_core_feature_options',
		        'settings'   => 'features_heading_color',
		        'active_callback' => 'gridchamp_feature_layout_check',

		    ) ) 
		);
	    $wp_customize->add_setting( 'features_text_color', 
			array(
		        'default'        => '#727272',
		        'sanitize_callback' => 'walker_core_sanitize_hex_color',
	    	) 
		);

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'features_text_color', 
			array(
				'label' => '',
		        'description'   => esc_html__( 'Text color', 'walker-core' ),
		        'section' => 'walker_core_feature_options',
		        'settings'   => 'features_text_color',
		        'active_callback' => 'gridchamp_feature_layout_check',

		    ) ) 
		);
		$wp_customize->add_setting( 'features_heading_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'features_heading_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_feature_options',
				'label' => esc_html__( 'Heading','walker-core' ),
				'description' =>'',
				 'priority' => 2,
				'active_callback' => function(){
				    return get_theme_mod( 'features_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'feature_desc_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'feature_desc_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_feature_options',
				'label' => esc_html__( 'Description','walker-core' ),
				'description' =>'',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'features_status', true );
				},
			)
		);
		
		$wp_customize->add_setting( 'features_viewall_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'features_viewall_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_feature_options',
				'label' => esc_html__( 'View all button Text','walker-core' ),
				'description' =>'',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'features_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'features_viewall_btn_link', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'features_viewall_btn_link', 
			array(
				'type' => 'text',
				'section' => 'walker_core_feature_options',
				'label' => esc_html__( 'View all button link','walker-core' ),
				'description' => '',
				'priority' => 3,
				'active_callback' => function(){
				    return get_theme_mod( 'features_status', true );
				},
			)
		);
		$wp_customize->add_setting( 'gridchamp_featured_section_padding_top', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'walker_core_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_featured_section_padding_top', 
			array(
				'type' => 'number',
				'section' => 'walker_core_feature_options',
				'settings' => 'gridchamp_featured_section_padding_top',
				'label' => esc_html__( 'Section Top Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
				    return get_theme_mod( 'features_status', true );
				},
			) 
		);
		$wp_customize->add_setting( 'gridchamp_featured_section_padding_bottom', 
			array(
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'gridchamp_sanitize_number_absint',
				'default' => 50,
			) 
		);

		$wp_customize->add_control( 'gridchamp_featured_section_padding_bottom', 
			array(
				'type' => 'number',
				'section' => 'walker_core_feature_options',
				'settings' => 'gridchamp_featured_section_padding_bottom',
				'label' => esc_html__( 'Section Bottom Space','walker-core' ),
				'description' => '',
				'input_attrs' => array(
			        'min'   => 0,
			        'max'   => 300,
			        'step'  => 1,
			    ),
			    'priority' => 50,
			    'active_callback' => function(){
				    return get_theme_mod( 'features_status', true );
				},
			) 
		);
		
	
	}
		function gridchamp_feature_layout_check(){
	        $gridchamp_feature_status= get_theme_mod( 'features_status' );
	        $gridchamp_feature_layout_chk= get_theme_mod( 'walker_features_layout' );
			$gridchamp_text_align_status = false;
			if($gridchamp_feature_status == true && $gridchamp_feature_layout_chk=='features-layout-3'){
				$gridchamp_text_align_status = true;
			}
			return $gridchamp_text_align_status;
	    }
	    function gridchamp_feature_full_width_check(){
	        $gridchamp_feature_status= get_theme_mod( 'features_status' );
	        $gridchamp_feature_layout_chk= get_theme_mod( 'walker_features_layout' );
			$gridchamp_full_width_status = false;
			if($gridchamp_feature_status == true && $gridchamp_feature_layout_chk=='features-layout-2'){
				$gridchamp_full_width_status = true;
			}
			return $gridchamp_full_width_status;
	    }
	}
}
add_action( 'customize_register', 'walker_features_options_register' );