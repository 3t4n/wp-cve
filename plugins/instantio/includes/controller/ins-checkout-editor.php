<?php
    /**
     * init Instantio Checkout Editor.
     * @author M Hemel Hasan
     * @since 3.1.0
     */
    ob_start();

    add_action( 'init', 'ins_defualt_billing_checkout_from', 9 );
    add_action( 'init', 'ins_defualt_shipping_checkout_from', 9 );

    function ins_defualt_billing_checkout_from() {
        // Make sure WooCommerce is loaded
        if ( function_exists('WC') && is_object(WC()) ) {

            // Store the billing fields in a global variable
            $my_plugin_billing_fields = array();

            // Billing 
            $billing_fields = WC()->checkout()->get_checkout_fields('billing');

            foreach( $billing_fields as $fieldkey => $insfields){

                $label       = !empty($insfields['label']) ? $insfields['label'] : '';
                $placeholder = !empty($insfields['placeholder']) ? $insfields['placeholder'] : '';
                $required    = !empty($insfields['required']) ? $insfields['required'] : 'false';

                $my_plugin_billing_fields[] = array(
                    'checkout_form_field_origin'  => $fieldkey,
                    'checkout_form_field_name'    => $label,
                    'checkout_form_field_place'   => $placeholder,
                    'required'                    => $required,
                    'checkout_form_field_status'  => true,
                );

            }

            return $my_plugin_billing_fields;
        }
    }

    
    function ins_defualt_shipping_checkout_from() {
        // Make sure WooCommerce is loaded
        if ( function_exists('WC') && is_object(WC()) ) {

            // Store the shiping fields in a global variable
            $my_plugin_shipping_fields = array();

            // Shpping 
            $shipping_fields = WC()->checkout()->get_checkout_fields('shipping');

            foreach( $shipping_fields as $key => $insshipfields){

                $label       = !empty($insshipfields['label']) ? $insshipfields['label'] : '';
                $placeholder = !empty($insshipfields['placeholder']) ? $insshipfields['placeholder'] : '';
                $required    = !empty($insshipfields['required']) ? $insshipfields['required'] : 'false';
                $priority    = !empty($insshipfields['priority']) ? $insshipfields['priority'] : '';

                $my_plugin_shipping_fields[] = array(
                    'checkout_shipping_form_field_origin'   => $key,
                    'checkout_shipping_form_field_name'     => $label,
                    'checkout_shipping_form_field_place'    => $placeholder,
                    'required_shipping'                     => $required,
                    'checkout_shipping_form_field_status'   => true,
                );

            }

            return $my_plugin_shipping_fields;

        }
    }
    

   
// echo ob_get_clean();
    
    
?>