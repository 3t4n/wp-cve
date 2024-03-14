<?php
function mipaqueteChangeCityCustomer($fields)
{
    $cityArgs = wp_parse_args( array(
        'label' => 'Escoge la ciudad',
        'type' => 'select',
        'options' => getCitiesOption(),
        'input_class' => array(
            'wc-enhanced-select',
        )
        ) );
    wc_enqueue_js( "
        
    jQuery( ':input.wc-enhanced-select' ).filter( ':not(.enhanced)' ).each( function() {
        var select2_args = { minimumResultsForSearch: 5 };
        jQuery( this ).select2( select2_args ).addClass( 'enhanced' );
    });" );
    
    $fields['shipping']['shipping_city'] = $cityArgs;
    $fields['billing']['billing_city'] = $cityArgs; // Also change for billing field

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'mipaqueteChangeCityCustomer');

// Admin editable single orders billing and shipping city field
add_filter('woocommerce_admin_billing_fields', 'adminOrderPagesCityFields');
add_filter('woocommerce_admin_shipping_fields', 'adminOrderPagesCityFields');
function adminOrderPagesCityFields( $fields ) {
    $fields['city']['type']    = 'select';
    $fields['city']['options'] = getCitiesOption();
    $fields['city']['class']   = 'short'; // Or 'js_field-country select short' to enable selectWoo (select2).

    return $fields;
}

// Admin editable User billing and shipping city
add_filter( 'woocommerce_customer_meta_fields', 'customOverrideUserCityFields' );
function customOverrideUserCityFields( $fields ) {
    $fields['billing']['fields']['billing_city']['type']    =
    $fields['shipping']['fields']['shipping_city']['type']  = 'select';
    $fields['billing']['fields']['billing_city']['options'] =
    $fields['shipping']['fields']['shipping_city']['options'] = getCitiesOption();

    return $fields;
}
