<?php
/**
 * Settings for PayPal Marketplace Gateway.
 */

defined( 'ABSPATH' ) || exit;

return apply_filters(
    'wcfm_paypal_marketplace_settings_form_fields', [
        'enabled'        => [
            'title'   => __( 'Enable/Disable', 'wc-frontend-manager-direct-paypal' ),
            'type'    => 'checkbox',
            'label'   => __( 'Enable WCFM PayPal Marketplace', 'wc-frontend-manager-direct-paypal' ),
            'default' => 'no',
        ],
        'title'          => [
            'title'       => __( 'Title', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'text',
            'class'       => 'input-text regular-input ',
            'description' => __( 'This controls the title which the user sees during checkout.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => __( 'PayPal Marketplace', 'wc-frontend-manager-direct-paypal' ),
            'desc_tip'    => true,
        ],
        'description'    => [
            'title'       => __( 'Description', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'textarea',
            'description' => __( 'This controls the description which the user sees during checkout.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => __( 'Pay via PayPal Marketplace; you can pay with your credit card if you don\'t have a PayPal account', 'wc-frontend-manager-direct-paypal' ),
        ],
        'partner_id'     => [
            'title'       => __( 'PayPal Merchant ID/Partner ID', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'password',
            'class'       => 'input-text regular-input ',
            'description' => __( 'To get Merchant ID goto Paypal Dashboard --> Account Settings --> Business Information section.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '',
            'desc_tip'    => true,
            'placeholder' => 'PayPal Merchant ID/Partner ID',
        ],
        'api_details'    => array(
            'title'       => __( 'API credentials', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'title',
            'description' => wp_kses(
                sprintf(
                    __( 'Your API credentials are a client ID and secret, which authenticate API requests from your account. You get these credentials from a REST API app in the Developer Dashboard. Visit <a href="%1$s">this link</a> for more information about getting your api details.', 'wc-frontend-manager-direct-paypal' ),
                    'https://developer.paypal.com/docs/platforms/get-started/'
                ),
                [
                    'a'         => [
                        'href'   => true,
                        'target' => true,
                    ],
                ]
            ),
        ),
        'test_mode'      => [
            'title'       => __( 'PayPal sandbox', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'checkbox',
            'label'       => __( 'Enable PayPal sandbox', 'wc-frontend-manager-direct-paypal' ),
            'default'     => 'no',
            'description' => sprintf( __( 'PayPal sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>.', 'wc-frontend-manager-direct-paypal' ), 'https://developer.paypal.com/' ),
        ],
        'client_id'       => [
            'title'       => __( 'Client ID', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'password',
            'class'       => 'input-text regular-input ',
            'description' => __( 'For this payment method your need an application credential', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '',
            'desc_tip'    => true,
            'placeholder' => __( 'Client ID', 'wc-frontend-manager-direct-paypal' ),
        ],
        'client_secret'       => [
            'title'       => __( 'Client Secret', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'password',
            'class'       => 'input-text regular-input ',
            'description' => __( 'For this payment method your need an application credential', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '',
            'desc_tip'    => true,
            'placeholder' => __( 'Client Secret', 'wc-frontend-manager-direct-paypal' ),
        ],
        'sandbox_client_id'  => [
            'title'       => __( 'Sandbox Client ID', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'password',
            'class'       => 'input-text regular-input ',
            'description' => __( 'For this system please sign up in developer account and get your  application credential', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '',
            'desc_tip'    => true,
            'placeholder' => __( 'Sandbox Client ID', 'wc-frontend-manager-direct-paypal' ),
        ],
        'sandbox_client_secret'  => [
            'title'       => __( 'Sandbox Client Secret', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'password',
            'class'       => 'input-text regular-input ',
            'description' => __( 'For this system please sign up in developer account and get your  application credential', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '',
            'desc_tip'    => true,
            'placeholder' => __( 'Sandbox Client Secret', 'wc-frontend-manager-direct-paypal' ),
        ],
        'bn_code'        => [
            'title'       => __( 'PayPal Partner Attribution Id', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'text',
            'class'       => 'input-text regular-input ',
            'description' => __( 'PayPal Partner Attribution ID will be given to you after you setup your PayPal Marketplace account. If you do not have any, default one will be used.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => 'WCFM_PayPal_Partner_Attribution_ID',
            'desc_tip'    => true,
            'placeholder' => __( 'PayPal Partner Attribution Id', 'wc-frontend-manager-direct-paypal' ),
        ],
        'allow_ucc'      => [
            'title'       => __('Advanced Credit and Debit Card Payments', 'wc-frontend-manager-direct-paypal'),
            'type'        => 'checkbox',
            'label'       => __('Allow Unbranded Credit Card', 'wc-frontend-manager-direct-paypal'),
            'default'     => 'no',
            'description' => sprintf(__('If disabled then EXPRESS_CHECKOUT will be used. If enabled PPCP will be used. Country & the currency support for PPCP can be found <a href="%s">here</a>.', 'wc-frontend-manager-direct-paypal'), 'https://developer.paypal.com/docs/checkout/advanced/#link-eligibility'),
        ],
        'disbursement_mode' => [
            'title'       => __( 'Disbursement Mode', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'select',
            'class'       => 'wc-enhanced-select',
            'description' => __( 'Choose whether you wish to disburse funds to the vendors immediately or hold the funds. Holding funds gives you time to conduct additional vetting or enforce other platform-specific business logic.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => 'INSTANT',
            'desc_tip'    => true,
            'options'     => [
                'INSTANT'   => __( 'Immediate', 'wc-frontend-manager-direct-paypal' ),
            ],
        ],
        'marketplace_logo' => [
            'title'       => __( 'Marketplace Logo', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'url',
            'description' => __( 'When vendors connect their PayPal account, they will see this logo upper right corner of the PayPal connect window', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '',
        ],
        'webhook_message' => [
            'title'       => __( 'Webhook URL', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'title',
            'description' => wp_kses(
                sprintf(
                // translators: 1) site url 2) paypal dev doc url
                    __( 'Webhook URL will be set <strong>automatically</strong> in your application settings with required events after you provide <strong>correct API information</strong>. You don\'t have to setup webhook url manually. Only make sure webhook url is available to <code>%1$s</code> in your PayPal <a href="%2$s" target="_blank">application settings</a>.', 'wc-frontend-manager-direct-paypal' ),
                    WC()->api_request_url( 'wcfm-paypal-webhook', true ), 'https://developer.paypal.com/developer/applications/'
                ),
                [
                    'a'         => [
                        'href'   => true,
                        'target' => true,
                    ],
                    'code'      => [],
                    'strong'    => [],
                ]
            ),
        ],
    ]
);