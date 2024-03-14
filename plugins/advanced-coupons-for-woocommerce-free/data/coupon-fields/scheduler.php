<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'Scheduler', 'advanced-coupons-for-woocommerce-free' );

/**
 * Scheduler fields.
 */
return array(
    '_acfw_enable_date_range_schedule' => array(
        'label'       => __( 'Enable Scheduler', 'advanced-coupons-for-woocommerce-free' ),
        'description' => __( 'When checked, this will enable scheduler for this coupon which will then show a notification for customers in the cart when the coupon can be applied.', 'advanced-coupons-for-woocommerce-free' ),
        'type'        => 'checkbox',
        'tooltip'     => '',
        'category'    => $category,
    ),
    '_acfw_schedule_start'             => array(
        'label'       => __( 'Coupon start date', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'date',
        'tooltip'     => __( 'The exact date the coupon will be available from. Based on the timezone in this WordPress installation’s settings.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_schedule_start_error_msg'   => array(
        'label'    => __( 'Coupon start error message', 'advanced-coupons-for-woocommerce-free' ),
        'type'     => 'textarea',
        'tooltip'  => __( 'Show a custom error message to customers that try to apply this coupon before it is available.', 'advanced-coupons-for-woocommerce-free' ),
        'category' => $category,
    ),
    '_acfw_schedule_end'               => array(
        'label'       => __( 'Coupon expiry date', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'date',
        'tooltip'     => __( 'The exact date the coupon will be expired. Based on the timezone in this WordPress installation’s settings.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_schedule_expire_error_msg'  => array(
        'label'    => __( 'Coupon expire error message', 'advanced-coupons-for-woocommerce-free' ),
        'type'     => 'textarea',
        'tooltip'  => __( 'Show a custom error message to customers that try to apply this coupon after it has expired.', 'advanced-coupons-for-woocommerce-free' ),
        'category' => $category,
    ),
);
