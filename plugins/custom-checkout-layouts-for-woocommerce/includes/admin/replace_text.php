<?php 
$prefix = 'cclw_';
$cclw_panel = new_cmb2_box( array(
        'id'            => $prefix .'replace_text',
        'title'         => __( 'Replace Text', 'cclw' ),
        'object_types'  => array( 'options-page', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		'option_key'      => 'cclw_replace_text',
		'parent_slug'     => 'admin.php?page=custom_checkout_settings',
		
      
    ) );
	/*Replace text*/
			$cclw_panel->add_field( array(
				//'name' => 'Replace Some Common Woocommerce Strings to Desired Names',
				'desc' => 'Replace Some Common Woocommerce text(strings) to Desired Names',
				'type' => 'title',
				'id'   => $prefix . 'replacetext',
			) );	
			
			$cclw_panel->add_field( array(
				'name' => 'Add to cart',
				'desc' => 'Replace "Add to cart" with ....',
				'type' => 'text',
				'id'   => $prefix . 'addtocart',
				'attributes'  => array(
					'placeholder' => 'For Example :- Add to Basket',
					
				),
				
			) );    
            
			$cclw_panel->add_field( array(
				'name' => 'View cart',
				'desc' => 'Replace "View cart" with checkout (recommended) as every cart link is redirected to checkout page',
				'type' => 'text',
				'id'   => $prefix . 'viewcart',
				'default' => 'Checkout',
				
			) );        
           
		   $cclw_panel->add_field( array(
				'name' => 'Place Order',
				'desc' => 'Replace "Place Order" with ....',
				'type' => 'text',
				'id'   => $prefix . 'placeorder',
				'attributes'  => array(
					'placeholder' => 'For example :- Complete payment',
					
				),
			) );        
            
			$cclw_panel->add_field( array(
				'name' => 'Continue Shopping',
				'desc' => 'Repalce "Continue Shopping" with ....',
				'type' => 'text',
				'id'   => $prefix . 'continueshop',
				'attributes'  => array(
					'placeholder' => 'For example :- Explore More',
					
				),
			) );   
            $cclw_panel->add_field( array(
				'name' => 'Billing Details',
				'desc' => 'Repalce "Billing Details" with ....',
				'type' => 'text',
				'id'   => $prefix . 'billing_details',
				'attributes'  => array(
					'placeholder' => 'For example :- Customer Details',
					
				),
			) ); 
            $cclw_panel->add_field( array(
				'name' => 'Review Your Orders',
				'desc' => 'Repalce "Review Your Order" with ....',
				'type' => 'text',
				'id'   => $prefix . 'review_order',
				'attributes'  => array(
					'placeholder' => 'For example :- Order Summary',
					
				),
			) ); 
            $cclw_panel->add_field( array(
				'name' => 'Payments',
				'desc' => 'Repalce "Payments" with ....',
				'type' => 'text',
				'id'   => $prefix . 'confirm_pay',
				'attributes'  => array(
					'placeholder' => 'For example :- Pay Here',
					
				),
			) ); 			