<?php
/**
*section reorder customizer options
*
* @package walker_core
*
*/

if (! function_exists('mularx_section_reorder_options_register')) {
	function mularx_section_reorder_options_register( $wp_customize ) {
		if ( wc_fs()->can_use_premium_code() ) {
			require WALKER_CORE_PATH . 'admin/customizer/walker-core-promo-controls.php';
			$wp_customize->add_section('mularx_section_order_options', 
			 	array(
			        'title' => esc_html__('Section Re-order', 'walker-core'),
			        'panel' =>'mularx_front_page_panel',
			        'priority' => 100,
		    	)
			 );

			require_once WALKER_CORE_PATH . 'admin/customizer/walkercore-sortable-control.php';
			$wp_customize->register_control_type( 'Walker_Core_Sortable_Customize_Control' );
			$default_order = array( 'featured-cta','about-us','mission','steps','services', 'portfolio', 'counters','teams', 'testimonials','latest-products','newsletter','cta','latest-post','brands-logo');

			$Section_order_choices = array(
				'featured-cta' => __( 'Featured CTA', 'walker-core' ),
				'about-us' => __( 'About Us', 'walker-core' ),
				'mission' => __( 'Mission & Goal', 'walker-core' ),
				'steps' => __( 'Process Steps', 'walker-core' ),
				'services' => __( 'Services', 'walker-core' ),
				'portfolio' => __( 'Portfolios', 'walker-core' ),
				'counters' => __( 'Counter Numbers', 'walker-core' ),
				'teams' => __( 'Teams', 'walker-core' ),
				'testimonials' => __( 'Testimonials', 'walker-core' ),
				'latest-products' => __( 'latest Products', 'walker-core' ),
				'newsletter' => __( 'Newsletter', 'walker-core' ),
				'cta' => __( 'CTA', 'walker-core' ),
				'latest-post' => __( 'Latest Posts', 'walker-core' ),
				'brands-logo' => __( 'Brands Logo Showcase', 'walker-core' ),
			);

				$wp_customize->add_setting( 'mularx_section_order',
				array(
					'default'     => $default_order,
					'transport' => 'refresh',
				)
			);

		    $wp_customize->add_control( new Walker_Core_Sortable_Customize_Control( $wp_customize,
		            'mularx_section_order',
		            array(
		                'section' => 'mularx_section_order_options',
		                'label'   => __( 'Section re-order', 'walker-core' ),
		                'type' =>'gridchamp-sortable-section',
		                'choices'     => $Section_order_choices,
		            )
		        )
		    );

		}
	}
}
add_action( 'customize_register', 'mularx_section_reorder_options_register' );