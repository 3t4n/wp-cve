<?php

add_filter( 'adfoin_form_providers', 'adfoin_woocommerce_add_provider' );
function adfoin_woocommerce_add_provider( $providers )
{
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        $providers['woocommerce'] = __( 'WooCommerce', 'advanced-form-integration' );
    }
    return $providers;
}

function adfoin_woocommerce_get_forms( $form_provider )
{
    if ( $form_provider != 'woocommerce' ) {
        return;
    }
    $triggers = array(
        '1'  => __( 'All New order', 'advanced-form-integration' ),
        '2'  => __( 'Order Status Processing', 'advanced-form-integration' ),
        '3'  => __( 'Order Sttus On-Hold', 'advanced-form-integration' ),
        '4'  => __( 'Order Status Completed', 'advanced-form-integration' ),
        '5'  => __( 'Order Status Failed', 'advanced-form-integration' ),
        '6'  => __( 'Order Status Pending', 'advanced-form-integration' ),
        '7'  => __( 'Order Status Refunded', 'advanced-form-integration' ),
        '8'  => __( 'Order Status Cancelled', 'advanced-form-integration' ),
        '9'  => __( 'Subscription Created', 'advanced-form-integration' ),
        '10' => __( 'Subscription Cancelled', 'advanced-form-integration' ),
        '11' => __( 'Subscription Expired', 'advanced-form-integration' ),
        '13' => __( 'Subscription Trial Ended', 'advanced-form-integration' ),
    );
    return $triggers;
}

function adfoin_woocommerce_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'woocommerce' ) {
        return;
    }
    $fields = array();
    $subscription_indexes = array(
        '9',
        '10',
        '11',
        '12',
        '13'
    );
    
    if ( in_array( $form_id, $subscription_indexes ) ) {
        $fields = adfoin_get_woocommerce_subscription_fields();
    } else {
        $fields = adfoin_get_woocommerce_order_fields();
    }
    
    return $fields;
}

function adfoin_get_woocommerce_customer_fields()
{
    $fields = array(
        'customer_id'                 => __( 'Customer ID', 'advanced-form-integration' ),
        'customer_ip_address'         => __( 'Customer IP Address', 'advanced-form-integration' ),
        'customer_user_agent'         => __( 'Customer User Agent', 'advanced-form-integration' ),
        'customer_note'               => __( 'Customer Note', 'advanced-form-integration' ),
        'billing_first_name'          => __( 'Billing First Name', 'advanced-form-integration' ),
        'billing_last_name'           => __( 'Billing Last Name', 'advanced-form-integration' ),
        'formatted_billing_full_name' => __( 'Formatted Billing Full Name', 'advanced-form-integration' ),
        'billing_company'             => __( 'Billing Company', 'advanced-form-integration' ),
        'billing_address_1'           => __( 'Billing Address 1', 'advanced-form-integration' ),
        'billing_address_2'           => __( 'Billing Address 2', 'advanced-form-integration' ),
        'billing_city'                => __( 'Billing City', 'advanced-form-integration' ),
        'billing_state'               => __( 'Billing State', 'advanced-form-integration' ),
        'billing_state_full'          => __( 'Billing State Full Name', 'advanced-form-integration' ),
        'billing_postcode'            => __( 'Billing Postcode', 'advanced-form-integration' ),
        'billing_country'             => __( 'Billing Country', 'advanced-form-integration' ),
        'billing_email'               => __( 'Billing Email', 'advanced-form-integration' ),
        'billing_phone'               => __( 'Billing Phone', 'advanced-form-integration' ),
        'formatted_billing_address'   => __( 'Formatted Billing Address', 'advanced-form-integration' ),
        'shipping_first_name'         => __( 'Shipping First Name', 'advanced-form-integration' ),
        'shipping_last_name'          => __( 'Shipping Last Name', 'advanced-form-integration' ),
        'shipping_full_name'          => __( 'Shipping Full Name', 'advanced-form-integration' ),
        'shipping_company'            => __( 'Shipping Company', 'advanced-form-integration' ),
        'shipping_address_1'          => __( 'Shipping Address 1', 'advanced-form-integration' ),
        'shipping_address_2'          => __( 'Shipping Address 2', 'advanced-form-integration' ),
        'shipping_city'               => __( 'Shipping City', 'advanced-form-integration' ),
        'shipping_state'              => __( 'Shipping State', 'advanced-form-integration' ),
        'shipping_state_full'         => __( 'Shipping State Full Name', 'advanced-form-integration' ),
        'shipping_postcode'           => __( 'Shipping Postcode', 'advanced-form-integration' ),
        'shipping_country'            => __( 'Shipping Country', 'advanced-form-integration' ),
        'shipping_email'              => __( 'Shipping Email', 'advanced-form-integration' ),
        'shipping_phone'              => __( 'Shipping Phone', 'advanced-form-integration' ),
        'formatted_shipping_address'  => __( 'Formatted Shipping Address', 'advanced-form-integration' ),
        'shipping_address_map_url'    => __( 'Shipping Address Map URL', 'advanced-form-integration' ),
    );
    
    if ( '1' == get_option( 'adfoin_general_settings_utm' ) ) {
        $special_tags = adfoin_get_special_tags( 'utm' );
        $fields = array_merge( $fields, $special_tags );
    }
    
    return $fields;
}

