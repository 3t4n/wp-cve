<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Settings for myPOS Checkout
 */
return array(
    'enabled' => array(
        'title'   => __( 'Enable/Disable', 'woocommerce-gateway-mypos' ),
        'label'   => __( 'Enable myPOS Checkout Payment', 'woocommerce-gateway-mypos' ),
        'type'    => 'checkbox',
        'default' => 'yes',
    ),
    'title' => array(
        'title'       => __( 'Title', 'woocommerce' ),
        'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-gateway-mypos' ),
        'default'     => __( 'Card payment - myPOS', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'desc_tip'    => true,
    ),
    'description' => array(
        'title'       => __( 'Description', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'desc_tip'    => true,
        'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-gateway-mypos' ),
        'default'     => __( 'Pay via myPOS Checkout.', 'woocommerce-gateway-mypos' )
    ),
    'test' => array(
        'title'   => __( 'Test Mode', 'woocommerce-gateway-mypos' ),
        'label'   => __( 'Enable test mode', 'woocommerce-gateway-mypos' ),
        'type'    => 'checkbox',
        'default' => 'yes',
    ),
    'debug' => array(
        'title'   => __( 'Logging', 'woocommerce-gateway-mypos' ),
        'label'   => __( 'Enable logging', 'woocommerce-gateway-mypos' ),
        'type'    => 'checkbox',
        'default' => 'yes',
    ),
    'use_deadline_for_orders' => array(
        'label'   => __( 'Cancel my pending orders from myPOS Checkout after 24 hours', 'woocommerce-gateway-mypos' ),
        'type'    => 'checkbox',
        'default' => 'no',
    ),
    'developer_options' => array(
        'title'       => __( 'Developer (Test) options', 'woocommerce-gateway-mypos' ),
        'type'        => 'title',
        'description' => '',
    ),
    'developer_payment_method' => array(
        'title'       => __( 'Payment Method', 'woocommerce-gateway-mypos' ),
        'type'        => 'select',
        'class'       => 'wc-enhanced-select',
        'desc_tip'    => true,
        'default'     => 3,
        'options' => array(
            '1' => __( 'Card Payment', 'woocommerce-gateway-mypos' ),
            '2' => __( 'iDeal', 'woocommerce-gateway-mypos' ),
            '3' => __( 'All', 'woocommerce-gateway-mypos' ),
        ),
    ),
    'developer_easy_setup' => array(
        'type'        => 'title',
        'css'        => 'color: grey;',
        'description'       => __( 'Easy setup', 'woocommerce-gateway-mypos' ),
    ),
    'developer_package' => array(
        'title'       => __( 'Configuration Pack', 'woocommerce-gateway-mypos' ),
        'type'        => 'textarea',
        'description' => __( 'The Configuration for your store is generated in your online banking at www.mypos.com > menu Online stores.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'developer_advanced_setup' => array(
        'type'        => 'title',
        'description'       => __( 'Advanced setup', 'woocommerce-gateway-mypos' ),
    ),
    'developer_sid' => array(
        'title'       => __( 'Store ID', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'description' => __( 'Store ID is given when you add a new online store. It could be reviewed in your online banking at www.mypos.com > menu Online stores.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'developer_wallet_number' => array(
        'title'       => __( 'Client Number', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'description' => __( 'You can view your myPOS Client number in your online banking at www.mypos.com', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'developer_private_key' => array(
        'title'       => __( 'Private Key', 'woocommerce-gateway-mypos' ),
        'type'        => 'textarea',
        'description' => __( 'The Private Key for your store is generated in your online banking at www.mypos.com > menu  Online stores > Keys.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'developer_public_certificate' => array(
        'title'       => __( 'myPOS Public Certificate', 'woocommerce-gateway-mypos' ),
        'type'        => 'textarea',
        'description' => __( 'The myPOS Public Certificate is available for download in your online banking at www.mypos.com > menu  Online stores > Keys.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'developer_url' => array(
        'title'       => __( 'Developer URL', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'default'     => 'https://www.mypos.com/vmp/checkout-test',
    ),
    'developer_keyindex' => array(
        'title'       => __( 'Developer Key Index', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'css' => 'margin-bottom: 100px;',
        'description' => __('The Key Index assigned to the certificate could be reviewed in your online banking at www.mypos.com > menu Online stores > Keys.', 'woocommerce-gateway-mypos'),
        'desc_tip'    => true,
    ),
    'production_options' => array(
        'title'       => __( 'Production options', 'woocommerce-gateway-mypos' ),
        'type'        => 'title',
        'description' => '',
    ),
    'production_payment_method' => array(
        'title'       => __( 'Payment Method', 'woocommerce-gateway-mypos' ),
        'type'        => 'select',
        'class'       => 'wc-enhanced-select',
        'desc_tip'    => true,
        'default'     => 3,
        'options' => array(
            '1' => __( 'Card Payment', 'woocommerce-gateway-mypos' ),
            '2' => __( 'iDeal', 'woocommerce' ),
            '3' => __( 'All', 'woocommerce' ),
        ),
    ),
    'production_ppr' => array(
        'title'       => __( 'Checkout form view', 'woocommerce-gateway-mypos' ),
        'type'        => 'select',
        'class'       => 'wc-enhanced-select',
        'description' => __( '<strong>Full payment form</strong><br/>When you choose the "Full payment form", you can collect detailed customer information on checkout - customer names, address, phone number and email. Have in mind, that if your website has a shipping form, customer should double type some of the details. All fields are mandatory. Names and email address are not editable on the payment page.<br/><br/><strong>Simplified payment form</strong><br/>Similar to the "Full payment form". However, customer names and email addresses are editable on the payment page.<br/><br/><strong>Ultra-simplified payment form</strong><br/>The most basic payment form - it requires only card details. Use this only if you collect customer details on a prior page.' ),
        'desc_tip'    => true,
        'default'     => 3,
        'options' => array(
            '1' => __( 'Full payment form', 'woocommerce-gateway-mypos' ),
            '2' => __( 'Simplified payment form', 'woocommerce-gateway-mypos' ),
            '3' => __( 'Ultra-simplified payment form', 'woocommerce-gateway-mypos' ),
        ),
    ),
    'production_easy_setup' => array(
        'type'        => 'title',
        'css'        => 'color: grey;',
        'description'       => __( 'Easy setup', 'woocommerce-gateway-mypos' ),
    ),
    'production_package' => array(
        'title'       => __( 'Configuration Pack', 'woocommerce-gateway-mypos' ),
        'type'        => 'textarea',
        'description' => __( 'The Configuration for your store is generated in your online banking at www.mypos.com > menu Online stores.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'production_advanced_setup' => array(
        'type'        => 'title',
        'css'        => 'color: grey;',
        'description'       => __( 'Advanced setup', 'woocommerce-gateway-mypos' ),
    ),
    'production_sid' => array(
        'title'       => __( 'Store ID', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'description' => __( 'Store ID is given when you add a new online store. It could be reviewed in your online banking at www.mypos.com > menu Online stores.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'production_wallet_number' => array(
        'title'       => __( 'Client Number', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'description' => __( 'You can view your myPOS Client number in your online banking at www.mypos.com', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'production_private_key' => array(
        'title'       => __( 'Private Key', 'woocommerce-gateway-mypos' ),
        'type'        => 'textarea',
        'description' => __( 'The Private Key for your store is generated in your online banking at www.mypos.com > menu Online stores > Keys.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'production_public_certificate' => array(
        'title'       => __( 'myPOS Public Certificate', 'woocommerce-gateway-mypos' ),
        'type'        => 'textarea',
        'description' => __( 'The myPOS Public Certificate is available for download in your online banking at www.mypos.com > menu Online stores > Keys.', 'woocommerce-gateway-mypos' ),
        'desc_tip'    => true,
    ),
    'production_url' => array(
        'title'       => __( 'Production URL', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'default'     => 'https://www.mypos.com/vmp/checkout',
    ),
    'production_keyindex' => array(
        'title'       => __( 'Production Key Index', 'woocommerce-gateway-mypos' ),
        'type'        => 'text',
        'description' => __('The Key Index assigned to the certificate could be reviewed in your online banking at www.mypos.com > menu Online stores > Keys.', 'woocommerce-gateway-mypos'),
        'desc_tip'    => true,
    ),

    'merchant_wallet_number' => array(
        'title'       => '', //__( 'Merchant wallet number', 'woocommerce' ),
        'type'        => 'hidden',
        'description' => '', //__('Merchant number for send money on order complete', 'woocommerce'),
        'desc_tip'    => true,
    ),

    'merchant_send_money_reason' => array(
        'title'       => '', //__( 'Merchant wallet number', 'woocommerce' ),
        'type'        => 'hidden',
        'description' => '', //__('Merchant number for send money on order complete', 'woocommerce'),
        'desc_tip'    => true,
    ),
);
