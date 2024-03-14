<?php 
$prefix = 'cclw_';
$cclw_panel = new_cmb2_box( array(
        'id'            => $prefix .'global_css',
        'title'         => __( 'Global Style', 'cclw' ),
        'object_types'  => array( 'options-page', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		'option_key'      => 'cclw_global_css',
		'parent_slug'     => 'admin.php?page=custom_checkout_settings',
		
      
    ) );
	
	/*color panel*/		
			
			$headers_field_id = $cclw_panel->add_field( array(
				'id'          => $prefix . 'heading_group',
				'type'        => 'group',
				//'row_classes' => 'cclw_group_grid',
				'description' => __( 'Change design for section headers like Billing Details, Review Your Order etc', 'cmb2' ),
				 'repeatable'  => false, 
				'options'     => array(
					'group_title'       => __( 'Header/Label Section', 'cmb2' ),
					'closed'        => false,
					'sortable'          => true,
					
				),
			) );
			
			$cclw_panel->add_group_field( $headers_field_id, array(
			'name'          => __( 'Background Color', 'cclw' ),
			'desc' => 'Select a background color for headers i.e like "Billing section"',
			'id'            => $prefix . 'heading_background',
			'type'    => 'colorpicker',
	         'default' => '#fafafa',
			) );
			
			$cclw_panel->add_group_field( $headers_field_id, array(
			'name'          => __( 'Text Color', 'cclw' ),
			'desc' => 'Select a text color for header content i.e  "Billing section"',
			'id'            => $prefix . 'heading_text_color',
			'type'    => 'colorpicker',
	         'default' => '#000000',
			) );
			
			$cclw_panel->add_group_field( $headers_field_id, array(
				'name'          => __( 'Border Width', 'cmb2' ),
				//'desc'          => __( 'Field description (optional)', 'cmb2' ),
				'id'            => $prefix . 'heading_border_width',
				'type' => 'text_small',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
				),
				 'default' => '3',
				) );
				
			$cclw_panel->add_group_field( $headers_field_id, array(
				'name'          => __( 'Border Style', 'cmb2' ),
				//'desc'          => __( 'Field description (optional)', 'cmb2' ),
				'id'            => $prefix . 'heading_border_style',
				'type'             => 'select',
				'show_option_none' => false,
				'default'          => 'left',
				'options'          => array(
					'left' => __( 'Left', 'cmb2' ),
					'right' => __( 'Right', 'cmb2' ),
					'top' => __( 'Top', 'cmb2' ),
					'bottom' => __( 'Bottom', 'cmb2' ),
				),
				) );
			
				
			$cclw_panel->add_group_field( $headers_field_id, array(
			'name'          => __( 'Border Color', 'cclw' ),
			'desc' => 'Select a Border color for headers i.e like "Billing section"',
			'id'            => $prefix . 'heading_border',
			'type'    => 'colorpicker',
	         'default' => '#1e85be',
			) );
			
			/**/
			 $button_field_id = $cclw_panel->add_field( array(
				'id'          => $prefix . 'button_group',
				'type'        => 'group',
				//'description' => __( 'Generates reusable form entries', 'cmb2' ),
				 'repeatable'  => false, 
				'options'     => array(
					'group_title'       => __( 'Buttons', 'cmb2' ),
					'closed'        => false,
					'sortable'          => true,
				),
			) );	
			$cclw_panel->add_group_field( $button_field_id, array(
			'name'          => __( 'Button Color', 'cclw' ),
			'desc' => 'Select a color for all buttons i.e like "Place order or Apply coupon" on checkout ',
			'id'            => $prefix . 'button_color',
			'type'    => 'colorpicker',
	         'default' => '#1e85be',
			) );
			
			$cclw_panel->add_group_field( $button_field_id, array(
			'name'          => __( 'Button Text Color', 'cclw' ),
			'desc' => 'Select a color for text on buttons',
			'id'            => $prefix . 'buttontext_color',
			'type'    => 'colorpicker',
	         'default' => '#fff',
			) );
			
			$cclw_panel->add_group_field( $button_field_id, array(
			'name'          => __( 'Button Hover Color', 'cclw' ),
			'desc' => 'Select a color for all buttons on hover ',
			'id'            => $prefix . 'button_hover_color',
			'type'    => 'colorpicker',
	         'default' => '#1e85be',
			) );
			
			$cclw_panel->add_group_field( $button_field_id, array(
			'name'          => __( 'Button Hover Text Color', 'cclw' ),
			'desc' => 'Select a color for text on buttons on hover',
			'id'            => $prefix . 'buttontext_hover_color',
			'type'    => 'colorpicker',
	         'default' => '#fff',
			) );