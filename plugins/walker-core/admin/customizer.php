<?php

$theme = wp_get_theme();
if ( 'Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme  ):
	if ( wc_fs()->can_use_premium_code() ) {
	require WALKER_CORE_PATH . 'admin/customizer/walker-customizer-controls.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/noticebar-options.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/feature-options.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/brand-options.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/faq-options.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/team-options.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/portfolio-options.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/pricing-table.php';
	require_once WALKER_CORE_PATH . 'admin/customizer/section-reorder-options.php';

	require WALKER_CORE_PATH . 'admin/customizer/sanitization_functions.php';
}

if (! function_exists('walcore_core_main_options_register')) {
	function walcore_core_main_options_register( $wp_customize ) {
	if ( wc_fs()->can_use_premium_code() ) {
		if(!get_theme_mod('disable_walker_core_testimonial')){
			$wp_customize->add_setting( 
	        'walker_testimonial_layout', 
	        array(
	            'default'           => 'testimonial-layout-1',
	            'sanitize_callback' => 'gridchamp_sanitize_choices'
	        ) 
	    );
	    
	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'walker_testimonial_layout',
				array(
					'section'	  => 'gridchamp_testimonial_options',
					'label'		  => __( 'Choose Section Layout', 'walker-core' ),
					'description' => '',
					'type'        => 'select',
					'priority'	  => 2,
					'choices'	  => array(
						'testimonial-layout-1'    => __('Layout 1','walker-core'),
						'testimonial-layout-2'  => __('Layout 2','walker-core'),
						'testimonial-layout-3'  => __('Layout 3','walker-core'),
					),
					'active_callback' => function(){
				    	return get_theme_mod( 'testimonial_status', true );
					},
				)
			)
		);
		/*Testimonial listing Page*/
		$wp_customize->add_section('walker_core_testimonial_inner_options', 
		 	array(
		        'title' => esc_html__('Testimonial Page Setting', 'walker-core'),
		        'panel' =>'gridchamp_theme_option',
		        'priority' => 108,
		        'divider' => 'before',
	    	)
		 );

		$wp_customize->add_setting( 'testimonial_page_heading_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'walker_core_sanitize_text',
			) 
		);
		$wp_customize->add_control( 'testimonial_page_heading_text', 
			array(
				'type' => 'text',
				'section' => 'walker_core_testimonial_inner_options',
				'label' => esc_html__( 'Heading','walker-core' ),
				'description' => '',
				 'priority' => 2,
			)
		);
		$wp_customize->add_setting( 'testimonial_page_desc_text', 
		 	array(
				'capability' => 'edit_theme_options',
				'default' => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			) 
		);

		$wp_customize->add_control( 'testimonial_page_desc_text', 
			array(
				'type' => 'textarea',
				'section' => 'walker_core_testimonial_inner_options',
				'label' => esc_html__( 'Description','walker-core' ),
				'description' => '',
				'priority' => 3,
				
			)
		);

		}
	

		

	$wp_customize->add_setting( 
	        'walker_service_layout', 
	        array(
	            'default'           => 'service-layout-1',
	            'sanitize_callback' => 'gridchamp_sanitize_choices'
	        ) 
	    );
	    
	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'walker_service_layout',
				array(
					'section'	  => 'gridchamp_services_options',
					'label'		  => __( 'Choose Section Layout', 'walker-core' ),
					'description' => '',
					'type'        => 'select',
					'priority'	  => 2,
					'choices'	  => array(
						'service-layout-1'    => __('Layout 1','walker-core'),
						'service-layout-2'  => __('Layout 2','walker-core'),
					),
					'active_callback' => function(){
				    	return get_theme_mod( 'services_status', true );
					},
				)
			)
		);
	  
		
		$wp_customize->add_setting( 
	        'blog_post_view', 
	        array(
	            'default'           => 'grid-layout',
	            'sanitize_callback' => 'gridchamp_sanitize_choices'
	        ) 
	    );
	    
	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'blog_post_view',
				array(
					'section'	  => 'gridchamp_blog_layout',
					'label'		  => __( 'Choose Post View', 'walker-core' ),
					'description' => '',
					'type'           => 'radio',
					'choices'	  => array(
						'grid-layout'    => __('Grid View','gridchamp'),
						'list-layout'  => __('List List','gridchamp'),
	                    'full-layout' => __('Full Image View','walker-core'),
					)
				)
			)
		);
	    $wp_customize->add_setting( 'author_status', 
	    	array(
		      'default'  =>  true,
		      'sanitize_callback' => 'gridchamp_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'author_status', 
			array(
			  'label'   => __( 'Show Author', 'walker-core' ),
			  'section' => 'gridchamp_blog_layout',
			  'settings' => 'author_status',
			  'type'    => 'checkbox',
			)
		);
		$wp_customize->add_setting( 'post_date_status', 
	    	array(
		      'default'  =>  true,
		      'sanitize_callback' => 'gridchamp_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'post_date_status', 
			array(
			  'label'   => __( 'Show Date', 'walker-core' ),
			  'section' => 'gridchamp_blog_layout',
			  'settings' => 'post_date_status',
			  'type'    => 'checkbox',
			)
		);
		$wp_customize->add_setting( 'category_status', 
	    	array(
		      'default'  =>  true,
		      'sanitize_callback' => 'gridchamp_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'category_status', 
			array(
			  'label'   => __( 'Show Category', 'walker-core' ),
			  'section' => 'gridchamp_blog_layout',
			  'settings' => 'category_status',
			  'type'    => 'checkbox',
			)
		);
		$wp_customize->add_setting( 'tags_status', 
	    	array(
		      'default'  =>  true,
		      'sanitize_callback' => 'gridchamp_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'tags_status', 
			array(
			  'label'   => __( 'Show Tags', 'walker-core' ),
			  'section' => 'gridchamp_blog_layout',
			  'settings' => 'tags_status',
			  'type'    => 'checkbox',
			)
		);
		$wp_customize->add_setting( 'comment_status', 
	    	array(
		      'default'  =>  true,
		      'sanitize_callback' => 'gridchamp_sanitize_checkbox'
		  	)
	    );
		$wp_customize->add_control( 'comment_status', 
			array(
			  'label'   => __( 'Show Comment', 'walker-core' ),
			  'section' => 'gridchamp_blog_layout',
			  'settings' => 'comment_status',
			  'type'    => 'checkbox',
			)
		);
		
	}
}

	
}
add_action( 'customize_register', 'walcore_core_main_options_register' );
endif;

// if ( 'Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme  ):
// 	if (! wc_fs()->can_use_premium_code() ) {
// 		require_once WALKER_CORE_PATH . 'admin/customizer/promotion-options.php';
// 	}
// endif;
if ( 'WalkerMag' == $theme->name || 'WalkerMag' == $theme->parent_theme  ):
	if (! function_exists('walkermag_main_options_register')) {

		function walkermag_main_options_register( $wp_customize ) {
			require WALKER_CORE_PATH . 'admin/customizer/walker-customizer-controls.php';
			require WALKER_CORE_PATH . 'admin/customizer/sanitization_functions.php';
			if ( wc_fs()->can_use_premium_code() ) {


		
		$wp_customize->add_setting('walkermag_topbar_menu_select',array(
	        'default'           => '',
	        'transport' => 'refresh',
	        'capability'        => 'edit_theme_options',
	        'sanitize_callback' => 'walker_core_sanitize_number_absint',
	    )
		);
		$wp_customize->add_control(
			new WalkerCore_Menu_Dropdown_Custom_Control($wp_customize, 
			'walkermag_topbar_menu_select',
			    array(
			        'label'       => esc_html__('Select Topbar Menu', 'walkermag'),
			        'description' =>esc_html('Select Cartgory to be shown on main content of after featured section','walkermag'),
			        'section'     => 'walkermag_header_options',
			        'settings'	  => 'walkermag_topbar_menu_select',
			        'priority'    => 10,
			        'type'		  => 'walker-core-custom-menu',
				    
			        
		    	)
			)
		);

				}
			}
	}
	add_action( 'customize_register', 'walkermag_main_options_register' );
endif;

if ( 'Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme  ):
	if ( wc_fs()->can_use_premium_code() ) {
		require_once WALKER_CORE_PATH . 'admin/customizer/walker-charity-section-order.php';
	}
endif;

if ( 'WalkerShop' == $theme->name || 'WalkerShop' == $theme->parent_theme  ):
	if ( wc_fs()->can_use_premium_code() ) {
		require_once WALKER_CORE_PATH . 'admin/customizer/walkershop-section-sortable.php';
	}
endif;

if ( 'MularX' == $theme->name || 'MularX' == $theme->parent_theme  ):
	if ( wc_fs()->can_use_premium_code() ) {
		require_once WALKER_CORE_PATH . 'admin/customizer/mularx-section-sortable.php';
	}
endif;