function adfoin_get_woocommerce_order_fields()
{
    $fields = array(
        'id'                      => __( 'Order ID', 'advanced-form-integration' ),
        'order_number'            => __( 'Order Number', 'advanced-form-integration' ),
        'parent_id'               => __( 'Parent ID', 'advanced-form-integration' ),
        'user_id'                 => __( 'User ID', 'advanced-form-integration' ),
        'payment_method'          => __( 'Payment Method', 'advanced-form-integration' ),
        'payment_method_title'    => __( 'Payment Method Title', 'advanced-form-integration' ),
        'transaction_id'          => __( 'Transaction ID', 'advanced-form-integration' ),
        'created_via'             => __( 'Order Created Via', 'advanced-form-integration' ),
        'date_completed'          => __( 'Date Completed', 'advanced-form-integration' ),
        'date_created'            => __( 'Date Created', 'advanced-form-integration' ),
        'date_modified'           => __( 'Date Modified', 'advanced-form-integration' ),
        'date_paid'               => __( 'Date Paid', 'advanced-form-integration' ),
        'cart_hash'               => __( 'Cart Hash', 'advanced-form-integration' ),
        'currency'                => __( 'Currency', 'advanced-form-integration' ),
        'total'                   => __( 'Total', 'advanced-form-integration' ),
        'formatted_order_total'   => __( 'Formatted Order Total', 'advanced-form-integration' ),
        'order_item_total'        => __( 'Order Item Total', 'advanced-form-integration' ),
        'prices_include_tax'      => __( 'Prices Include Tax', 'advanced-form-integration' ),
        'discount_total'          => __( 'Discount Total', 'advanced-form-integration' ),
        'discount_tax'            => __( 'Discount Tax', 'advanced-form-integration' ),
        'shipping_total'          => __( 'Shipping Total', 'advanced-form-integration' ),
        'shipping_tax'            => __( 'Shipping Tax', 'advanced-form-integration' ),
        'cart_tax'                => __( 'Cart Tax', 'advanced-form-integration' ),
        'total_tax'               => __( 'Total Tax', 'advanced-form-integration' ),
        'total_discount'          => __( 'Total Discount', 'advanced-form-integration' ),
        'subtotal'                => __( 'Subtotal', 'advanced-form-integration' ),
        'tax_totals'              => __( 'Tax Totals', 'advanced-form-integration' ),
        'items'                   => __( 'Items Full JSON', 'advanced-form-integration' ),
        'items_id'                => __( 'Line Item(s) ID', 'advanced-form-integration' ),
        'items_name'              => __( 'Line Item(s) Name', 'advanced-form-integration' ),
        'items_sku'               => __( 'Line Item(s) SKU', 'advanced-form-integration' ),
        'items_variation_id'      => __( 'Line Item(s) Variation ID', 'advanced-form-integration' ),
        'items_quantity'          => __( 'Line Item(s) Quantity', 'advanced-form-integration' ),
        'items_total'             => __( 'Line Item(s) Total', 'advanced-form-integration' ),
        'items_price'             => __( 'Line Item(s) Price', 'advanced-form-integration' ),
        'items_sale_price'        => __( 'Line Item(s) Sale Price', 'advanced-form-integration' ),
        'items_regular_price'     => __( 'Line Item(s) Regular Price', 'advanced-form-integration' ),
        'items_subtotal'          => __( 'Line Item(s) Subtotal', 'advanced-form-integration' ),
        'items_subtotal_tax'      => __( 'Line Item(s) Subtotal Tax', 'advanced-form-integration' ),
        'items_subtotal_with_tax' => __( 'Line Item(s) Subtotal With Tax', 'advanced-form-integration' ),
        'items_total_tax'         => __( 'Line Item(s) Total Tax', 'advanced-form-integration' ),
        'items_total_with_tax'    => __( 'Line Item(s) Total With Tax', 'advanced-form-integration' ),
        'items_number_in_cart'    => __( 'Line Item(s) Number In Cart', 'advanced-form-integration' ),
        'items_attributes'        => __( 'Line Item(s) Attributes', 'advanced-form-integration' ),
        'taxes'                   => __( 'Taxes', 'advanced-form-integration' ),
        'shipping_methods'        => __( 'Shipping Methods', 'advanced-form-integration' ),
        'shipping_method'         => __( 'Shipping Method', 'advanced-form-integration' ),
        'coupons_applied'         => __( 'Coupons Applied', 'advanced-form-integration' ),
        'coupons_amount_total'    => __( 'Coupons Amount Total', 'advanced-form-integration' ),
        'status'                  => __( 'Status', 'advanced-form-integration' ),
    );
    $customer_fields = adfoin_get_woocommerce_customer_fields();
    $fields = array_merge( $fields, $customer_fields );
    return $fields;
}

