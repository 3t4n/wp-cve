<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'Auto Apply', 'advanced-coupons-for-woocommerce-free' );

/**
 * URL Coupons fields.
 */
return array(
    '_acfw_auto_apply_coupon' => array(
        'label'       => __( 'Auto apply coupon', 'advanced-coupons-for-woocommerce-free' ),
        'description' => __( 'Enable auto apply for this coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'type'        => 'checkbox',
        'tooltip'     => '',
        'category'    => $category,
    ),
);
