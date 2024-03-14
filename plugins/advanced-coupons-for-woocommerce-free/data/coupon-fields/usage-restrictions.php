<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'Usage restrictions', 'advanced-coupons-for-woocommerce-free' );

return array(
    'minimum_amount'             => array(
        'label'       => __( 'Minimum spend', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'price',
        'tooltip'     => __( 'This field allows you to set the minimum spend (subtotal) allowed to use the coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'maximum_amount'             => array(
        'label'       => __( 'Maximum spend', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'price',
        'tooltip'     => __( 'This field allows you to set the maximum spend (subtotal) allowed when using the coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'individual_use'             => array(
        'label'       => __( 'Individual use only', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'tooltip'     => __( 'Check this box if the coupon cannot be used in conjunction with other coupons.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_excluded_coupons'     => array(
        'label'       => __( 'Exclude Coupons', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'coupons',
        'tooltip'     => __( 'This is the advanced version of the "Individual use only" field. Coupons listed here or coupons under the categories listed cannot be used in conjunction with this coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'placeholder' => __( 'Search coupons and/or coupon categories…', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'exclude_sale_items'         => array(
        'label'       => __( 'Exclude sale items', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'tooltip'     => __( 'Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'product_ids'                => array(
        'label'       => __( 'Products', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'products',
        'tooltip'     => __( 'Products that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied. Leave blank to apply to all products.', 'advanced-coupons-for-woocommerce-free' ),
        'placeholder' => __( 'Search products…', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'exclude_product_ids'        => array(
        'label'       => __( 'Exclude products', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'products',
        'tooltip'     => __( 'Products that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied. Leave blank to apply to all products.', 'advanced-coupons-for-woocommerce-free' ),
        'placeholder' => __( 'Search products…', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'product_categories'         => array(
        'label'       => __( 'Product categories', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'product_categories',
        'tooltip'     => __( 'Product categories that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied. Leave blank to apply to all categories.', 'advanced-coupons-for-woocommerce-free' ),
        'placeholder' => __( 'Search product categories…', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'exclude_product_categories' => array(
        'label'       => __( 'Exclude categories', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'product_categories',
        'tooltip'     => __( 'Product categories that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied. Leave blank to apply to all categories.', 'advanced-coupons-for-woocommerce-free' ),
        'placeholder' => __( 'Search product categories…', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    'customer_email'             => array(
        'label'       => __( 'Email restrictions', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'text',
        'tooltip'     => __( 'List of allowed emails separated by commas. Leave blank to allow all emails.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_allowed_customers'    => array(
        'label'       => __( 'Allowed customers', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'customers',
        'tooltip'     => __( 'Search and select customers that are eligible to only use this coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'placeholder' => __( 'Search customers…', 'advanced-coupons-for-woocommerce-free' ),

        'category'    => $category,
    ),
);
