<?php

    // Filter Action
    add_filter('woocommerce_billing_fields', 'ins_billing_unrequire_fields');
    add_filter('woocommerce_shipping_fields', 'ins_shipping_unrequire_fields');
    add_filter('woocommerce_checkout_fields' , 'ins_override_ordernote_fields' );

    // Display the custom field data in the admin order edit screen.
    add_action( 'woocommerce_admin_order_data_after_billing_address', 'ins_custom_checkout_field_display_order_meta', 10, 1 );

    add_action( 'woocommerce_admin_order_data_after_shipping_address', 'ins_custom_checkout_field_display_order_meta_shipping', 10, 1 );

    // Hook to save the custom field data when the order is created.
    add_action('woocommerce_checkout_create_order', 'save_custom_field_to_order_meta');

    
    function save_custom_field_to_order_meta($order) {
        if (isset($_POST['ins_cus_billingfield_origin12'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_billingfield_origin12']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_billingfield_origin12', $custom_field_value);
        }

        if (isset($_POST['ins_cus_billingfield_origin13'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_billingfield_origin13']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_billingfield_origin13', $custom_field_value);
        }

        if (isset($_POST['ins_cus_billingfield_origin14'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_billingfield_origin14']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_billingfield_origin14', $custom_field_value);
        }

        if (isset($_POST['ins_cus_billingfield_origin15'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_billingfield_origin15']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_billingfield_origin15', $custom_field_value);
        }

        if (isset($_POST['ins_cus_billingfield_origin16'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_billingfield_origin16']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_billingfield_origin16', $custom_field_value);
        }

        //Shipping Fields 
        if (isset($_POST['ins_cus_shipingfield_origin10'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_shipingfield_origin10']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_shipingfield_origin10', $custom_field_value);
        } 

        if (isset($_POST['ins_cus_shipingfield_origin11'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_shipingfield_origin11']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_shipingfield_origin11', $custom_field_value);
        }

        if (isset($_POST['ins_cus_shipingfield_origin12'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_shipingfield_origin12']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_shipingfield_origin12', $custom_field_value);
        }

        if (isset($_POST['ins_cus_shipingfield_origin13'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_shipingfield_origin13']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_shipingfield_origin13', $custom_field_value);
        }

        if (isset($_POST['ins_cus_shipingfield_origin14'])) {
            // Get the custom field value from the POST data.
            $custom_field_value = sanitize_text_field($_POST['ins_cus_shipingfield_origin14']);

            // Save the custom field value to the order meta.
            $order->update_meta_data('ins_cus_shipingfield_origin14', $custom_field_value);
        }
    }

    function ins_custom_checkout_field_display_order_meta($order){
        $get_ins_data = insopt('checkout_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_for_editor_fl = unserialize($get_ins_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_for_editor_fl = $get_ins_data;
        }
        
        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){

            $field_origin   = $ins_field['checkout_form_field_origin'];

            if($field_origin == 'ins_cus_billingfield_origin12'){
                echo '<p><strong>'.$ins_field['checkout_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_billingfield_origin12', true ) . '</p>';
            } elseif ($field_origin == 'ins_cus_billingfield_origin13'){
                echo '<p><strong>'.$ins_field['checkout_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_billingfield_origin13', true ) . '</p>';
            } elseif ($field_origin == 'ins_cus_billingfield_origin14'){
                echo '<p><strong>'.$ins_field['checkout_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_billingfield_origin14', true ) . '</p>';
            } elseif ($field_origin == 'ins_cus_billingfield_origin15'){
                echo '<p><strong>'.$ins_field['checkout_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_billingfield_origin15', true ) . '</p>';
            } elseif ($field_origin == 'ins_cus_billingfield_origin16'){
                echo '<p><strong>'.$ins_field['checkout_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_billingfield_origin16', true ) . '</p>';
            }
            
        }  
    }

    function ins_custom_checkout_field_display_order_meta_shipping($order){
        $get_ins_data = insopt('checkout_shiping_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_for_editor_fl = unserialize($get_ins_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_for_editor_fl = $get_ins_data;
        }
        
        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){

            $field_origin   = $ins_field['checkout_shipping_form_field_origin'];

            if($field_origin == 'ins_cus_shipingfield_origin10'){
                echo '<p><strong>'.$ins_field['checkout_shipping_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_shipingfield_origin10', true ) . '</p>';

            } elseif ($field_origin == 'ins_cus_shipingfield_origin11'){
                echo '<p><strong>'.$ins_field['checkout_shipping_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_shipingfield_origin11', true ) . '</p>';

            } elseif ($field_origin == 'ins_cus_shipingfield_origin12'){
                echo '<p><strong>'.$ins_field['checkout_shipping_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_shipingfield_origin12', true ) . '</p>';

            } elseif ($field_origin == 'ins_cus_shipingfield_origin13'){
                echo '<p><strong>'.$ins_field['checkout_shipping_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_shipingfield_origin13', true ) . '</p>';

            } elseif ($field_origin == 'ins_cus_shipingfield_origin14'){
                echo '<p><strong>'.$ins_field['checkout_shipping_form_field_name'].':</strong> ' . get_post_meta( $order->get_id(), 'ins_cus_shipingfield_origin14', true ) . '</p>';

            }
            
        } 
    }


    /**
     * Get Billing Checkout Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Var,
     */
    function ins_over_checkout_billing_fields($fields) {

        $get_ins_data = insopt('checkout_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_for_editor_fl = unserialize($get_ins_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_for_editor_fl = $get_ins_data;
        }
        
        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];

            $field_status   = (isset($ins_field['checkout_form_field_status']) && $ins_field['checkout_form_field_status'] === '1') ? true : false;

            $required = (isset($ins_field['required']) && $ins_field['required'] === '1') ? true : false;

            // var_dump($field_origin, $fieldskey);

            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'billing_first_name'){
                $fields['billing']['billing_first_name']['label']       = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_first_name']['placeholder'] = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_first_name']['priority']    = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_first_name']);
                }

            } elseif ($field_origin == 'billing_last_name'){
                $fields['billing']['billing_last_name']['label']        = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_last_name']['placeholder']  = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_last_name']['priority']     = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_last_name']);
                }

            } elseif ($field_origin == 'billing_company'){
                $fields['billing']['billing_company']['label']          = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_company']['placeholder']    = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_company']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_company']);
                }

            } elseif ($field_origin == 'billing_email'){
                $fields['billing']['billing_email']['label']            = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_email']['placeholder']      = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_email']['priority']         = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_email']);
                }
                

            } elseif ($field_origin == 'billing_country'){
                // $fields['billing']['billing_country']['label']          = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_country']['placeholder']    = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_country']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_country']);
                }

            } elseif ($field_origin == 'billing_address_1'){
                // $fields['billing']['billing_address_1']['label']        = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_address_1']['placeholder']  = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_address_1']['priority']     = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_address_1']);
                }

            } elseif ($field_origin == 'billing_address_2'){
                // $fields['billing']['billing_address_2']['placeholder']  = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_address_2']['priority']     = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_address_2']);
                }

            } elseif ($field_origin == 'billing_city'){
                // $fields['billing']['billing_city']['label']             = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_city']['placeholder']       = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_city']['priority']          = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_city']);
                }

            } elseif ($field_origin == 'billing_state'){
                // $fields['billing']['billing_state']['label']            = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_state']['placeholder']      = $ins_field['checkout_form_field_place'];

                // $fields['billing']['billing_state']['priority']         = $fieldskey . '0';

                // if($field_status === false){
                //     unset($fields['billing']['billing_state']);
                // }
                // unset($fields['billing']['billing_state']);

            } elseif ($field_origin == 'billing_phone'){
                $fields['billing']['billing_phone']['label']             = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_phone']['placeholder']       = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_phone']['priority']          = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_phone']);
                }

            } elseif ($field_origin == 'ins_cus_billingfield_origin12'){
                $fields['billing']['ins_cus_billingfield_origin12'] = array(
                    'label'         => __($ins_field['checkout_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'      => $required,
                    'priority'      => $fieldskey . '0',
                    'class'         => array('form-row-wide'),
                    'clear'         => true
                    );

                if($field_status === false){
                    unset($fields['billing']['ins_cus_billingfield_origin12']);
                }
            } elseif ($field_origin == 'ins_cus_billingfield_origin13'){
                $fields['billing']['ins_cus_billingfield_origin13'] = array(
                    'label'     => __($ins_field['checkout_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'  => $required,
                    'priority'  => $fieldskey . '0',
                    'class'     => array('form-row-wide'),
                    'clear'     => true
                    );
                if($field_status === false){
                    unset($fields['billing']['ins_cus_billingfield_origin13']);
                }
            } elseif ($field_origin == 'ins_cus_billingfield_origin14'){
                $fields['billing']['ins_cus_billingfield_origin14'] = array(
                    'label'     => __($ins_field['checkout_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'  => $required,
                    'priority'  => $fieldskey . '0',
                    'class'     => array('form-row-wide'),
                    'clear'     => true
                    );
                if($field_status === false){
                    unset($fields['billing']['ins_cus_billingfield_origin14']);
                }
            } elseif ($field_origin == 'ins_cus_billingfield_origin15'){
                $fields['billing']['ins_cus_billingfield_origin15'] = array(
                    'label'     => __($ins_field['checkout_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'  => $required,
                    'priority'  => $fieldskey . '0',
                    'class'     => array('form-row-wide'),
                    'clear'     => true
                    );
                if($field_status === false){
                    unset($fields['billing']['ins_cus_billingfield_origin15']);
                }
            } elseif ($field_origin == 'ins_cus_billingfield_origin16'){
                $fields['billing']['ins_cus_billingfield_origin16'] = array(
                    'label'     => __($ins_field['checkout_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'  => $required,
                    'priority'  => $fieldskey . '0',
                    'class'     => array('form-row-wide'),
                    'clear'     => true
                    );
                if($field_status === false){
                    unset($fields['billing']['ins_cus_billingfield_origin16']);
                }
            }
        }
        // var_dump($field_origin, $fieldskey);

        return $fields;

    }

    /**
     * Get Checkout Blling Address Fields Data form Instantio And Updated.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Address,
     */
    function ins_over_checkout_billing_address($address_fields){
        $get_ins_add_data = insopt('checkout_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_add_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_add_editor_fl = unserialize($get_ins_add_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_add_editor_fl = $get_ins_add_data;
        }
        
        $ins_address_checkout_fields = !empty($get_ins_data_add_editor_fl) ? $get_ins_data_add_editor_fl : [];

        
        foreach( $ins_address_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];
            $field_status   = (isset($ins_field['checkout_form_field_status']) && $ins_field['checkout_form_field_status'] === '1') ? true : false;
            $required = (isset($ins_field['required']) && $ins_field['required'] === '1') ? true : false;
             
            // Check Address Fields Origin And Set Data Accordingly 
            if ($field_origin == 'billing_country'){
                $address_fields['country']['label']          = $ins_field['checkout_form_field_name'];
                $address_fields['country']['placeholder']    = $ins_field['checkout_form_field_place'];
                $address_fields['country']['priority']       = $fieldskey . '0';

            } elseif ($field_origin == 'billing_address_1'){
                $address_fields['address_1']['label']        = $ins_field['checkout_form_field_name'];
                $address_fields['address_1']['placeholder']  = $ins_field['checkout_form_field_place'];
                $address_fields['address_1']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'billing_address_2'){
                $address_fields['address_2']['placeholder']  = $ins_field['checkout_form_field_place'];
                $address_fields['address_2']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'billing_state'){
                // $address_fields['billing_state']['type']        = 'select';
                // $address_fields['billing_state']['class']       = array('form-row-wide');
                // $address_fields['state']['required']    = $ins_field['required'];
                $address_fields['state']['label']       = $ins_field['checkout_form_field_name'];
                $address_fields['state']['placeholder'] = $ins_field['checkout_form_field_place'];
                $address_fields['state']['priority']    = $fieldskey . '0';

            } elseif ($field_origin == 'billing_city'){
                $address_fields['city']['label']             = $ins_field['checkout_form_field_name'];
                $address_fields['city']['placeholder']       = $ins_field['checkout_form_field_place'];
                $address_fields['city']['priority']          = $fieldskey . '0';

            } elseif ($field_origin == 'billing_postcode'){
                $address_fields['postcode']['label']         = $ins_field['checkout_form_field_name'];
                $address_fields['postcode']['placeholder']   = $ins_field['checkout_form_field_place'];
                $address_fields['postcode']['priority']      = $fieldskey . '0';
                $address_fields['postcode']['required']      = $required;
                if($field_status === false){
                    unset($address_fields['postcode']);
                }
            }

        }
        
        return $address_fields;
    }
    

    function ins_billing_unrequire_fields($fields) {

        $get_ins_data = insopt('checkout_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_for_editor_fl = unserialize($get_ins_data);
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_for_editor_fl = $get_ins_data;
        }

        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];

            $required = (isset($ins_field['required']) && $ins_field['required'] === '1') ? true : false;
            
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'billing_first_name'){
                $fields['billing_first_name']['required']   = $required;

            } elseif ($field_origin == 'billing_last_name'){
               
                $fields['billing_last_name']['required']    = $required;

            } elseif ($field_origin == 'billing_company'){
                
                $fields['billing_company']['required']      = $required;

            } elseif ($field_origin == 'billing_email'){
                
                $fields['billing_email']['required']        = $required;

            } elseif ($field_origin == 'billing_country'){
            
                $fields['billing_country']['required']      = $required;

            } elseif ($field_origin == 'billing_address_1'){
                
                $fields['billing_address_1']['required']    = $required;

            } elseif ($field_origin == 'billing_city'){
                
                $fields['billing_city']['required']         = $required;

            } elseif ($field_origin == 'billing_state'){
                
                $fields['billing_state']['required']        = $required;

            } elseif ($field_origin == 'billing_postcode'){
                
                $fields['billing_postcode']['required']     = $required;

            } elseif ($field_origin == 'billing_phone'){
                
                $fields['billing_phone']['required']        = $required;

            }

        }

        return $fields;

    }

    
    
    
    /**
     * Get Shipping Checkout Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Var,
     */
    function ins_over_checkout_shipping_fields($fields) {

        $get_ins_data = insopt('checkout_shiping_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_for_editor_fl = unserialize($get_ins_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_for_editor_fl = $get_ins_data;
        }
        
        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_shipping_form_field_origin'];

            $field_status   = (isset($ins_field['checkout_shipping_form_field_status']) && $ins_field['checkout_shipping_form_field_status'] === '1') ? true : false;

            $required = (isset($ins_field['required_shipping']) && $ins_field['required_shipping'] === '1') ? true : false;

            // All Fields
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'shipping_first_name'){
                $fields['shipping']['shipping_first_name']['label']         = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_first_name']['placeholder']   = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_first_name']['priority']      = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_first_name']);
                }

            } elseif ($field_origin == 'shipping_last_name'){
                $fields['shipping']['shipping_last_name']['label']          = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_last_name']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_last_name']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_last_name']);
                }

            } elseif ($field_origin == 'shipping_company'){
                $fields['shipping']['shipping_company']['label']            = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_company']['placeholder']      = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_company']['priority']         = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_company']);
                }

            } elseif ($field_origin == 'shipping_country'){
                // $fields['shipping']['shipping_country']['label']            = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_country']['placeholder']      = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_country']['priority']         = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_country']);
                }

            } elseif ($field_origin == 'shipping_address_1'){
                // $fields['shipping']['shipping_address_1']['label']          = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_address_1']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_address_1']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_address_1']);
                }

            } elseif ($field_origin == 'shipping_address_2'){
                // $fields['shipping']['shipping_address_2']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_address_2']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_address_2']);
                }

            } elseif ($field_origin == 'shipping_city'){
                // $fields['shipping']['shipping_city']['label']               = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_city']['placeholder']         = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_city']['priority']            = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_city']);
                }

            } elseif ($field_origin == 'shipping_state'){
                $fields['shipping']['shipping_state']['label']              = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_state']['placeholder']        = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_state']['priority']           = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_state']);
                }

            } elseif ($field_origin == 'shipping_postcode'){
                // $fields['shipping']['shipping_postcode']['label']           = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_postcode']['placeholder']     = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_postcode']['priority']        = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_postcode']);
                }

            } elseif ($field_origin == 'ins_cus_shipingfield_origin10'){
                $fields['shipping']['ins_cus_shipingfield_origin10'] = array(
                    'label'         => __($ins_field['checkout_shipping_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_shipping_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'      => $required,
                    'priority'      => $fieldskey . '0',
                    'class'         => array('form-row-wide'),
                    'clear'         => true
                    );
                if($field_status === false){
                    unset($fields['shipping']['ins_cus_shipingfield_origin10']);
                }
            } elseif ($field_origin == 'ins_cus_shipingfield_origin11'){
                $fields['shipping']['ins_cus_shipingfield_origin11'] = array(
                    'label'         => __($ins_field['checkout_shipping_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_shipping_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'      => $required,
                    'priority'      => $fieldskey . '0',
                    'class'         => array('form-row-wide'),
                    'clear'         => true
                    );
                if($field_status === false){
                    unset($fields['shipping']['ins_cus_shipingfield_origin11']);
                }
            } elseif ($field_origin == 'ins_cus_shipingfield_origin12'){
                $fields['shipping']['ins_cus_shipingfield_origin12'] = array(
                    'label'         => __($ins_field['checkout_shipping_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_shipping_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'      => $required,
                    'priority'      => $fieldskey . '0',
                    'class'         => array('form-row-wide'),
                    'clear'         => true
                    );
                if($field_status === false){
                    unset($fields['shipping']['ins_cus_shipingfield_origin12']);
                }
            } elseif ($field_origin == 'ins_cus_shipingfield_origin13'){
                $fields['shipping']['ins_cus_shipingfield_origin13'] = array(
                    'label'         => __($ins_field['checkout_shipping_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_shipping_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'      => $required,
                    'priority'      => $fieldskey . '0',
                    'class'         => array('form-row-wide'),
                    'clear'         => true
                    );
                if($field_status === false){
                    unset($fields['shipping']['ins_cus_shipingfield_origin13']);
                }
            } elseif ($field_origin == 'ins_cus_shipingfield_origin14'){
                $fields['shipping']['ins_cus_shipingfield_origin14'] = array(
                    'label'         => __($ins_field['checkout_shipping_form_field_name'], 'woocommerce'),
                    'placeholder'   => _x($ins_field['checkout_shipping_form_field_place'], 'placeholder', 'woocommerce'),
                    'required'      => $required,
                    'priority'      => $fieldskey . '0',
                    'class'         => array('form-row-wide'),
                    'clear'         => true
                    );
                if($field_status === false){
                    unset($fields['shipping']['ins_cus_shipingfield_origin14']);
                }
            }

        }

        return $fields;

    }

    /**
     * Get Checkout Shipping Address Fields Data form Instantio And Updated.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Address,
     */
    function ins_over_checkout_shiping_address($address_fields){
        $get_ins_add_shipping_data = insopt('checkout_shiping_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_add_shipping_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_add_shiping_fl = unserialize($get_ins_add_shipping_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_add_shiping_fl = $get_ins_add_shipping_data;
        }
        
        $ins_address_shiping_fields = !empty($get_ins_data_add_shiping_fl) ? $get_ins_data_add_shiping_fl : [];

        foreach( $ins_address_shiping_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_shipping_form_field_origin'];

            // Check Address Fields Origin And Set Data Accordingly 
            if ($field_origin == 'shipping_country'){
                $address_fields['country']['label']          = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['country']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['country']['priority']       = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_address_1'){
                $address_fields['address_1']['label']        = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['address_1']['placeholder']  = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['address_1']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_address_2'){
                $address_fields['address_2']['placeholder']  = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['address_2']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_city'){
                $address_fields['city']['label']             = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['city']['placeholder']       = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['city']['priority']          = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_postcode'){
                $address_fields['postcode']['label']         = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['postcode']['placeholder']   = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['postcode']['priority']      = $fieldskey . '0';
            }

        }
        
        return $address_fields;
    }
    
    function ins_shipping_unrequire_fields($fields) {

        $get_ins_data = insopt('checkout_shiping_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_for_editor_fl = unserialize($get_ins_data);
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_for_editor_fl = $get_ins_data;
        }

        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_shipping_form_field_origin'];

            $required = (isset($ins_field['required_shipping']) && $ins_field['required_shipping'] === '1') ? true : false;
            
            // All Fields
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'shipping_first_name'){
                $fields['shipping_first_name']['required']   = $required;

            } elseif ($field_origin == 'shipping_last_name'){
               
                $fields['shipping_last_name']['required']    = $required;

            } elseif ($field_origin == 'shipping_company'){
                
                $fields['shipping_company']['required']      = $required;

            } elseif ($field_origin == 'shipping_country'){
                
                $fields['shipping_country']['required']        = $required;

            } elseif ($field_origin == 'shipping_address_1'){
            
                $fields['shipping_address_1']['required']      = $required;

            } elseif ($field_origin == 'shipping_address_2'){
                
                $fields['shipping_address_2']['required']    = $required;

            } elseif ($field_origin == 'shipping_city'){
                
                $fields['shipping_city']['required']         = $required;

            } elseif ($field_origin == 'shipping_state'){
                
                $fields['shipping_state']['required']        = $required;

            } elseif ($field_origin == 'shipping_postcode'){
                
                $fields['shipping_postcode']['required']     = $required;

            }
        }

        return $fields;

    }

    function ins_override_ordernote_fields($fields) {

        $order_note_label = isset(insopt( 'order_note_editor' )['order_note_field_label']) ? insopt( 'order_note_editor' )['order_note_field_label'] : 'Order notes';
        $order_note_place = isset(insopt( 'order_note_editor' )['order_note_field_placeh']) ? insopt( 'order_note_editor' )['order_note_field_placeh'] : 'Notes about your order, e.g. special notes for delivery.';

        $fields['order']['order_comments']['label'] = $order_note_label;
        $fields['order']['order_comments']['placeholder'] = $order_note_place;

        return $fields;
    }

    
    /**
     * Reset blliling Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return obj,
     */
    function ins_reset_blliling_fields_button() {
        echo '
            <div class="csf-title">
                <h4>' . __( "Reset Billing Fields", "instantio" ) . '</h4>
                <div class="csf-subtitle-text">' . __( "All data entered in the edit field will be erased and reset to the default position.<br><b style='color: red;'>Be aware! You will lose your old data!</b>", "instantio" ) . '</div>
            </div>
            <div class="csf-fieldset">
                <button type="button" data-delete-all="no" class="button button-large ins-del-billing-fields ins-order-remove">' . __( "Reset Fields", "instantio" ) . '</button>
            </div>
            <div class="clear"></div>
        ';
    }

    /**
     * Reset shipping Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return obj,
     */
    function ins_reset_shipping_fields_button() {
        echo '
            <div class="csf-title">
                <h4>' . __( "Reset Shipping Fields", "instantio" ) . '</h4>
                <div class="csf-subtitle-text">' . __( "All data entered in the edit field will be erased and reset to the default position.<br><b style='color: red;'>Be aware! You will lose your old data!</b>", "instantio" ) . '</div>
            </div>
            <div class="csf-fieldset">
                <button type="button" data-delete-all="no" class="button button-large ins-del-shipping-fields ins-order-remove">' . __( "Reset Fields", "instantio" ) . '</button>
            </div>
            <div class="clear"></div>
        ';
    }

?>