<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'General', 'advanced-coupons-for-woocommerce-free' );

/**
 * General fields.
 */
return array(
    'coupon_code'                   => array(
        'label'       => __( 'Coupon Code', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'coupon_code',
        'tooltip'     => '',
        'button_txt'  => __( 'Generate', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'discount_type'                 => array(
        'label'       => __( 'Discount Type', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'select',
        'options'     => array(
            'fixed_cart'    => __( 'Fixed Cart Discount', 'advanced-coupons-for-woocommerce-free' ),
            'percent'       => __( 'Percentage Discount', 'advanced-coupons-for-woocommerce-free' ),
            'fixed_product' => __( 'Fixed Product Discount', 'advanced-coupons-for-woocommerce-free' ),
        ),
        'tooltip'     => '',
        'category'    => $category,
    ),
    'coupon_amount'                 => array(
        'label'       => __( 'Coupon Amount', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'price',
        'tooltip'     => __( 'Value of the coupon', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'free_shipping'                 => array(
        'label'       => __( 'Free Shipping', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'tooltip'     => __( 'Check this box if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see WooCommerce > Settings > Shipping > Shipping Zones).', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_force_apply_url_coupon'  => array(
        'label'       => __( 'Force Apply URL Coupon', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'options'     => array(
            'disable' => __( 'Disable', 'advanced-coupons-for-woocommerce-free' ),
            'yes'     => __( 'When applied via URL only', 'advanced-coupons-for-woocommerce-free' ),
            'all'     => __( 'Always', 'advanced-coupons-for-woocommerce-free' ),
        ),
        'tooltip'     => __( 'When enabled, conflicting coupons that are already applied to the cart will be replaced with this coupon instead. This is useful especially if you have an auto applied coupon that you want to be removed when a conflicting coupon is applied.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_coupon_label'            => array(
        'label'       => __( 'Coupon Label', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'text',
        'tooltip'     => __( 'Modify the label displayed for the coupon on the cart totals table. Add the {coupon_code} tag to this text and it will be replaced with the actual coupon code.', 'advanced-coupons-for-woocommerce-free' ),
    ),
    '_acfw_show_on_my_coupons_page' => array(
        'label'       => __( 'Show on my coupons page', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'tooltip'     => __( 'When checked, this will show the coupon in all customers my coupons page.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_percentage_discount_cap' => array(
        'label'       => __( 'Percentage discount cap', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'text',
        'tooltip'     => __( 'The maximum discount amount value allowed for this percentage type coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
);
