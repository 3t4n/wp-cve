<?php

add_action('woocommerce_checkout_order_processed', 'order_made_inside_app', 10, 1);

function order_made_inside_app($order_id)
{

    $myValue = isInAppUser();
    update_post_meta( $order_id, 'order_made_inside_app', $myValue);

}

function order_phone_backend($order){
    echo "<p><strong>isInAppUser:-</strong> " . get_post_meta( $order->id, 'order_made_inside_app', true ) . "</p><br>";
} 
add_action( 'woocommerce_admin_order_data_after_billing_address','order_phone_backend', 10, 1 );