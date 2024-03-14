<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'Role restrictions', 'advanced-coupons-for-woocommerce-free' );

/**
 * Role restriciton fields.
 */
return array(
    '_acfw_enable_role_restriction'     => array(
        'label'       => __( 'Enable Role Restriction', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'checkbox',
        'tooltip'     => __( 'When checked, will enable role restrictions check when coupon is applied', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_role_restrictions_type'      => array(
        'label'       => __( 'Type', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'select',
        'options'     => array(
            'allowed'    => __( 'Allowed', 'advanced-coupons-for-woocommerce-free' ),
            'disallowed' => __( 'Disallowed', 'advanced-coupons-for-woocommerce-free' ),
        ),
        'tooltip'     => __( 'The type of implementation for this restriction. Select "allowed" to allow coupon only to users under the selected roles. Select "disallowed" to only allow coupon to users that don\'t fall under the selected roles.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_role_restrictions'           => array(
        'label'       => 'User roles',
        'description' => '',
        'type'        => 'user_roles',
        'tooltip'     => __( "The user roles that should/shouldn't have access to this coupon. Make sure you include admin and shop manager roles if you want them to be able to test this coupon. Guests are defined as logged out users.", 'advanced-coupons-for-woocommerce-free' ),
    ),
    '_acfw_role_restrictions_error_msg' => array(
        'label'       => __( 'Invalid role error message', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'textarea',
        'tooltip'     => __( 'The message that should be displayed to users if they are not allowed to apply this coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'placeholder' => __( 'You are not allowed to use this coupon.', 'advanced-coupons-for-woocommerce-free' ),
    ),
);