function adfoin_get_woocommerce_subscription_fields()
{
    $fields = array(
        'id'                      => __( 'Subscription ID', 'advanced-form-integration' ),
        'user_id'                 => __( 'User ID', 'advanced-form-integration' ),
        'status'                  => __( 'Subscription Status', 'advanced-form-integration' ),
        'currency'                => __( 'Currency', 'advanced-form-integration' ),
        'billing_period'          => __( 'Billing Period', 'advanced-form-integration' ),
        'billing_interval'        => __( 'Billing Interval', 'advanced-form-integration' ),
        'trial_period'            => __( 'Trial Period', 'advanced-form-integration' ),
        'is_manual'               => __( 'Manual Renewal', 'advanced-form-integration' ),
        'sign_up_fee'             => __( 'Signup Fee', 'advanced-form-integration' ),
        'start'                   => __( 'Subscription Start Date', 'advanced-form-integration' ),
        'end'                     => __( 'Subscription End Date', 'advanced-form-integration' ),
        'trial_end'               => __( 'Trial End Date', 'advanced-form-integration' ),
        'last_payment'            => __( 'Last Payement Date', 'advanced-form-integration' ),
        'next_payment'            => __( 'Next Payment Date', 'advanced-form-integration' ),
        'order_key'               => __( 'Order Key', 'advanced-form-integration' ),
        'payment_method'          => __( 'Payment Method', 'advanced-form-integration' ),
        'payment_method_title'    => __( 'Payment Method Title', 'advanced-form-integration' ),
        'transaction_id'          => __( 'Transaction ID', 'advanced-form-integration' ),
        'created_via'             => __( 'Order Created Via', 'advanced-form-integration' ),
        'total'                   => __( 'Total', 'advanced-form-integration' ),
        'formatted_order_total'   => __( 'Formatted Order Total', 'advanced-form-integration' ),
        'order_item_total'        => __( 'Order Item Total', 'advanced-form-integration' ),
        'prices_include_tax'      => __( 'Prices Include Tax', 'advanced-form-integration' ),
        'discount_total'          => __( 'Discount Total', 'advanced-form-integration' ),
        'discount_tax'            => __( 'Discount Tax', 'advanced-form-integration' ),
        'shipping_total'          => __( 'Shipping Total', 'advanced-form-integration' ),
        'shipping_tax'            => __( 'Shipping Tax', 'advanced-form-integration' ),
        'cart_tax'                => __( 'Cart Tax', 'advanced-form-integration' ),
        'total_tax'               => __( 'Total Tax', 'advanced-form-integration' ),
        'total_discount'          => __( 'Total Discount', 'advanced-form-integration' ),
        'subtotal'                => __( 'Subtotal', 'advanced-form-integration' ),
        'tax_totals'              => __( 'Tax Totals', 'advanced-form-integration' ),
        'items'                   => __( 'Items Full JSON', 'advanced-form-integration' ),
        'items_id'                => __( 'Line Item(s) ID', 'advanced-form-integration' ),
        'items_name'              => __( 'Line Item(s) Name', 'advanced-form-integration' ),
        'items_sku'               => __( 'Line Item(s) SKU', 'advanced-form-integration' ),
        'items_variation_id'      => __( 'Line Item(s) Variation ID', 'advanced-form-integration' ),
        'items_quantity'          => __( 'Line Item(s) Quantity', 'advanced-form-integration' ),
        'items_total'             => __( 'Line Item(s) Total', 'advanced-form-integration' ),
        'items_price'             => __( 'Line Item(s) Price', 'advanced-form-integration' ),
        'items_sale_price'        => __( 'Line Item(s) Sale Price', 'advanced-form-integration' ),
        'items_regular_price'     => __( 'Line Item(s) Regular Price', 'advanced-form-integration' ),
        'items_subtotal'          => __( 'Line Item(s) Subtotal', 'advanced-form-integration' ),
        'items_subtotal_tax'      => __( 'Line Item(s) Subtotal Tax', 'advanced-form-integration' ),
        'items_subtotal_with_tax' => __( 'Line Item(s) Subtotal With Tax', 'advanced-form-integration' ),
        'items_total_tax'         => __( 'Line Item(s) Total Tax', 'advanced-form-integration' ),
        'items_total_with_tax'    => __( 'Line Item(s) Total With Tax', 'advanced-form-integration' ),
    );
    $customer_fields = adfoin_get_woocommerce_customer_fields();
    $fields = array_merge( $fields, $customer_fields );
    return $fields;
}

