<?php
//show method shipping mipaquete in checkout
function mipaqueteShippingMethodInit() {
        
    include('filters/shipping-normal.php');
    include('filters/shipping-upon.php');
}

function MipaqueteShippingMethod( $methods ) {
    
    $methods['mipaquete_shipping_normal'] = 'MipaqueteShippingMethod';
    $methods['mipaquete_shipping_upon_delivery'] = 'MipaqueteShippingMethodUponDelivery';
    return $methods;
}
add_action( 'woocommerce_shipping_init', 'mipaqueteShippingMethodInit' );
add_filter( 'woocommerce_shipping_methods', 'MipaqueteShippingMethod' );