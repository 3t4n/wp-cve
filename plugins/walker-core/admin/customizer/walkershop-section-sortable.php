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
			$wp_customize->add_section('walkershop_section_order_options', 
			 	array(
			        'title' => esc_html__('Section Re-order', 'walker-core'),
			        'panel' =>'walkershop_front_page_panel',
			        'priority' => 100,
		    	)
			 );

			require_once WALKER_CORE_PATH . 'admin/customizer/walkercore-sortable-control.php';
			$wp_customize->register_control_type( 'Walker_Core_Sortable_Customize_Control' );
			$default_order = array( 'services','latest-products','special-offer','recommended-tab','top-selling', 'top-rating', 'about-us','shopby-category', 'featured-box','featured-products','product-showcase','flash-sale','category-tabs','testimonials','disocunt-offer','latest-post','newsletter','brands-logo');

			$Section_order_choices = array(
				'services' => __( 'Services', 'walker-core' ),
				'latest-products' => __( 'Latest Products', 'walker-core' ),
				'special-offer' => __( 'Special Offer CTA', 'walker-core' ),
				'recommended-tab' => __( 'Recommended Products Tabs', 'walker-core' ),
				'top-selling' => __( 'Top Selling Products', 'walker-core' ),
				'top-rating' => __( 'Top Rating Products', 'walker-core' ),
				'about-us' => __( 'About Us', 'walker-core' ),
				'shopby-category' => __( 'Shop By Category', 'walker-core' ),
				'featured-box' => __( 'Featured Box Section', 'walker-core' ),
				'featured-products' => __( 'Featured Products', 'walker-core' ),
				'product-showcase' => __( 'Product Showcase', 'walker-core' ),
				'flash-sale' => __( 'Flash Sale Products', 'walker-core' ),
				'category-tabs' => __( 'Featured Category Products Tabs', 'walker-core' ),
				'testimonials' => __( 'Testimonials', 'walker-core' ),
				'disocunt-offer' => __( 'Disocunt Offer CTA', 'walker-core' ),
				'latest-post' => __( 'Latest Posts', 'walker-core' ),
				'newsletter' => __( 'Newsletter Settings', 'walker-core' ),
				'brands-logo' => __( 'Brands Logo Showcase', 'walker-core' ),
				
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
		                'section' => 'walkershop_section_order_options',
		                'label'   => __( 'Section re-order', 'walker-core' ),
		                'type' =>'gridchamp-sortable-section',
		                'choices'     => $Section_order_choices,
		            )
		        )
		    );

		}
	}
}
add_action( 'customize_register', 'walker_charity_section_reorder_options_register' );