function adfoin_woocommerce_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'woocommerce' ) {
        return;
    }
    $triggers = array(
        '1'  => __( 'All New order', 'advanced-form-integration' ),
        '2'  => __( 'Order Status Processing', 'advanced-form-integration' ),
        '3'  => __( 'Order Sttus On-Hold', 'advanced-form-integration' ),
        '4'  => __( 'Order Status Completed', 'advanced-form-integration' ),
        '5'  => __( 'Order Status Failed', 'advanced-form-integration' ),
        '6'  => __( 'Order Status Pending', 'advanced-form-integration' ),
        '7'  => __( 'Order Status Refunded', 'advanced-form-integration' ),
        '8'  => __( 'Order Status Cancelled', 'advanced-form-integration' ),
        '9'  => __( 'Subscription Created', 'advanced-form-integration' ),
        '10' => __( 'Subscription Cancelled', 'advanced-form-integration' ),
        '11' => __( 'Subscription Expired', 'advanced-form-integration' ),
        '13' => __( 'Subscription Trial Ended', 'advanced-form-integration' ),
    );
    if ( $form_id ) {
        return $triggers[$form_id];
    }
    return false;
}

// Save WooCommerce POST fields
add_action(
    'woocommerce_checkout_update_order_meta',
    'adfoin_woocommerce_save_checkout_fields',
    10,
    2
);
function adfoin_woocommerce_save_checkout_fields( $order_id )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'woocommerce' );
    if ( empty($saved_records) ) {
        return;
    }
    $fields = adfoin_get_woocommerce_order_fields();
    $field_keys = array_keys( $fields );
    $filtered = array();
    if ( isset( $_POST ) && is_array( $_POST ) ) {
        foreach ( $_POST as $key => $value ) {
            if ( is_string( $value ) && !in_array( $key, $field_keys ) ) {
                $filtered[$key] = adfoin_sanitize_text_or_array_field( $value );
            }
        }
    }
    $applied_coupons = WC()->cart->get_applied_coupons();
    
    if ( $applied_coupons ) {
        $filtered['coupons_applied'] = implode( $applied_coupons );
        $amounts = array();
        foreach ( $applied_coupons as $coupon ) {
            $amounts[] = WC()->cart->get_coupon_discount_amount( $coupon, false );
        }
        $coupon_total_amount = array_sum( $amounts );
        $filtered['coupons_amount_total'] = $coupon_total_amount;
    }
    
    update_option( 'adfoin_wc_checkout_fields', maybe_serialize( $filtered ) );
    return;
}

add_action(
    'woocommerce_new_order',
    'adfoin_woocommerce_after_admin_order',
    10,
    2
);
function adfoin_woocommerce_after_admin_order( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    $via = $order->get_created_via();
    if ( !($via == 'admin' || $via == 'rest-api') ) {
        return;
    }
    $order = apply_filters( 'adfoin_woocommerce_after_admin_order', $order );
    adfoin_woocommerce_after_submission( $order, 1 );
}

add_action(
    'woocommerce_checkout_order_created',
    'adfoin_woocommerce_after_checkout_order',
    10,
    1
);
function adfoin_woocommerce_after_checkout_order( $order )
{
    adfoin_woocommerce_after_submission( $order, 1 );
}

add_action(
    'woocommerce_order_status_processing',
    'adfoin_woocommerce_order_status_processing',
    10,
    2
);
function adfoin_woocommerce_order_status_processing( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    adfoin_woocommerce_after_submission( $order, 2 );
}

