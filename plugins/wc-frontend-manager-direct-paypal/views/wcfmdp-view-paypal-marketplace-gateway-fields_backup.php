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
        'disbursement_mode' => [
            'title'       => __( 'Disbursement Mode', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'select',
            'class'       => 'wc-enhanced-select',
            'description' => __( 'Choose whether you wish to disburse funds to the vendors immediately or hold the funds. Holding funds gives you time to conduct additional vetting or enforce other platform-specific business logic.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => 'INSTANT',
            'desc_tip'    => true,
            'options'     => [
                'INSTANT'   => __( 'Immediate', 'wc-frontend-manager-direct-paypal' ),
                // 'ON_ORDER_COMPLETE' => __( 'On Order Complete', 'wc-frontend-manager-direct-paypal' ),
                // 'DELAYED' => __( 'Delayed', 'wc-frontend-manager-direct-paypal' ),
            ],
        ],
        /*'disbursement_delay_period' => [
            'title'       => __( 'Disbursement Delay Period', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'number',
            'class'       => 'input-text regular-input ',
            'description' => __( 'Specify after how many days funds will be disburse to corresponding vendor. Maximum holding period is 29 days. After 29 days, fund will be automatically disbursed to corresponding vendor.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '7',
            'desc_tip'    => true,
            'placeholder' => __( 'Disbursement Delay Period', 'wc-frontend-manager-direct-paypal' ),
            'custom_attributes' => [
                'min' => 1,
                'max' => 29,
            ],
        ],
        'button_type'    => [
            'title'         => __( 'Payment Button Type', 'wc-frontend-manager-direct-paypal' ),
            'type'          => 'select',
            'class'         => 'wc-enhanced-select',
            'description'   => __( 'Smart Payment Buttons type is recommended.', 'wc-frontend-manager-direct-paypal' ),
            'default'       => 'smart',
            'options'       => [
                'smart'    => __( 'Smart Payment Buttons', 'wc-frontend-manager-direct-paypal' ),
                'standard' => __( 'Standard Button', 'wc-frontend-manager-direct-paypal' ),
            ],
        ],*/
        'marketplace_logo' => [
            'title'       => __( 'Marketplace Logo', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'url',
            'description' => __( 'When vendors connect their PayPal account, they will see this logo upper right corner of the PayPal connect window', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '',
        ],
        /*'display_notice_on_vendor_dashboard' => [
            'title'       => __( 'Display Notice to Connect Seller', 'wc-frontend-manager-direct-paypal' ),
            'label'       => __( 'If checked, non-connected sellers will see a notice to connect their PayPal account on their vendor dashboard.', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'checkbox',
            'description' => __( 'If this is enabled, non-connected sellers will see a notice to connect their Paypal account on their vendor dashboard.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => 'no',
            'desc_tip'    => true,
        ],
        'display_notice_to_non_connected_sellers' => [
            'title'       => __( 'Send Announcement to Connect Seller', 'wc-frontend-manager-direct-paypal' ),
            'label'       => __( 'If checked, non-connected sellers will receive announcement notice to connect their PayPal account. ', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'checkbox',
            'description' => __( 'If this is enabled non-connected sellers will receive announcement notice to connect their Paypal account once in a week by default.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => 'no',
            'desc_tip'    => true,
        ],
        'display_notice_interval' => [
            'title'       => __( 'Send Announcement Interval', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'number',
            'description' => __( 'If Send Announcement to Connect Seller setting is enabled, non-connected sellers will receive announcement notice to connect their PayPal account once in a week by default. You can control notice display interval from here.', 'wc-frontend-manager-direct-paypal' ),
            'default'     => '7',
            'desc_tip'    => false,
            'custom_attributes' => [
                'min' => 1,
            ],
        ],*/
        'webhook_message' => [
            'title'       => __( 'Webhook URL', 'wc-frontend-manager-direct-paypal' ),
            'type'        => 'title',
            'description' => wp_kses(
                sprintf(
                // translators: 1) site url 2) paypal dev doc url
                    __( 'Webhook URL will be set <strong>automatically</strong> in your application settings with required events after you provide <strong>correct API information</strong>. You don\'t have to setup webhook url manually. Only make sure webhook url is available to <code>%1$s</code> in your PayPal <a href="%2$s" target="_blank">application settings</a>.', 'wc-frontend-manager-direct-paypal' ),
                    home_url( 'wc-api/wcfm-paypal-webhook', 'https' ), 'https://developer.paypal.com/developer/applications/'
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