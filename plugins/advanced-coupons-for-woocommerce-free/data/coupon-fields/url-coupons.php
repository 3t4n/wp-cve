<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'URL Coupons', 'advanced-coupons-for-woocommerce-free' );

/**
 * URL Coupons fields.
 */
return array(
    '_acfw_enable_coupon_url'      => array(
        'label'       => __( 'Enable coupon URL', 'advanced-coupons-for-woocommerce-free' ),
        'description' => __( 'When checked, it enables the coupon url functionality for the current coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'type'        => 'checkbox',
        'tooltip'     => '',
        'category'    => $category,
    ),
    '_acfw_code_url_override'      => array(
        'label'       => __( 'Coupon URL override', 'advanced-coupons-for-woocommerce-free' ),
        'description' => __( 'Customize the coupon code on the coupon url. Leave blank to disable feature.', 'advanced-coupons-for-woocommerce-free' ),
        'type'        => 'text',
        'tooltip'     => '',
        'category'    => $category,
    ),
    '_acfw_after_redirect_url'     => array(
        'label'       => __( 'Redirect to URL', 'advanced-coupons-for-woocommerce-free' ),
        'description' => __( 'This will redirect the user to the provided URL after it has been attempted to be applied. You can also pass query args to the URL for the following variables: {acfw_coupon_code}, {acfw_coupon_is_applied} or {acfw_coupon_error_message} and they will be replaced with proper data. Eg. ?foo={acfw_coupon_error_message}, then test the "foo" query arg to get the message if there is one.', 'advanced-coupons-for-woocommerce-free' ),
        'type'        => 'text',
        'tooltip'     => '',
        'category'    => $category,
    ),
    '_acfw_success_message'        => array(
        'label'       => __( 'Success message', 'advanced-coupons-for-woocommerce-free' ),
        'type'        => 'text',
        'description' => '',
        'tooltip'     => __( 'Message that will be displayed when a coupon has been applied successfully. Leave blank to use the default message.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_redirect_to_origin_url' => array(
        'label'       => __( 'Redirect back to origin', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'tooltip'     => __( 'When checked, the user will be redirected back to the original page they were in after the coupon has been applied to the cart. This is useful for adding the coupon URL as a button in a blog post or a page that you want your customers to do additional actions.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_defer_apply_url_coupon' => array(
        'label'       => __( 'Defer apply', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'tooltip'     => __( 'When checked, the coupon will not be applied to the cart until its conditions and/or restrictions are met.', 'advanced-coupons-for-woocommerce-free' ),
    ),
);