add_action(
    'woocommerce_order_status_on-hold',
    'adfoin_woocommerce_order_status_onhold',
    10,
    2
);
function adfoin_woocommerce_order_status_onhold( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    adfoin_woocommerce_after_submission( $order, 3 );
}

add_action(
    'woocommerce_order_status_completed',
    'adfoin_woocommerce_order_status_completed',
    10,
    2
);
function adfoin_woocommerce_order_status_completed( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    adfoin_woocommerce_after_submission( $order, 4 );
}

add_action(
    'woocommerce_order_status_failed',
    'adfoin_woocommerce_order_status_failed',
    10,
    2
);
function adfoin_woocommerce_order_status_failed( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    adfoin_woocommerce_after_submission( $order, 5 );
}

add_action(
    'woocommerce_order_status_pending',
    'adfoin_woocommerce_order_status_pending',
    10,
    2
);
function adfoin_woocommerce_order_status_pending( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    adfoin_woocommerce_after_submission( $order, 6 );
}

add_action(
    'woocommerce_order_status_refunded',
    'adfoin_woocommerce_order_status_refunded',
    10,
    2
);
function adfoin_woocommerce_order_status_refunded( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    adfoin_woocommerce_after_submission( $order, 7 );
}

add_action(
    'woocommerce_order_status_cancelled',
    'adfoin_woocommerce_order_status_cancelled',
    10,
    2
);
function adfoin_woocommerce_order_status_cancelled( $order_id )
{
    if ( !$order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    adfoin_woocommerce_after_submission( $order, 8 );
}

add_action( 'woocommerce_subscription_payment_complete', 'adfoin_woocommerce_subscription_created' );
function adfoin_woocommerce_subscription_created( $subscription )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'woocommerce', 9 );
    if ( empty($saved_records) ) {
        return;
    }
    adfoin_woocommerce_send_subscription_data( $subscription, $saved_records );
}

add_action( 'woocommerce_subscription_status_cancelled', 'adfoin_woocommerce_subscription_status_cancelled' );
function adfoin_woocommerce_subscription_status_cancelled( $subscription )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'woocommerce', 10 );
    if ( empty($saved_records) ) {
        return;
    }
    adfoin_woocommerce_send_subscription_data( $subscription, $saved_records );
}

add_action( 'woocommerce_subscription_status_expired', 'adfoin_woocommerce_subscription_status_expired' );
function adfoin_woocommerce_subscription_status_expired( $subscription )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'woocommerce', 11 );
    if ( empty($saved_records) ) {
        return;
    }
    adfoin_woocommerce_send_subscription_data( $subscription, $saved_records );
}

add_action( 'woocommerce_scheduled_subscription_trial_end', 'adfoin_woocommerce_subscription_trial_end' );
function adfoin_woocommerce_subscription_trial_end( $subscription_id )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'woocommerce', 13 );
    if ( empty($saved_records) ) {
        return;
    }
    if ( !function_exists( 'wcs_get_subscription' ) ) {
        return;
    }
    $subscription = wcs_get_subscription( $subscription_id );
    adfoin_woocommerce_send_subscription_data( $subscription, $saved_records );
}

