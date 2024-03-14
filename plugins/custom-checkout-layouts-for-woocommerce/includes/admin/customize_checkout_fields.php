<?php 
$prefix = 'cclw_';
$cclw_panel = new_cmb2_box( array(
        'id'            => $prefix .'checkout_fields',
        'title'         => __( 'Customize Checkout Fields', 'cclw' ),
        'object_types'  => array( 'options-page', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		'option_key'      => 'cclw_checkout_fields',
		'parent_slug'     => 'admin.php?page=custom_checkout_settings'
	    
    ) );
	
	        $cclw_panel->add_field( array(
				'name' => '<div id="cclw_billing_admin_tabs">
				<a href="#" class="cclw_admin_button active" id="billing_details_group_wrap">Billing Fields</a>
				<a href="#" class="cclw_admin_button" id="shipping_details_group_wrap">Shipping Fields</a></div>',
				//'desc' => '',
				'type' => 'title',
				'id'   => $prefix . 'billing_fields',
			) );
			
			 $cclw_panel->add_field( array(
				'name'    => 'Overide Billing/Shipping Fields',
				'desc'    => 'Recommended to Select no if you are using any external plugin for Billing/Shipping Fields For example :- Custom fields for woocommerce. Below setting panel will only work if you select Yes',
				'id'      => $prefix . 'overide_fields',
				'type'    => 'radio_inline',
				'options' => array(
					'yes' => __( 'Yes', 'cmb2' ),
					'no'   => __( 'No', 'cmb2' ),
				),
				'default' => 'no',
			) );
			
			/*Billing First Name*/
			$billing_first_name = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_first_name',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing First Name',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $billing_first_name, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' => 'billing_first_name',
			) );
			$cclw_panel->add_group_field( $billing_first_name, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'First Name',
				'attributes'  => array(
					'placeholder' => 'First Name',
				),
			) );
			
			$cclw_panel->add_group_field( $billing_first_name, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'Enter your First Name',
			) );
			$cclw_panel->add_group_field( $billing_first_name, array(
				'name' => 'Width',
				'id'   => 'width',
				'type'    => 'radio_inline',
				'options' => array(
					'form-row-first' => __( '50% Left', 'cmb2' ),
					'form-row-last'   => __('50% Right', 'cmb2' ),
					'form-row-wide'   => __('Full Width', 'cmb2' ),
				),
				'default' => 'show',
			) );
			$cclw_panel->add_group_field( $billing_first_name, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_first_name, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/*Billing Last Name*/
			
			$billing_last_name = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_last_name',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing Last Name',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $billing_last_name, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' => 'billing_last_name',
			) );
			$cclw_panel->add_group_field( $billing_last_name, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Last Name',
				'attributes'  => array(
					'placeholder' => 'Last Name',
				),
			) );
			
			$cclw_panel->add_group_field( $billing_last_name, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'Enter your Last Name',
			) );
			$cclw_panel->add_group_field( $billing_last_name, array(
				'name' => 'Width',
				'id'   => 'width',
				'type'    => 'radio_inline',
				'options' => array(
					'form-row-first' => __( '50% Left', 'cmb2' ),
					'form-row-last'   => __('50% Right', 'cmb2' ),
					'form-row-wide'   => __('Full Width', 'cmb2' ),
				),
				'default' => 'show',
			) );
			$cclw_panel->add_group_field( $billing_last_name, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_last_name, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/*Billing Company Name*/
			
			$billing_company = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_company',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing Company',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $billing_company, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' => 'billing_company',
			) );
			$cclw_panel->add_group_field( $billing_company, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Company',
				'attributes'  => array(
					'placeholder' => 'Company',
				),
			) );
			
			$cclw_panel->add_group_field( $billing_company, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'Enter your Company Name',
			) );
			
			$cclw_panel->add_group_field( $billing_company, array(
				'name' => 'Width',
				'id'   => 'width',
				'type'    => 'radio_inline',
				'options' => array(
					'form-row-first' => __( '50% Left', 'cmb2' ),
					'form-row-last'   => __('50% Right', 'cmb2' ),
					'form-row-wide'   => __('Full Width', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			$cclw_panel->add_group_field( $billing_company, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_company, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* Country / Region */
						
			$billing_country = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_country',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing Country/Region',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $billing_country, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'billing_country',
				
			) );
			
			$cclw_panel->add_group_field( $billing_country, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'State/Country',
				'attributes'  => array(
					'placeholder' => 'State/Country',
				),
			) );
			
			$cclw_panel->add_group_field( $billing_country, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			
			$cclw_panel->add_group_field( $billing_country, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_country, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			
			
			/*Billing Adress */
			$billing_adress = $cclw_panel->add_field( array(
				'id'          => $prefix . 'address_1',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Street address (Address Line 1)',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $billing_adress, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'billing_address_1',
			) );
			$cclw_panel->add_group_field( $billing_adress, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Street addresss',
				'attributes'  => array(
					'placeholder' => 'Street address',
				),
			) );
			
			$cclw_panel->add_group_field( $billing_adress, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'House number and street name',
				
			) );
			$cclw_panel->add_group_field( $billing_adress, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_adress, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			/* Apartment suite */
			$billing_adress_2 = $cclw_panel->add_field( array(
				'id'          => $prefix . 'address_2',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Street address (Address Line 2)',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $billing_adress_2, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'billing_address_2',
			) );
			
			$cclw_panel->add_group_field( $billing_adress_2, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'attributes'  => array(
					'placeholder' => 'Apartment, suite, unit, etc. (optional)',
				),
				
				
			) );
			$cclw_panel->add_group_field( $billing_adress_2, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_adress_2, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			
			/*Town/City*/
						
			$billing_city = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_city',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing Town/City',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $billing_city, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'billing_city',
			) );
			
			$cclw_panel->add_group_field( $billing_city, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Town/City',
				'attributes'  => array(
					'placeholder' => 'Town/City',
				),
			) );
			
			$cclw_panel->add_group_field( $billing_city, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				
			) );
			$cclw_panel->add_group_field( $billing_city, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_city, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* State / County */
						
			$billing_state = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_state',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing State/Country',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $billing_state, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'billing_state',
			) );
			
			$cclw_panel->add_group_field( $billing_state, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'State/Country',
				'attributes'  => array(
					'placeholder' => 'State/Country',
				),
				
			) );
			
			$cclw_panel->add_group_field( $billing_state, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			
			$cclw_panel->add_group_field( $billing_state, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
				'disabled'    => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_state, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* Postcode / ZIP */
						
			$billing_postcode = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_postcode',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing Postcode / ZIP',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $billing_postcode, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'billing_postcode',
			) );
			$cclw_panel->add_group_field( $billing_postcode, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Postcode / ZIP',
				'attributes'  => array(
					'placeholder' => 'Postcode / ZIP',
				),
			) );
			
			
			$cclw_panel->add_group_field( $billing_postcode, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			
			$cclw_panel->add_group_field( $billing_postcode, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_postcode, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* Phone */
						
			$billing_phone = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_phone',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing Phone',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $billing_phone, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'billing_phone',
			) );
			$cclw_panel->add_group_field( $billing_phone, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Phone',
				'attributes'  => array(
					'placeholder' => 'Phone',
				),
			) );
			
				
			$cclw_panel->add_group_field( $billing_phone, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			
			$cclw_panel->add_group_field( $billing_phone, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $billing_phone, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* Email */
						
			$billing_email = $cclw_panel->add_field( array(
				'id'          => $prefix . 'billing_email',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Billing Email',
					'closed'        => true,
				),
				'classes' => 'billing_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $billing_email, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' => 'billing_email',
			) );
			$cclw_panel->add_group_field( $billing_email, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Email',
				'attributes'  => array(
					'placeholder' => 'Email',
				),
			) );
			
			
			$cclw_panel->add_group_field( $billing_email, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			$cclw_panel->add_group_field( $billing_email, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
		           
			
			/*shipping section starts*/		
			$shipping_first_name = $cclw_panel->add_field( array(
				'id'          => $prefix . 'shipping_first_name',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping First Name',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $shipping_first_name, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' => 'shipping_first_name',
			) );
			$cclw_panel->add_group_field( $shipping_first_name, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'First Name',
				'attributes'  => array(
					'placeholder' => 'First Name',
				),
			) );
			
			$cclw_panel->add_group_field( $shipping_first_name, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'Enter your First Name',
			) );
			$cclw_panel->add_group_field( $shipping_first_name, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_first_name, array(
				'name' => 'Width',
				'id'   => 'width',
				'type'    => 'radio_inline',
				'options' => array(
					'form-row-first' => __( '50% Left', 'cmb2' ),
					'form-row-last'   => __('50% Right', 'cmb2' ),
					'form-row-wide'   => __('Full Width', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			$cclw_panel->add_group_field( $shipping_first_name, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/*Billing Last Name*/
			
			$shipping_last_name = $cclw_panel->add_field( array(
				'id'          => $prefix . 'shipping_last_name',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping Last Name',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $shipping_last_name, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' => 'shipping_last_name',
			) );
			$cclw_panel->add_group_field( $shipping_last_name, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Last Name',
				'attributes'  => array(
					'placeholder' => 'Last Name',
				),
			) );
			
			$cclw_panel->add_group_field( $shipping_last_name, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'Enter your Last Name',
			) );
			
			$cclw_panel->add_group_field( $shipping_last_name, array(
				'name' => 'Width',
				'id'   => 'width',
				'type'    => 'radio_inline',
				'options' => array(
					'form-row-first' => __( '50% Left', 'cmb2' ),
					'form-row-last'   => __('50% Right', 'cmb2' ),
					'form-row-wide'   => __('Full Width', 'cmb2' ),
				),
				'default' => 'show',
			) );
			$cclw_panel->add_group_field( $shipping_last_name, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_last_name, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/*Billing Company Name*/
			
			$shipping_company = $cclw_panel->add_field( array(
				'id'          => $prefix . 'shipping_company',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping Company',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $shipping_company, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' => 'shipping_company',
			) );
			$cclw_panel->add_group_field( $shipping_company, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Company',
				'attributes'  => array(
					'placeholder' => 'Company',
				),
			) );
			
			$cclw_panel->add_group_field( $shipping_company, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'Enter your Company Name',
			) );
			
			$cclw_panel->add_group_field( $shipping_company, array(
				'name' => 'Width',
				'id'   => 'width',
				'type'    => 'radio_inline',
				'options' => array(
					'form-row-first' => __( '50% Left', 'cmb2' ),
					'form-row-last'   => __('50% Right', 'cmb2' ),
					'form-row-wide'   => __('Full Width', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			$cclw_panel->add_group_field( $shipping_company, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_company, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* Country / Region */
						
			$shipping_country = $cclw_panel->add_field( array(
				'id'          => $prefix . 'shipping_country',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping Country/Region',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $shipping_country, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'shipping_country',
			) );
			
			$cclw_panel->add_group_field( $shipping_country, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'State/Country',
				'attributes'  => array(
					'placeholder' => 'State/Country',
				),
			) );
			
			
			
			$cclw_panel->add_group_field( $shipping_country, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			
			$cclw_panel->add_group_field( $shipping_country, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_country, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			
			
			/*Billing Adress */
			$shipping_adress = $cclw_panel->add_field( array(
				'id'          => $prefix . 'ship_address_1',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping Street address (Address Line 1)',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $shipping_adress, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'shipping_address_1',
			) );
			$cclw_panel->add_group_field( $shipping_adress, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Street address',
				'attributes'  => array(
					'placeholder' => 'Street address',
				),
			) );
			
			$cclw_panel->add_group_field( $shipping_adress, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'default' => 'House number and street name',
				
			) );
			$cclw_panel->add_group_field( $shipping_adress, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_adress, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			/* Apartment suite */
			$shipping_adress_2 = $cclw_panel->add_field( array(
				'id'          => $prefix . 'ship_address_2',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping Street address (Address Line 2)',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $shipping_adress_2, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'shipping_address_2',
			) );
			
			$cclw_panel->add_group_field( $shipping_adress_2, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				'attributes'  => array(
					'placeholder' => 'Apartment, suite, unit, etc. (optional)',
				),
				
			) );
			$cclw_panel->add_group_field( $shipping_adress_2, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_adress_2, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			
			/*Town/City*/
						
			$shipping_city = $cclw_panel->add_field( array(
				'id'          => $prefix . 'shipping_city',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping Town/City',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			$cclw_panel->add_group_field( $shipping_city, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'shipping_city',
			) );
			
			$cclw_panel->add_group_field( $shipping_city, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Town/City',
				'attributes'  => array(
					'placeholder' => 'Apartment, suite, unit, etc. (optional)',
				),
			) );
			
			$cclw_panel->add_group_field( $shipping_city, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
				
			) );
			$cclw_panel->add_group_field( $shipping_city, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_city, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* State / County */
						
			$shipping_state = $cclw_panel->add_field( array(
				'id'          => $prefix . 'shipping_state',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping State/Country',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $shipping_state, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'shipping_state',
			) );
			
			$cclw_panel->add_group_field( $shipping_state, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'State/Country',
				'attributes'  => array(
					'placeholder' => 'State/Country',
				),
			) );
			
			$cclw_panel->add_group_field( $shipping_state, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			
			$cclw_panel->add_group_field( $shipping_state, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_state, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			/* Postcode / ZIP */
						
			$shipping_postcode = $cclw_panel->add_field( array(
				'id'          => $prefix . 'shipping_postcode',
				'type'        => 'group',
				'repeatable'  => false,
				'options'     => array(
					'group_title'   => 'Shipping Postcode / ZIP',
					'closed'        => true,
				),
				'classes' => 'shipping_details_group_wrap',
			) );
			
			$cclw_panel->add_group_field( $shipping_postcode, array(
				'id'   => 'slug',
				'type' => 'hidden',
				'default' =>'shipping_postcode',
			) );
			$cclw_panel->add_group_field( $shipping_postcode, array(
				'name' => 'Label',
				'id'   => 'label',
				'type' => 'text',
				'default' => 'Postcode /ZIP',
				'attributes'  => array(
					'placeholder' => 'Postcode / ZIP',
				),				
			) );
			
			
			$cclw_panel->add_group_field( $shipping_postcode, array(
				'name' => 'Placeholder',
				'id'   => 'placeholder',
				'type' => 'text',
			) );
			
			$cclw_panel->add_group_field( $shipping_postcode, array(
				'name'    => 'Required',
				'id'      => 'required',
				'type'    => 'radio_inline',
				'options' => array(
					'true' => __( 'Yes', 'cmb2' ),
					'false'   => __( 'No', 'cmb2' ),
				),
				'default' => 'true',
			) );
			
			$cclw_panel->add_group_field( $shipping_postcode, array(
				'name'    => 'Show/Hide',
				'id'      => 'show_hide',
				'type'    => 'radio_inline',
				'options' => array(
					'show' => __( 'Show', 'cmb2' ),
					'hide'   => __( 'Hide', 'cmb2' ),
				),
				'default' => 'show',
			) );
			
			
			
			
			?>