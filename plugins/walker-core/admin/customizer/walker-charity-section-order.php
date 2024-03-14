<?php
/**
*section reorder customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_charity_section_reorder_options_register')) {
	function walker_charity_section_reorder_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() ) {
			require WALKER_CORE_PATH . 'admin/customizer/walker-core-promo-controls.php';
			$wp_customize->add_section('walker_charity_section_order_options', 
			 	array(
			        'title' => esc_html__('Section Re-order', 'walker-core'),
			        'panel' =>'walker_charity_frontpage_option',
			        'priority' => 1,
		    	)
			 );

			require_once WALKER_CORE_PATH . 'admin/customizer/walkercore-sortable-control.php';
			$wp_customize->register_control_type( 'Walker_Core_Sortable_Customize_Control' );
			$default_order = array( 'features-cta','about-us','counter', 'donation', 'single-cta','features', 'portfolios','teams','extra-page','testimonial','recentpost','contact-section','brands');

			$Section_order_choices = array(
				'features-cta' => __( 'Featured CTA', 'walker-core' ),
				'about-us' => __( 'About Us', 'walker-core' ),
				'counter' => __( 'Counter', 'walker-core' ),
				'donation' => __( 'Donation', 'walker-core' ),
				'single-cta' => __( 'Single CTA', 'walker-core' ),
				'features' => __( 'Features', 'walker-core' ),
				'portfolios' => __( 'Portfolios', 'walker-core' ),
				'teams' => __( 'Teams', 'walker-core' ),
				'extra-page' => __( 'Extra Page', 'walker-core' ),
				'testimonial' => __( 'Testimonial', 'walker-core' ),
				'recentpost' => __( 'Recent Post', 'walker-core' ),
				'contact-section' => __( 'Contact Section', 'walker-core' ),
				'brands' => __( 'Brands', 'walker-core' ),
				
			);

				$wp_customize->add_setting( 'walker_charity_section_order',
				array(
					'default'     => $default_order,
					'transport' => 'refresh',
				)
			);

		    $wp_customize->add_control( new Walker_Core_Sortable_Customize_Control( $wp_customize,
		            'walker_charity_section_order',
		            array(
		                'section' => 'walker_charity_section_order_options',
		                'label'   => __( 'Section re-order', 'walker-core' ),
		                'type' =>'gridchamp-sortable-section',
		                'choices'     => $Section_order_choices,
		            )
		        )
		    );

		 //    $wp_customize->add_setting( 'walker_core_custom_post_type_settings', array(
   //          'default'           => '',
   //          'sanitize_callback' => 'wp_kses_post',
	  //       ) );

	  //       $wp_customize->add_control( new Walker_Core_Custom_Text( $wp_customize, 'walker_core_custom_post_type_settings', array(
		 //        'section' => 'walker_charity_section_order_options',
		 //        'label'   => esc_html__('Custom Post Type Settings','walker-core'),
		 //    ) ) );

			// $wp_customize->add_setting( 'disable_walker_core_testimonial', 
		 //    	array(
			//       'default'  =>  false,
			//       'sanitize_callback' => 'walker_core_sanitize_checkbox'
			//   	)
		 //    );
			// $wp_customize->add_control( 'disable_walker_core_testimonial', 
			// 	array(
			// 	  'label'   => esc_html__( 'Disable Testimonials', 'walker-core' ),
			// 	  'description' => esc_html__('This disable custom post type and all features related to testimonials from site.','walker-core'),
			// 	  'section' => 'walker_charity_section_order_options',
			// 	  'settings' => 'disable_walker_core_testimonial',
			// 	  'type'    => 'checkbox',
			// 	)
			// );
			// $wp_customize->add_setting( 'disable_walker_core_team', 
		 //    	array(
			//       'default'  =>  false,
			//       'sanitize_callback' => 'walker_core_sanitize_checkbox'
			//   	)
		 //    );
			// $wp_customize->add_control( 'disable_walker_core_team', 
			// 	array(
			// 	  'label'   => esc_html__( 'Disable Teams', 'walker-core' ),
			// 	  'description' => esc_html__('This disable custom post type and all features related to teams from site.','walker-core'),
			// 	  'section' => 'walker_charity_section_order_options',
			// 	  'settings' => 'disable_walker_core_team',
			// 	  'type'    => 'checkbox',
			// 	)
			// );
			// $wp_customize->add_setting( 'disable_walker_core_portfolio', 
		 //    	array(
			//       'default'  =>  false,
			//       'sanitize_callback' => 'walker_core_sanitize_checkbox'
			//   	)
		 //    );
			// $wp_customize->add_control( 'disable_walker_core_portfolio', 
			// 	array(
			// 	  'label'   => esc_html__( 'Disable Portfolio', 'walker-core' ),
			// 	  'description' => esc_html__('This disable custom post type and all features related to portfolios from site.','walker-core'),
			// 	  'section' => 'walker_charity_section_order_options',
			// 	  'settings' => 'disable_walker_core_portfolio',
			// 	  'type'    => 'checkbox',
			// 	)
			// );
			// $wp_customize->add_setting( 'disable_walker_core_faq', 
		 //    	array(
			//       'default'  =>  false,
			//       'sanitize_callback' => 'walker_core_sanitize_checkbox'
			//   	)
		 //    );
			// $wp_customize->add_control( 'disable_walker_core_faq', 
			// 	array(
			// 	  'label'   => esc_html__( 'Disable FAQ', 'walker-core' ),
			// 	  'description' => esc_html__('This disable custom post type and all features related to faqs from site.','walker-core'),
			// 	  'section' => 'walker_charity_section_order_options',
			// 	  'settings' => 'disable_walker_core_faq',
			// 	  'type'    => 'checkbox',
			// 	)
			// );
			// $wp_customize->add_setting( 'disable_walker_core_brands', 
		 //    	array(
			//       'default'  =>  false,
			//       'sanitize_callback' => 'walker_core_sanitize_checkbox'
			//   	)
		 //    );
			// $wp_customize->add_control( 'disable_walker_core_brands', 
			// 	array(
			// 	  'label'   => esc_html__( 'Disable Brands Logo', 'walker-core' ),
			// 	  'description' => esc_html__('This disable custom post type and all features related to brands logo from site.','walker-core'),
			// 	  'section' => 'walker_charity_section_order_options',
			// 	  'settings' => 'disable_walker_core_brands',
			// 	  'type'    => 'checkbox',
			// 	)
			// );
		}
	}
}
add_action( 'customize_register', 'walker_charity_section_reorder_options_register' );