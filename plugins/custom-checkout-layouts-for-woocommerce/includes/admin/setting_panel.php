<?php 
$prefix = 'cclw_';

			$cclw_panel = new_cmb2_box( array(
			'id'            => $prefix . 'custom_checkout',
			'title'         => __( 'Checkout Layouts', 'cclw' ),
			'object_types' => array( 'options-page' ),
			'option_key'      => 'custom_checkout_settings', 
			'icon_url'        => 'dashicons-cart',
			'position'        => 59,
		    // 'parent_slug'     => 'woocommerce',
			
			) );

			/*general setting panel*/		
			$cclw_panel->add_field( array(
				'name'             => 'Checkout Page Layouts',
				'desc'             => '<span style="color:#6368ff;">(Pro) Want More Layouts?</span> Just drag and drop sections and create your layout. <a target="_blank" href="https://www.youtube.com/watch?v=ODMMvu2xQbw">A Video Guide.</a>',
				'id'               => $prefix .'checkout_layouts',
				'type'             => 'select',
				'default'          => 'three-column-layout',
				'options'          => array(
					'three-column-layout' => __( '3 Column Layout', 'cclw' ),
					'two-column-layout'   => __( '2 Column Layout', 'cclw' ),
					'one-column-layout'   => __( '1 Column Layout', 'cclw' ),
					'cart-checkout-layout'   => __( 'Cart + Chekout Layout', 'cclw' ),
				),
			) );
			
			
			$cclw_panel->add_field( array(
				'name'             => 'Order Table Design Options',
				'desc'             => 'Select a range of different designs for ORDER REVIEW section.',
				'id'               => $prefix .'checkout_ordertable',
				'type'             => 'select',
				'show_option_none' => false,
				'default'          => 'basic-table',
				'options'          => array(
					'style-1' => __( 'Style 1', 'cmb2' ),
					'style-default' => __( 'Default Style', 'cmb2' ),
				),
			) );
			
			
		      
           	$cclw_panel->add_field( array(
			'name'    => 'Skip Cart Page',
			'desc' => 'Recommended "yes". We recommend to skip cart page to shorten the checkout process .',
			'id'      => $prefix .'skip_cart',
			'type'    => 'radio_inline',
			'options' => array(
			'yes' => __( 'Yes', 'cclw' ),
			'no'   => __( 'No', 'cclw' ),
			),
			'default' => 'yes',
			) );
			
			$cclw_panel->add_field( array(
			'name'    => 'Non Changeable Qty',
			'desc' => 'Product qty will be nonchangeable if you are selling only one item or You donot want your buyers to change qty at checkout.',
			'id'      => $prefix .'skip_qty',
			'type'    => 'radio_inline',
			'options' => array(
			'yes' => __( 'Yes', 'cclw' ),
			'no'   => __( 'No', 'cclw' ),
			),
			'default' => 'no',
			) );
			
			$cclw_panel->add_field( array(
			'name'    => 'Hide Order Notes',
			'desc' => 'Select yes if you want to hide Order notes section',
			'id'      => $prefix .'order_notes',
			'type'    => 'radio_inline',
			'options' => array(
			'yes' => __( 'Yes', 'cclw' ),
			'no'   => __( 'No', 'cclw' ),
			),
			'default' => 'no',
			) );
			

			?>