function adfoin_woocommerce_send_subscription_data( $subscription, $saved_records )
{
    $posted_data = array();
    $fields = adfoin_get_woocommerce_order_fields();
    $field_keys = array_keys( $fields );
    foreach ( $field_keys as $key ) {
        
        if ( method_exists( $subscription, 'get_' . $key ) ) {
            $result = call_user_func( array( $subscription, 'get_' . $key ) );
            $posted_data[$key] = $result;
            if ( 'tax_totals' == $key ) {
                $posted_data['tax_totals'] = json_encode( $subscription->get_tax_totals() );
            }
            
            if ( 'shipping_methods' == $key ) {
                $shipping_methods = $subscription->get_shipping_methods();
                $methods_data = array();
                
                if ( is_array( $shipping_methods ) ) {
                    foreach ( $shipping_methods as $single_method ) {
                        $methods_data[] = $single_method->get_data();
                    }
                    $posted_data['shipping_methods'] = json_encode( $methods_data );
                }
            
            }
            
            
            if ( 'taxes' == $key ) {
                $taxes = $subscription->get_taxes();
                $taxes_data = array();
                
                if ( is_array( $taxes ) ) {
                    foreach ( $taxes as $single_tax ) {
                        $taxes_data[] = $single_tax->get_data();
                    }
                    $posted_data['taxes'] = json_encode( $taxes_data );
                }
            
            }
        
        }
    
    }
    $posted_data['manual_renewal'] = $subscription->is_manual();
    $posted_data['start_date'] = $subscription->get_date( 'start' );
    $posted_data['end_date'] = $subscription->get_date( 'end' );
    $posted_data['trial_end_date'] = $subscription->get_date( 'trial_end' );
    $posted_data['last_payment_date'] = $subscription->get_date( 'last_payment' );
    $posted_data['next_payment_date'] = $subscription->get_date( 'next_payment' );
    
    if ( isset( $posted_data['billing_state'] ) && $posted_data['billing_state'] ) {
        $state_full = adfoin_woocommerce_get_full_state( $subscription, 'billing' );
        $posted_data['billing_state_full'] = $state_full;
    }
    
    
    if ( isset( $posted_data['shipping_state'] ) && $posted_data['shipping_state'] ) {
        $state_full = adfoin_woocommerce_get_full_state( $subscription, 'shipping' );
        $posted_data['shipping_state_full'] = $state_full;
    }
    
    $items = $subscription->get_items();
    
    if ( is_array( $items ) ) {
        $line = 1;
        $item_data = array();
        foreach ( $items as $item ) {
            $item_data[$line]['items_id'] = $item->get_product_id();
            $item_data[$line]['items_name'] = $item->get_name();
            $item_data[$line]['items_variation_id'] = $item->get_variation_id();
            $item_data[$line]['items_quantity'] = $item->get_quantity();
            $item_data[$line]['items_subtotal'] = $item->get_subtotal();
            $item_data[$line]['items_subtotal_tax'] = $item->get_subtotal_tax();
            $item_data[$line]['items_subtotal_with_tax'] = $item->get_subtotal() + $item->get_subtotal_tax();
            $item_data[$line]['items_total_tax'] = $item->get_total_tax();
            $item_data[$line]['items_total_with_tax'] = $item->get_total_tax() + $item->get_total();
            $item_data[$line]['items_total'] = $item->get_total();
            $item_data[$line]['items'] = $item->get_data();
            $item_data[$line]['items'] = json_encode( $item_data['items'] );
            
            if ( $item->get_variation_id() ) {
                $product = wc_get_product( $item->get_variation_id() );
            } else {
                $product = wc_get_product( $item->get_product_id() );
            }
            
            $item_data[$line]['items_sku'] = $product->get_sku();
            $item_data[$line]['items_price'] = $product->get_price();
            $item_data[$line]['items_sale_price'] = $product->get_sale_price();
            $item_data[$line]['items_regular_price'] = $product->get_regular_price();
            $variation_id = $item->get_variation_id();
            $items_attributes = array();
            
            if ( $variation_id ) {
                $variation = new WC_Product_Variation( $variation_id );
                $attributes = $variation->get_attributes();
                $item_data[$line]['items_attributes'] = $items_attributes[] = implode( ',', $attributes );
            }
            
            $item_metas = adfoin_woocommerce_get_meta_tags( $saved_records, 'item' );
            foreach ( $item_metas as $item_meta ) {
                $meta_tag = str_replace( 'itemmeta_', '', $item_meta );
                $item_id = $item->get_id();
                $meta_value = wc_get_order_item_meta( (int) $item_id, $meta_tag );
                $item_data[$line][$item_meta] = $meta_value;
            }
            $line++;
        }
    }
    
    
    if ( '1' == get_option( 'adfoin_general_settings_utm' ) ) {
        $utm_data = adfoin_capture_utm_and_url_values();
        $posted_data = $posted_data + $utm_data;
    }
    
    $merged_items = array();
    $item_keys = array_keys( array_merge( ...$item_data ) );
    foreach ( $item_data as $item ) {
        foreach ( $item_keys as $key ) {
            if ( !isset( $merged_items[$key] ) ) {
                $merged_items[$key] = array();
            }
            $merged_items[$key][] = $item[$key];
        }
    }
    $posted_data = $posted_data + $merged_items;
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );
    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];
        if ( function_exists( "adfoin_{$action_provider}_send_data" ) ) {
            
            if ( $job_queue ) {
                as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                    'data' => array(
                    'record'      => $record,
                    'posted_data' => $posted_data,
                ),
                ) );
            } else {
                call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
            }
        
        }
    }
}

