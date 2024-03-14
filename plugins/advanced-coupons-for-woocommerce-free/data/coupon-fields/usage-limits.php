<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'Usage limits', 'advanced-coupons-for-woocommerce-free' );


/**
 * Usage limit fields.
 */
return array(
    'usage_limit'                    => array(
        'label'       => __( 'Usage Limit', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'number',
        'tooltip'     => __( 'How many times this coupon can be used before it is void', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'usage_limit_per_user'           => array(
        'label'       => __( 'Usage Limit per User', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'number',
        'tooltip'     => __( 'How many times this coupon can be used by an individual user. Uses billing email for guests, and user ID for logged in users.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'limit_usage_to_x_items'         => array(
        'label'       => __( 'Limit Usage to X Items', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'number',
        'tooltip'     => __( 'The maximum number of individual items this coupon can apply to when using product discounts. Leave blank to apply to all qualifying items in cart.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_reset_usage_limit_period' => array(
        'label'       => __( 'Reset usage count every', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'select',
        'options'     => array(
            'none'    => __( 'None', 'advanced-coupons-for-woocommerce-free' ),
            'yearly'  => __( 'Yearly', 'advanced-coupons-for-woocommerce-free' ),
            'monthly' => __( 'Monthly', 'advanced-coupons-for-woocommerce-free' ),
            'weekly'  => __( 'Weekly', 'advanced-coupons-for-woocommerce-free' ),
            'dail'    => __( 'Daily', 'advanced-coupons-for-woocommerce-free' ),
        ),
        'tooltip'     => __( 'Set the time period to reset the usage limit count. <strong>Yearly:</strong> resets at start of the year. <strong>Monthly:</strong> resets at start of the month. <strong>Weekly:</strong> resets at the start of every week (day depends on the <em>&quot;Week Starts On&quot;</em> setting). <strong>Daily:</strong> resets everyday. Time is always set at 12:00am of the local timezone settings.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '',
);
