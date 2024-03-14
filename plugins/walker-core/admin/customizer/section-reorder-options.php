<?php
/**
*section reorder customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_section_reorder_options_register')) {
	function walker_section_reorder_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() ) {
			require WALKER_CORE_PATH . 'admin/customizer/walker-core-promo-controls.php';
			$wp_customize->add_section('walker_core_section_order_options', 
			 	array(
			        'title' => esc_html__('Section Re-order', 'walker-core'),
			        'panel' =>'gridchamp_frontpage_option',
			        'priority' => 1,
		    	)
			 );

			require_once WALKER_CORE_PATH . 'admin/customizer/walkercore-sortable-control.php';
			$wp_customize->register_control_type( 'Walker_Core_Sortable_Customize_Control' );
			$default_order = array( 'extra-cta','about-us','grid-counter', 'services', 'portfolio','team', 'features','products','pricing-table','testimonial','cta','recentpost','faqs','brands','newsletter');

			$Section_order_choices = array(
				'extra-cta' => __( 'Extra CTA', 'walker-core' ),
				'about-us' => __( 'About Us', 'walker-core' ),
				'grid-counter' => __( 'Counter Section', 'walker-core' ),
				'services' => __( 'Services', 'walker-core' ),
				'portfolio' => __( 'Portfolio', 'walker-core' ),
				'team' => __( 'Team', 'walker-core' ),
				'features' => __( 'Features', 'walker-core' ),
				'products' => __( 'Products', 'walker-core' ),
				'pricing-table' => __( 'Pricing Table', 'walker-core' ),
				'testimonial' => __( 'Testimonial', 'walker-core' ),
				'cta' => __( 'CTA', 'walker-core' ),
				'recentpost' => __( 'Recent Post', 'walker-core' ),
				'faqs' => __( 'FAQs', 'walker-core' ),
				'brands' => __( 'Brands', 'walker-core' ),
				'newsletter' => __( 'Newsletter', 'walker-core' ),
				
			);

				$wp_customize->add_setting( 'gridchamp_section_order',
				array(
					'default'     => $default_order,
					'transport' => 'refresh',
				)
			);

		    $wp_customize->add_control( new Walker_Core_Sortable_Customize_Control( $wp_customize,
		            'gridchamp_section_order',
		            array(
		                'section' => 'walker_core_section_order_options',
		                'label'   => __( 'Section re-order', 'textdomain' ),
		                'type' =>'gridchamp-sortable-section',
		                'choices'     => $Section_order_choices,
		            )
		        )
		    );

		    $wp_customize->add_setting( 'walker_core_custom_post_type_settings', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
	        ) );

	        $wp_customize->add_control( new Walker_Core_Custom_Text( $wp_customize, 'walker_core_custom_post_type_settings', array(
		        'section' => 'walker_core_section_order_options',
		        'label'   => esc_html__('Custom Post Type Settings','walker-core'),
		    ) ) );

			$wp_customize->add_setting( 'disable_walker_core_testimonial', 
		    	array(
			      'default'  =>  false,
			      'sanitize_callback' => 'walker_core_sanitize_checkbox'
			  	)
		    );
			$wp_customize->add_control( 'disable_walker_core_testimonial', 
				array(
				  'label'   => esc_html__( 'Disable Testimonials', 'walker-core' ),
				  'description' => esc_html__('This disable custom post type and all features related to testimonials from site.','walker-core'),
				  'section' => 'walker_core_section_order_options',
				  'settings' => 'disable_walker_core_testimonial',
				  'type'    => 'checkbox',
				)
			);
			$wp_customize->add_setting( 'disable_walker_core_team', 
		    	array(
			      'default'  =>  false,
			      'sanitize_callback' => 'walker_core_sanitize_checkbox'
			  	)
		    );
			$wp_customize->add_control( 'disable_walker_core_team', 
				array(
				  'label'   => esc_html__( 'Disable Teams', 'walker-core' ),
				  'description' => esc_html__('This disable custom post type and all features related to teams from site.','walker-core'),
				  'section' => 'walker_core_section_order_options',
				  'settings' => 'disable_walker_core_team',
				  'type'    => 'checkbox',
				)
			);
			$wp_customize->add_setting( 'disable_walker_core_portfolio', 
		    	array(
			      'default'  =>  false,
			      'sanitize_callback' => 'walker_core_sanitize_checkbox'
			  	)
		    );
			$wp_customize->add_control( 'disable_walker_core_portfolio', 
				array(
				  'label'   => esc_html__( 'Disable Portfolio', 'walker-core' ),
				  'description' => esc_html__('This disable custom post type and all features related to portfolios from site.','walker-core'),
				  'section' => 'walker_core_section_order_options',
				  'settings' => 'disable_walker_core_portfolio',
				  'type'    => 'checkbox',
				)
			);
			$wp_customize->add_setting( 'disable_walker_core_faq', 
		    	array(
			      'default'  =>  false,
			      'sanitize_callback' => 'walker_core_sanitize_checkbox'
			  	)
		    );
			$wp_customize->add_control( 'disable_walker_core_faq', 
				array(
				  'label'   => esc_html__( 'Disable FAQ', 'walker-core' ),
				  'description' => esc_html__('This disable custom post type and all features related to faqs from site.','walker-core'),
				  'section' => 'walker_core_section_order_options',
				  'settings' => 'disable_walker_core_faq',
				  'type'    => 'checkbox',
				)
			);
			$wp_customize->add_setting( 'disable_walker_core_brands', 
		    	array(
			      'default'  =>  false,
			      'sanitize_callback' => 'walker_core_sanitize_checkbox'
			  	)
		    );
			$wp_customize->add_control( 'disable_walker_core_brands', 
				array(
				  'label'   => esc_html__( 'Disable Brands Logo', 'walker-core' ),
				  'description' => esc_html__('This disable custom post type and all features related to brands logo from site.','walker-core'),
				  'section' => 'walker_core_section_order_options',
				  'settings' => 'disable_walker_core_brands',
				  'type'    => 'checkbox',
				)
			);
		}
	}
}
add_action( 'customize_register', 'walker_section_reorder_options_register' );