function adfoin_woocommerce_after_submission( $order, $form_id )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'woocommerce', $form_id );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    $fields = adfoin_get_woocommerce_order_fields();
    $field_keys = array_keys( $fields );
    foreach ( $field_keys as $key ) {
        
        if ( method_exists( $order, 'get_' . $key ) ) {
            $result = call_user_func( array( $order, 'get_' . $key ) );
            $posted_data[$key] = $result;
            if ( 'date_created' == $key ) {
                $posted_data['date_created'] = ( $order->get_date_created() !== null ? date( 'Y-m-d H:i:s', $order->get_date_created()->getOffsetTimestamp() ) : '' );
            }
            if ( 'date_modified' == $key ) {
                $posted_data['date_modified'] = ( $order->get_date_modified() !== null ? date( 'Y-m-d H:i:s', $order->get_date_modified()->getOffsetTimestamp() ) : '' );
            }
            if ( 'date_completed' == $key ) {
                $posted_data['date_completed'] = ( $order->get_date_completed() !== null ? date( 'Y-m-d H:i:s', $order->get_date_completed()->getOffsetTimestamp() ) : '' );
            }
            if ( 'tax_totals' == $key ) {
                $posted_data['tax_totals'] = json_encode( $order->get_tax_totals() );
            }
            
            if ( 'shipping_methods' == $key ) {
                $shipping_methods = $order->get_shipping_methods();
                $methods_data = array();
                
                if ( is_array( $shipping_methods ) ) {
                    foreach ( $shipping_methods as $single_method ) {
                        $methods_data[] = $single_method->get_data();
                    }
                    $posted_data['shipping_methods'] = json_encode( $methods_data );
                }
            
            }
            
            
            if ( 'taxes' == $key ) {
                $taxes = $order->get_taxes();
                $taxes_data = array();
                
                if ( is_array( $taxes ) ) {
                    foreach ( $taxes as $single_tax ) {
                        $taxes_data[] = $single_tax->get_data();
                    }
                    $posted_data['taxes'] = json_encode( $taxes_data );
                }
            
            }
        
        }
    
    }
    
    if ( isset( $posted_data['billing_state'] ) && $posted_data['billing_state'] ) {
        $state_full = adfoin_woocommerce_get_full_state( $order, 'billing' );
        $posted_data['billing_state_full'] = $state_full;
    }
    
    
    if ( isset( $posted_data['shipping_state'] ) && $posted_data['shipping_state'] ) {
        $state_full = adfoin_woocommerce_get_full_state( $order, 'shipping' );
        $posted_data['shipping_state_full'] = $state_full;
    }
    
    $items = $order->get_items();
    
    if ( is_array( $items ) ) {
        $line = 1;
        $item_data = array();
        foreach ( $items as $item ) {
            $item_data[$line]['items_id'] = $item->get_product_id();
            $item_data[$line]['items_name'] = $item->get_name();
            $item_data[$line]['items_variation_id'] = $item->get_variation_id();
            $item_data[$line]['items_quantity'] = $item->get_quantity();
            $item_data[$line]['items_subtotal'] = $item->get_subtotal();
            $item_data[$line]['items_subtotal_tax'] = $item->get_subtotal_tax();
            $item_data[$line]['items_subtotal_with_tax'] = $item->get_subtotal() + $item->get_subtotal_tax();
            $item_data[$line]['items_total_tax'] = $item->get_total_tax();
            $item_data[$line]['items_total_with_tax'] = $item->get_total_tax() + $item->get_total();
            $item_data[$line]['items_total'] = $item->get_total();
            $item_data[$line]['items_number_in_cart'] = $line;
            $item_data[$line]['items'] = $item->get_data();
            $item_data[$line]['items'] = json_encode( $item_data['items'] );
            
            if ( $item->get_variation_id() ) {
                $product = wc_get_product( $item->get_variation_id() );
            } else {
                $product = wc_get_product( $item->get_product_id() );
            }
            
            $item_data[$line]['items_sku'] = $product->get_sku();
            $item_data[$line]['items_price'] = $product->get_price();
            $item_data[$line]['items_sale_price'] = $product->get_sale_price();
            $item_data[$line]['items_regular_price'] = $product->get_regular_price();
            $variation_id = $item->get_variation_id();
            $items_attributes = array();
            
            if ( $variation_id ) {
                $variation = new WC_Product_Variation( $variation_id );
                $attributes = $variation->get_attributes();
                $item_data[$line]['items_attributes'] = $items_attributes[] = implode( ',', $attributes );
            }
            
            $item_metas = adfoin_woocommerce_get_meta_tags( $saved_records, 'item' );
            foreach ( $item_metas as $item_meta ) {
                $meta_tag = str_replace( 'itemmeta_', '', $item_meta );
                $item_id = $item->get_id();
                $meta_value = wc_get_order_item_meta( (int) $item_id, $meta_tag );
                $item_data[$line][$item_meta] = $meta_value;
            }
            $line++;
        }
    }
    
    $extra_data = maybe_unserialize( get_option( 'adfoin_wc_checkout_fields' ) );
    
    if ( is_array( $extra_data ) ) {
        $posted_data = $posted_data + $extra_data;
        update_option( 'adfoin_wc_checkout_fields', maybe_serialize( array() ) );
    }
    
    $meta_data = get_post_meta( $posted_data['id'] );
    if ( $meta_data ) {
        foreach ( $meta_data as $metakey => $metavalue ) {
            $posted_data[$metakey] = ( isset( $metavalue[0] ) ? $metavalue[0] : '' );
        }
    }
    
    if ( '1' == get_option( 'adfoin_general_settings_utm' ) ) {
        $utm_data = adfoin_capture_utm_and_url_values();
        $posted_data = $posted_data + $utm_data;
    }
    
    $merged_items = array();
    $item_keys = array_keys( array_merge( ...$item_data ) );
    foreach ( $item_data as $item ) {
        foreach ( $item_keys as $key ) {
            if ( !isset( $merged_items[$key] ) ) {
                $merged_items[$key] = array();
            }
            $merged_items[$key][] = $item[$key];
        }
    }
    $user_metas = adfoin_woocommerce_get_meta_tags( $saved_records, 'user' );
    if ( is_array( $user_metas ) && !empty($user_metas) ) {
        foreach ( $user_metas as $user_meta ) {
            $meta_tag = str_replace( 'usermeta_', '', $user_meta );
            $user_id = $order->get_user_id();
            $meta_value = get_user_meta( (int) $user_id, $meta_tag );
            $posted_data[$user_meta] = $meta_value;
        }
    }
    $posted_data = $posted_data + $merged_items;
    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];
        if ( function_exists( "adfoin_{$action_provider}_send_data" ) ) {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    }
}

