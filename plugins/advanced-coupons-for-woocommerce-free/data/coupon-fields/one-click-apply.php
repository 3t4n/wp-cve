<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$category = __( 'One Click Apply', 'advanced-coupons-for-woocommerce-free' );

/**
 * One Click Apply fields.
 */
return array(
    '_acfw_enable_apply_notification'   => array(
        'label'       => __( 'Enable one click apply', 'advanced-coupons-for-woocommerce-free' ),
        'description' => __( 'Enable one click apply', 'advanced-coupons-for-woocommerce-free' ),
        'type'        => 'checkbox',
        'tooltip'     => __( 'When checked, this will enable one click apply notifications for this coupon which will then show a notification for customers in the cart when the coupon can be applied.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_apply_notification_message'  => array(
        'label'       => __( 'Notification nessage', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'textarea',
        'tooltip'     => __( 'The notification message that will be displayed after checking that the coupon is elegible in the customer’s cart.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_apply_notification_btn_text' => array(
        'label'       => __( 'Notification Button Text', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'text',
        'tooltip'     => __( 'The text for the button to apply the coupon.', 'advanced-coupons-for-woocommerce-free' ),
        'category'    => $category,
    ),
    '_acfw_apply_notification_type'     => array(
        'label'       => __( 'Notification type', 'advanced-coupons-for-woocommerce-free' ),
        'description' => '',
        'type'        => 'select',
        'options'     => array(
            'info'    => __( 'Info', 'advanced-coupons-for-woocommerce-free' ),
            'success' => __( 'Success', 'advanced-coupons-for-woocommerce-free' ),
            'error'   => __( 'Error', 'advanced-coupons-for-woocommerce-free' ),
        ),
    ),
);
