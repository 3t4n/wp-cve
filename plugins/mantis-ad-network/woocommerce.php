<?php

/**
 * Action is registered after a purchase
 */
function mantis_woocommerce_inject($order_id)
{
    $advertiser = get_option('mantis_advertiser_id');

    if (!$advertiser) {
        return;
    }

    $order = new WC_Order( $order_id );

    $transaction = $order->get_order_number();
    $revenue = $order->get_total();

    require(dirname(__FILE__) . '/html/advertiser/config.php');
}

add_action('woocommerce_thankyou', 'mantis_woocommerce_inject');