function adfoin_woocommerce_get_meta_tags( $saved_records, $type )
{
    $item_metas = array();
    if ( is_array( $saved_records ) ) {
        foreach ( $saved_records as $record ) {
            
            if ( isset( $record['data'] ) ) {
                $data = json_decode( $record['data'], true );
                if ( isset( $data['field_data'] ) && is_array( $data['field_data'] ) ) {
                    foreach ( $data['field_data'] as $field ) {
                        
                        if ( $type == 'item' && strpos( $field, 'itemmeta_' ) ) {
                            preg_match_all( '/itemmeta_.+?\\}\\}/', $field, $matches );
                            if ( isset( $matches[0] ) ) {
                                foreach ( $matches[0] as $match ) {
                                    $tag = str_replace( '}}', '', $match );
                                    if ( $tag ) {
                                        $item_metas[] = $tag;
                                    }
                                }
                            }
                        }
                        
                        
                        if ( $type == 'user' && strpos( $field, 'usermeta_' ) ) {
                            preg_match_all( '/usermeta_.+?\\}\\}/', $field, $matches );
                            if ( isset( $matches[0] ) ) {
                                foreach ( $matches[0] as $match ) {
                                    $tag = str_replace( '}}', '', $match );
                                    if ( $tag ) {
                                        $item_metas[] = $tag;
                                    }
                                }
                            }
                        }
                    
                    }
                }
            }
        
        }
    }
    return array_unique( $item_metas );
}

/**
 * Retrieves the full name of a state (region) based on the provided country and state code.
 *
 * This function retrieves the full name of a state (region) based on the given country and state code.
 * It is commonly used in WooCommerce to get the full name of a state for an order's billing or shipping address.
 *
 * @param WC_Order $order The WooCommerce order object.
 * @param string   $type  Optional. The type of address for which to retrieve the state.
 *                        Accepts 'billing' (default) or 'shipping'.
 * @return string|null The full name of the state (region) if found; otherwise, returns null.
 */
function adfoin_woocommerce_get_full_state( $order, $type = 'billing' )
{
    $country = ( $type === 'billing' ? $order->get_billing_country() : $order->get_shipping_country() );
    $state = ( $type === 'billing' ? $order->get_billing_state() : $order->get_shipping_state() );
    $states = WC()->countries->get_states( $country );
    $state_full = ( isset( $states[$state] ) ? $states[$state] : '' );
    return $state_full;
}
