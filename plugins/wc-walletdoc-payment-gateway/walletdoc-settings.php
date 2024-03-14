<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

return array(

    'enabled' => array(

        'title' => __( 'Enable/Disable', 'woocommerce' ),

        'type' => 'checkbox',

        'label' => __( 'Enable Walletdoc', 'walletdoc' ),

        'default' => 'yes'

    ),

    'title' => array(

        'title' => __( 'Title*', 'woocommerce' ),

        'type' => 'text',

        'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),

        'default' => __( 'Card payment via Walletdoc', 'woocommerce' ),

        'desc_tip'      => true,

    ),

    'description' => array(

        'title'       => __( 'Description', 'woocommerce' ),

        'type'        => 'text',

        'desc_tip'    => true,

        'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),

        'default'     => __( 'Pay with your credit or debit card via Walletdoc', 'walletdoc' )

    ),

    'webhook'                       => array(

        'title'       => __( 'Webhook Endpoints', 'woocommerce' ),

        'type'        => 'title',

        /* translators: webhook URL */

        'description' => __( 'You must add the following webhook endpoint <strong style="background-color:#ddd;">&nbsp;'.get_site_url().'/wc-api/walletdoc&nbsp;</strong> to your <a href="https://www.walletdoc.com/business-webhooks" target="_blank">Walletdoc webhook settings</a>. This will enable you to receive notifications for payment & refund.', 'woocommerce' ),

    ),

    'api_details' => array(

        'title'       => __( 'API Credentials', 'walletdoc' ),

        'type'        => 'title',

        'description' => '',

    ),

    'testmode' => array(

        'id'          => '_enable_readonly',

        'title'       => __( 'Test Mode', 'walletdoc' ),

        'type'        => 'checkbox',

        'label'       => __( 'Enable Test Mode', 'walletdoc' ),

        'default'     => 'no',

    ),

    'client_secret' => array(

        'title' => __( 'Sandbox Secret Key*', 'woocommerce' ),

        'type'  => 'password',

        'description' => __( 'Your sandbox secret key is available in the App & API Keys page of your Walletdoc account', 'woocommerce' ),

        'desc_tip'      => true,

    ),

    'production_secret' => array(

        'title' => __( 'Production Secret Key *', 'woocommerce' ),

        'type'  => 'password',

        'description' => __( 'Your production secret key is available in the Apps & API Keys page of your Walletdoc account', 'woocommerce' ),

        'desc_tip'      => true,

    ),

    'saved_cards'                   => array(

        'title'       => __( 'Saved Cards', 'woocommerce' ),

        'label'       => __( 'Enable Payment via Saved Cards', 'woocommerce' ),

        'type'        => 'checkbox',

        'description' => __( 'If enabled, customer will be able to pay with a saved card during checkout. Card details are saved on Walletdoc servers, not on your store.', 'woocommerce' ),

        'default'     => 'yes',

        'desc_tip'    => true,

    ),

    'capture'                       => array(

        'title'       => __( 'Capture', 'woocommerce' ),

        'label'       => __( 'Capture Funds Immediately', 'woocommerce' ),

        'type'        => 'checkbox',

        'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only. Uncaptured funds expire in 7 days.', 'woocommerce' ),

        'default'     => 'yes',

        'desc_tip'    => true,

    ),


    
    'reference_setting'                       => array(

        'title'       => __( 'Reference', 'woocommerce' ),

        'label'       => __( 'Include customer name in the reference', 'woocommerce' ),

        'type'        => 'checkbox',

        'description' => __( 'If enabled, the first name and last name of the customer will be appended to the order number for the Walletdoc transaction reference.', 'woocommerce' ),

        'default'     => 'no',

        'desc_tip'    => true,

    ),

);