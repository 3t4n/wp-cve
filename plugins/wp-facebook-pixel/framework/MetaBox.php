<?php

if( ! function_exists( 'nsa_wpfbp_metabox' ) ) {
    /**
     * Adds the Facebook Pixel meta box to all post types.
     */
    function nsa_wpfbp_metabox() {
        global $WPFacebookPixel;

	    $metabox_name = 'metabox';
        
        $metabox_ID = wp_facebook_pixel::PLUGIN_ID.'_'.$metabox_name;
        $prefix = $metabox_ID.'_';

		$cmb = new_cmb2_box( array(
			'id'               => $metabox_ID,
			'title'            => __( 'Remarketable Post Settings', wp_facebook_pixel::PLUGIN_ID ),
			'object_types'     => get_post_types(), // These fields should be placed on the POST object.
			'show_names'       => true,
        ));


        

        $fields = apply_filters('nsa_wpfbp_metabox_fields', array());
        foreach ($fields as $field)
        {
        	$cmb->add_field($field);
        }
        
        


        $cmb->add_field( array(
			'id'                => $prefix . 'event',
			'type'              => 'select',
            'name'              => __( 'Event', wp_facebook_pixel::PLUGIN_ID ),
			'desc'              => __( 'Select the Facebook Standard Event.', wp_facebook_pixel::PLUGIN_ID ),
			'show_option_none'  => true,
            'default'           => 'None',
            'options'           => array(
                'ViewContent'           => __('ViewContent', wp_facebook_pixel::PLUGIN_ID ),
                'Search'                => __('Search', wp_facebook_pixel::PLUGIN_ID ),
                'AddToCart'             => __('AddToCart', wp_facebook_pixel::PLUGIN_ID ),
                'AddToWishlist'         => __('AddToWishlist', wp_facebook_pixel::PLUGIN_ID ),
                'InitiateCheckout'      => __('InitiateCheckout', wp_facebook_pixel::PLUGIN_ID ),
                'AddPaymentInfo'        => __('AddPaymentInfo', wp_facebook_pixel::PLUGIN_ID ),
                'Purchase'              => __('Purchase', wp_facebook_pixel::PLUGIN_ID ),
                'Lead'                  => __('Lead', wp_facebook_pixel::PLUGIN_ID ),
                'CompleteRegistration'  => __('CompleteRegistration', wp_facebook_pixel::PLUGIN_ID ),
            )
        ));


        $description = 'Custom Values are made up of Key and Value Pairs.<br />
                        The key can be any name you want to give your data.<br />
                        The value can be any meaningful data you will use to target your audience.<br />
                        <br />
                        The following values allowed: Any AlphaNumeric (A-Z and 0-9) Comma (,) Underscore (_) and Space ( ).<br />
                        Set the value to a dynamic value expressions noted below to set the value on the client side<br />
                        "%JavaScript Global Variable Name%"<br />
                        "?Query String Property Name?"<br />
                        Examples:<br />
                        Use "%curCartTotal%" to set the key to the value of the curCartTotal javascript variable<br />
                        Use "?search-term?" to set the key to the value of search-term found in the URL<br />';
        if(class_exists( 'WooCommerce' )) {
            $description .= '
                <br />NOTE: Remarketable automatically adds Standard Events for the WooCommerce actions.<br />For more information, visit <a href="http://nightshiftapps.com/wp-facebook-pixel/" target="_blank">Our Integration Page</a><br />';
        }

        
        $group_field_id = $cmb->add_field( array(
            'id'            => $prefix . 'event_values',
            'type'          => 'group',
            'name'          => __( 'Event custom values', wp_facebook_pixel::PLUGIN_ID ),
            'description'   => __( $description, wp_facebook_pixel::PLUGIN_ID ),
            'options'       => array(
                'group_title'   => __( 'Value {#}', wp_facebook_pixel::PLUGIN_ID ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'    => __( 'Add Another Value', wp_facebook_pixel::PLUGIN_ID ),
                'remove_button' => __( 'Remove Value', wp_facebook_pixel::PLUGIN_ID ),
                'sortable'      => false,
                'closed'        => true,
            ),
        ) );

        // Id's for group's fields only need to be unique for the group. Prefix is not needed.
        $cmb->add_group_field( $group_field_id, array(
            'name' => 'Key',
            'id'   => 'key',
            'type' => 'text',
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );
        //Validation Regular Expression: https://regex101.com/r/mG4cE2/2
        $cmb->add_group_field( $group_field_id, array(
            'name' => 'Value',
            'id'   => 'value',
            'type' => 'text_event_value',
            'attributes'    => array(
                'data-validation-regexp'   => '/^([A-Z0-9_,\040]+)$|^(\?|%|\[)([(A-Z0-9_,\040])+(\?|%|\])$/gim',
            ),

            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
        ) );

	}

    function cmb2_render_callback_for_text_event_value( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
        echo $field_type_object->input( array( 'type' => 'text' ) );
    }
    add_action( 'cmb2_render_text_event_value', 'cmb2_render_callback_for_text_event_value', 10, 5 );


    function cmb2_sanitize_text_event_value_callback( $override_value, $value ) {
        
        // Validate Input and set to '' if not valid
        //if ( ! is_email( $value ) ) {
        //    // Empty the value
        //    $value = '';
        //}
        return $value;
    }
    add_filter( 'cmb2_sanitize_text_event_value', 'cmb2_sanitize_text_event_value_callback', 10, 2 );


}
add_action( 'cmb2_admin_init', 'nsa_wpfbp_metabox' );