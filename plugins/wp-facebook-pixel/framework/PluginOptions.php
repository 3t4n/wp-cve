<?php


add_filter(wp_facebook_pixel::PLUGIN_ID.'_settings_tabs', 
    function ($val) {
        global $WPFacebookPixel;

        $id = 'general';
        $name = __('General', wp_facebook_pixel::PLUGIN_ID );
        $description = __('General settings for the Remarketable.', wp_facebook_pixel::PLUGIN_ID );

        if ( ! isset( $wp_roles ) ) { $wp_roles = new WP_Roles(); }
        //$exclusionRoles['none'] = 'Track All (do not exclude tracking of any user)';
        //$exclusionRoles = array_merge($exclusionRoles, $wp_roles->role_names);
		

        $cmb = array(
            'box' => array(
                'hookup'     => false,
                'cmb_styles' => false,
                'show_on'    => array(
                    'key'   => 'options-page',
                    'value' => array( wp_facebook_pixel::PLUGIN_ID.'_settings', )
                ),
            ),
            'fields' => array(
                array(
                    'name' => __( 'Facebook Pixel ID', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Please enter the pixel ID provided by Facebook.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'facebook_pixel_id',
                    'type' => 'text',
                ),
                array(
                    'name' => __( '<em>Pro Feature</em><br />Track Title', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Include the title in your ViewContent event.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'track_title',
                    'type' => 'radio_inline',
                    'options' => array(
                        'true' => __( 'Yes', wp_facebook_pixel::PLUGIN_ID ),
                        'false' => __( 'No', wp_facebook_pixel::PLUGIN_ID ),
                    ),
                    'default' => 'true',
                    'ispro' => true,
                ),
                array (
                    'name' => __( '<em>Pro Feature</em><br />Delay ViewContent Events<br />(in seconds)', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Delay the delivery of the ViewContent events until the user has been<br />on the page for more then this many seconds.  Set to "0" to send these events immediately.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'delay_view_content',
                    'type' => 'text',
                    'attributes'    => array(
                        'data-validation-regexp'   => '/^[0-9]*$/gim',
                    ),
                    'default' => 0,
                    'ispro' => true,
                ),
                array(
                    'name' => __( '<em>Pro Feature</em><br />Track duration on page', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Automatically adds page the duration the user spent on your<br />page to the ViewContent event.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'track_view_duration',
                    'type' => 'radio_inline',
                    'options' => array(
                        'true' => __( 'Yes', wp_facebook_pixel::PLUGIN_ID ),
                        'false' => __( 'No', wp_facebook_pixel::PLUGIN_ID ),
                    ),
                    'default' => 'true',
                    'ispro' => true,
                ),
                array(
                    'name' => __( '<em>Pro Feature</em><br />Track Search Queries', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Track the terms used in searching.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'track_search',
                    'type' => 'radio_inline',
                    'options' => array(
                        'true' => __( 'Yes', wp_facebook_pixel::PLUGIN_ID ),
                        'false' => __( 'No', wp_facebook_pixel::PLUGIN_ID ),
                    ),
                    'default' => 'true',
                    'ispro' => true,
                ),
                array(
                    'name' => __( 'User Type Tracking', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Track the user types selected above.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'user_type_tracking',
                    'type' => 'multicheck',
                    'options' => $wp_roles->role_names,
                    'default' => array_keys($wp_roles->role_names),
                    'select_all_button' => false,
                ),
            ),
        );
        $val[] = $WPFacebookPixel->settings->create_tab($id, 'cmb', $name, $description, $cmb);






        $id = 'category_tracking';
        $name = __('Category Tracking', wp_facebook_pixel::PLUGIN_ID );
        $description = __('Automatically track selected category terms.  Adds the category name and term to the ViewContent event for the terms selected below.  Read more about this feature <a href="http://nightshiftapps.com/wp-facebook-pixel-category-tracking-settings/" target="_blank">here <img src="'.$WPFacebookPixel->plugin_root_dir_url.'inc/images/newtab.png" /></a>', wp_facebook_pixel::PLUGIN_ID );

        $categories = get_taxonomies(array('public' => true, 'show_tagcloud' => true), 'object');
        $category_list = array();
        foreach ($categories as $cateogry)
        {
            $terms = get_terms($cateogry->name);
            $options = array();
            foreach ($terms as $term)
            {
            	$options[$term->slug] = $term->name;
            }
                        
            $category_list[] = array(
                'name'     => 'Track '.$cateogry->label,
                'desc'     => '',
                'id'       => 'track_category_'.$cateogry->name,
                'taxonomy' => $cateogry->name,
                'type'     => 'multicheck_inline',
                'options'  => $options,
                'ispro'    => true,
            );
        }

        
        $cmb = array(
            'box' => array(
                'hookup'     => false,
                'cmb_styles' => false,
                'show_on'    => array(
                    'key'   => 'options-page',
                    'value' => array( wp_facebook_pixel::PLUGIN_ID.'_settings', )
                ),
            ),
            'fields' => array(
                array(
                    'name' => __( 'Track all category terms', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Automatically track all category terms.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'track_all_terms',
                    'type' => 'radio_inline',
                    'options' => array(
                        'true' => __( 'Yes', wp_facebook_pixel::PLUGIN_ID ),
                        'false' => __( 'No', wp_facebook_pixel::PLUGIN_ID ),
                    ),
                    'default' => 'true',
                    'ispro' => true,
                ),
                array(
                    'id'            => 'term_tracking',
                    'type'          => 'group',
                    'description'   => '',
                    'repeatable'    => false,
                    'options'       => array(
                        'group_title'   => __( 'Track specific category terms', wp_facebook_pixel::PLUGIN_ID ), // since version 1.1.4, {#} gets replaced by row number
                        'closed'        => false,
                    ),
                    'group_fields' => $category_list,

                ), 
            )
        );
        $val[] = $WPFacebookPixel->settings->create_tab($id, 'cmb', $name, $description, $cmb);






        $id = 'key_tracking';
        $name = __('Meta key Tracking', wp_facebook_pixel::PLUGIN_ID );
        $description = __('Adds the meta key and value to the ViewContent event for the keys selected below.', wp_facebook_pixel::PLUGIN_ID );

        //Get All Keys for All Posts
        $posts = array();
        $post_types = get_post_types(array('public' => true));

        foreach ($post_types as $post_type)
        {
        	$metakeys = nsau_GetMetaKeys($post_type);
            $options = array();
            foreach ($metakeys as $index => $keyName)
            {
            	$options[$keyName] = $keyName;
            }
            

            $posts[] = array(
                'name'     => 'Track '.$post_type.' Keys',
                'desc'     => '',
                'id'       => 'track_keys_'.$post_type,
                'type'     => 'multicheck_inline',
                'options'  => $options,
                'ispro'    => true,
            );
        }
        
                
        $cmb = array(
            'box' => array(
                'hookup'     => false,
                'cmb_styles' => false,
                'show_on'    => array(
                    'key'   => 'options-page',
                    'value' => array( wp_facebook_pixel::PLUGIN_ID.'_settings', )
                ),
            ),
            'fields' => array(
                array(
                    'name' => __( 'Track all post meta keys', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Automatically track all meta key values for all post types.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'track_all_keys',
                    'type' => 'radio_inline',
                    'options' => array(
                        'true' => __( 'Yes', wp_facebook_pixel::PLUGIN_ID ),
                        'false' => __( 'No', wp_facebook_pixel::PLUGIN_ID ),
                    ),
                    'default' => 'false',
                    'ispro' => true,
                ),
                array(
                    'id'            => 'key_tracking',
                    'type'          => 'group',
                    'description'   => '',
                    'repeatable'    => false,
                    'options'       => array(
                        'group_title'   => __( 'Track specific post keys', wp_facebook_pixel::PLUGIN_ID ), // since version 1.1.4, {#} gets replaced by row number
                        'closed'        => false,
                    ),
                    'group_fields' => $posts,

                ), 
            )
        );
        $val[] = $WPFacebookPixel->settings->create_tab($id, 'cmb', $name, $description, $cmb);







        $id = 'woocommerce';
        $name = __('WooCommerce Options', wp_facebook_pixel::PLUGIN_ID );
        $description = __('Options for tracking WooCommerce actions automatically.', wp_facebook_pixel::PLUGIN_ID );

        $attributes = array();
        if(!class_exists('WooCommerce')) {
            $attributes = array( 'readonly' => 'readonly', 'disabled' => 'disabled', );
            $description .= '<br /><strong style="font-size: 1.25em; background-color: yellow;">To enable these features and options, please install and activate the WooCommerce plugin.</strong>';
        }

        $cmb = array(
            'box' => array(
                'hookup'     => false,
                'cmb_styles' => false,
                'show_on'    => array(
                    'key'   => 'options-page',
                    'value' => array( wp_facebook_pixel::PLUGIN_ID.'_settings', )
                ),
            ),
            'fields' => array(
                array(
                    'type' => 'nsa_section_start',
                    'id'   => 'wc_general_section',
                    'name' => '',
                    'header' => __( 'General Settings', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'General WooCommerce settings used for all WooCommerce events.', wp_facebook_pixel::PLUGIN_ID ),
                    'element' => 'h3',
                ),
                array(
                    'name' => __( 'Product Id', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Select which value you would like to use to identify your Products.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'product_id',
                    'type' => 'radio_inline',
                    'default' => 'data-product_id',
                    'options' => array(
                        'data-product_id'   => __( 'Post ID', wp_facebook_pixel::PLUGIN_ID ),
                        'data-product_sku'  => __( 'Product SKU', wp_facebook_pixel::PLUGIN_ID ),
                    ),
                    'attributes' => $attributes,

                ), 
                array(
                    'name' => __( 'Product Value', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Allows you to use the product\'s price or a custom amount to define the product\'s value.', wp_facebook_pixel::PLUGIN_ID ),
                    'id'   => 'product_value',
                    'type' => 'radio_inline_pro',
                    'default' => 'current_price',
                    'options' => array(
                        'current_price' => __( 'Product Price', wp_facebook_pixel::PLUGIN_ID ),
                        'pro_custom_value' => __( 'Custom Value (Pro)', wp_facebook_pixel::PLUGIN_ID ),
                    ),
                    'attributes' => $attributes,
                ),
                array(
                    'type' => 'nsa_section_end',
                    'id'   => 'wc_general_section_end',
                    'name' => '',
                ),
                array(
                    'type' => 'nsa_section_start',
                    'id'   => 'wc_product_section',
                    'name' => '',
                    'header' => __( 'Product Page Events', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Configure events triggered from the WooCommerce Product page.', wp_facebook_pixel::PLUGIN_ID ),
                    'element' => 'h3',
                ),
                array(
                    'id'   => 'product_send_ViewContent',
                    'type' => 'radio_inline',
                    'name' => __( 'ViewContent', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Send the view content event when the user opens the page?', wp_facebook_pixel::PLUGIN_ID ),
                    'default' => 'true',
                    'options' => array(
                        'true'   => __( 'Yes', 'General'),
                        'false'  => __( 'No', 'General'),
                    ),
                    'attributes' => $attributes,
                ), 
                array(
                    'id'   => 'product_ViewContent_Value',
                    'type' => 'checkbox',
                    'name' => '',
                    'desc' => __( 'Include product\'s value', wp_facebook_pixel::PLUGIN_ID ),
                    //'default' => 'true',
                    'attributes' => $attributes,
                    'row_classes' => 'sub-item',
                ), 
                array(
                    'id'   => 'product_send_AddToCart',
                    'type' => 'radio_inline',
                    'name' => __( 'AddToCart', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Send the add to cart event when the user clicks the add to cart button from the page?', wp_facebook_pixel::PLUGIN_ID ),
                    'default' => 'true',
                    'options' => array(
                        'true'   => __( 'Yes', 'General'),
                        'false'  => __( 'No', 'General'),
                    ),
                    'attributes' => $attributes,
                ), 
                array(
                    'id'   => 'product_AddToCart_Value',
                    'type' => 'checkbox',
                    'name' => '',
                    'desc' => __( 'Include product\'s value', wp_facebook_pixel::PLUGIN_ID ),
                    //'default' => 'true',
                    'attributes' => $attributes,
                    'row_classes' => 'sub-item',
                ), 
                array(
                    'type' => 'nsa_section_end',
                    'id'   => 'wc_product_section_end',
                    'name' => '',
                ),
                
                array(
                    'type' => 'nsa_section_start',
                    'id'   => 'wc_shop_section',
                    'name' => '',
                    'header' => __( 'Shop Events', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Configure events triggered from the WooCommerce shop.', wp_facebook_pixel::PLUGIN_ID ),
                    'element' => 'h3',
                ),
                array(
                    'id'   => 'shop_send_AddToCart',
                    'type' => 'radio_inline',
                    'name' => __( 'AddToCart', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Send the add to cart event when the user clicks the add to cart button from the page?', wp_facebook_pixel::PLUGIN_ID ),
                    'default' => 'true',
                    'options' => array(
                        'true'   => __( 'Yes', 'General'),
                        'false'  => __( 'No', 'General'),
                    ),
                    'attributes' => $attributes,
                ), 
                array(
                    'id'   => 'shop_AddToCart_Value',
                    'type' => 'checkbox',
                    'name' => '',
                    'desc' => __( 'Include product\'s value', wp_facebook_pixel::PLUGIN_ID ),
                    //'default' => 'true',
                    'attributes' => $attributes,
                    'row_classes' => 'sub-item',
                ), 
                array(
                    'type' => 'nsa_section_end',
                    'id'   => 'wc_shop_section_end',
                    'name' => '',
                ),
                
                array(
                    'type' => 'nsa_section_start',
                    'id'   => 'wc_order_recieved_section',
                    'name' => '',
                    'header' => __( 'Order Received Events', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Configure events triggered from the WooCommerce order received page.', wp_facebook_pixel::PLUGIN_ID ),
                    'element' => 'h3',
                ),
                array(
                    'id'   => 'order_recieved_send_purchase',
                    'type' => 'radio_inline',
                    'name' => __( 'Purchase', wp_facebook_pixel::PLUGIN_ID ),
                    'desc' => __( 'Send the purchase event when the user reaches the WooCommerce order received page?', wp_facebook_pixel::PLUGIN_ID ),
                    'default' => 'true',
                    'options' => array(
                        'true'   => __( 'Yes', 'General'),
                        'false'  => __( 'No', 'General'),
                    ),
                    'attributes' => $attributes,
                ), 
                array(
                    'type' => 'nsa_section_end',
                    'id'   => 'wc_order_recived_section_end',
                    'name' => '',
                ),


            )
        );
        $val[] = $WPFacebookPixel->settings->create_tab($id, 'cmb', $name, $description, $cmb);


        $val[] = $WPFacebookPixel->settings->create_tab('support', 'page', 'Support', '', null, function() { 
            global $WPFacebookPixel; 
            require $WPFacebookPixel->plugin_root_dir.'inc/support.php'; 
        });
            

        return $val;



    }
);
























/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// TRAHS THIS
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function nsa_wpfbp_license_email() {
    global $WPFacebookPixel;

	echo "<input id='nsa_wpfbp_license_email' name='" . $WPFacebookPixel->settings->get_tab_id('pro_activation') . "[{$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_email')}]' size='25' type='text' value='{$WPFacebookPixel->ame_options[$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_email')]}' />";
	if ( $WPFacebookPixel->ame_options['nsa_wpfbp_license_email'] ) {
		echo "<span class='dashicons dashicons-yes' style='color: #66ab03;'></span>";
	} else {
		echo "<span class='dashicons dashicons-no' style='color: #ca336c;'></span>";
	}
}
function nsa_wpfbp_license_key() {
    global $WPFacebookPixel;

	echo "<input id='nsa_wpfbp_license_key' name='" . $WPFacebookPixel->settings->get_tab_id('pro_activation') . "[{$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_key')}]' size='25' type='text' value='{$WPFacebookPixel->ame_options[$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_key')]}' />";
	if ( $WPFacebookPixel->ame_options['nsa_wpfbp_license_key'] ) {
		echo "<span class='dashicons dashicons-yes' style='color: #66ab03;'></span>";
	} else {
		echo "<span class='dashicons dashicons-no' style='color: #ca336c;'></span>";
	}
}


// Sanitizes and validates all input and output for Dashboard
function nsa_wpfbp_validate_options( $input ) {
    global $WPFacebookPixel;

    
	// Load existing options, validate, and update with changes from input before returning
	$options = $WPFacebookPixel->ame_options;
	$options[$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_email')] = trim( $input[$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_email')] );
    $options[$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_key')] = trim( $input[$WPFacebookPixel->settings->get_field_id('pro_activation', 'license_key')] );




    ///**
    //    * Plugin Activation
    //    */
    //$api_email = trim( $input[nsa_wpfbp_pro()->ame_activation_email] );
    //$api_key = trim( $input[nsa_wpfbp_pro()->ame_api_key] );

    //$activation_status = get_option( nsa_wpfbp_pro()->ame_activated_key );
    //$checkbox_status = get_option( nsa_wpfbp_pro()->ame_deactivate_checkbox );

    //$current_api_key = nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_api_key];

    //// Should match the settings_fields() value
    //if ( $_REQUEST['option_page'] != nsa_wpfbp_pro()->ame_deactivate_checkbox ) {

    //    if ( $activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $checkbox_status == 'on' || $current_api_key != $api_key  ) {

    //        /**
    //            * If this is a new key, and an existing key already exists in the database,
    //            * deactivate the existing key before activating the new key.
    //            */
    //        if ( $current_api_key != $api_key )
    //            $this->replace_license_key( $current_api_key );

    //        $args = array(
    //            'email' => $api_email,
    //            'licence_key' => $api_key,
    //            );

    //        $activate_results = json_decode( nsa_wpfbp_pro()->key()->activate( $args ), true );

    //        if ( $activate_results['activated'] === true ) {
    //            add_settings_error( 'activate_text', 'activate_msg', __( 'Plugin activated. ', nsa_wpfbp_pro()->text_domain ) . "{$activate_results['message']}.", 'updated' );
    //            update_option( nsa_wpfbp_pro()->ame_activated_key, 'Activated' );
    //            update_option( nsa_wpfbp_pro()->ame_deactivate_checkbox, 'off' );
    //        }

    //        if ( $activate_results == false ) {
    //            add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Try again later.', nsa_wpfbp_pro()->text_domain ), 'error' );
    //            $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //            $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //            update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //        }

    //        if ( isset( $activate_results['code'] ) ) {

    //            switch ( $activate_results['code'] ) {
    //                case '100':
    //                    add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
    //                    $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //                    $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //                    update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //                break;
    //                case '101':
    //                    add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
    //                    $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //                    $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //                    update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //                break;
    //                case '102':
    //                    add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
    //                    $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //                    $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //                    update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //                break;
    //                case '103':
    //                        add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
    //                        $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //                        $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //                        update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //                break;
    //                case '104':
    //                        add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
    //                        $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //                        $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //                        update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //                break;
    //                case '105':
    //                        add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
    //                        $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //                        $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //                        update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //                break;
    //                case '106':
    //                        add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
    //                        $options[nsa_wpfbp_pro()->ame_api_key] = '';
    //                        $options[nsa_wpfbp_pro()->ame_activation_email] = '';
    //                        update_option( nsa_wpfbp_pro()->ame_options[nsa_wpfbp_pro()->ame_activated_key], 'Deactivated' );
    //                break;
    //            }

    //        }

    //    } // End Plugin Activation

    //}

	return $options;
}