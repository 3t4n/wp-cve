<?php

namespace Woo_MP\Payment_Gateways\Authorize_Net;

defined( 'ABSPATH' ) || die;

/**
 * The Authorize.net settings section.
 */
class Settings_Section implements \Woo_MP\Payment_Gateway\Settings_Section {

    public function get_settings() {
        $text_style = 'width: 400px;';

        return [
            [
                'title' => __( 'API Keys', 'woo-mp' ),
                'type'  => 'title',
                'desc'  => WOO_MP_CONFIG_HELP,
            ],
            [
                'title'    => __( 'Login ID', 'woo-mp' ),
                'type'     => 'text',
                'id'       => 'woo_mp_authorize_net_login_id',
                'css'      => $text_style,
                'required' => true,
            ],
            [
                'title'    => __( 'Transaction Key', 'woo-mp' ),
                'type'     => 'text',
                'id'       => 'woo_mp_authorize_net_transaction_key',
                'css'      => $text_style,
                'required' => true,
            ],
            [
                'title'    => __( 'Client Key', 'woo-mp' ),
                'type'     => 'text',
                'id'       => 'woo_mp_authorize_net_client_key',
                'css'      => $text_style,
                'required' => true,
            ],
            [
                'title' => __( 'Sandbox Mode', 'woo-mp' ),
                'type'  => 'checkbox',
                'desc'  => __( 'Enable sandbox mode.', 'woo-mp' ),
                'id'    => 'woo_mp_authorize_net_test_mode',
            ],
            [
                'type' => 'sectionend',
            ],
            [
                'title' => __( 'Settings', 'woo-mp' ),
                'type'  => 'title',
            ],
            [
                'title'    => __( 'Title', 'woo-mp' ),
                'type'     => 'text',
                'desc'     => __( 'Choose a payment method title.', 'woo-mp' ),
                'id'       => 'woo_mp_authorize_net_title',
                'default'  => 'Credit Card (Authorize.net)',
                'desc_tip' => true,
                'css'      => $text_style,
            ],
            [
                'title'         => __( 'Include Order Details', 'woo-mp' ),
                'type'          => 'checkbox',
                'checkboxgroup' => 'start',
                'desc'          => __( 'Send order billing details to Authorize.net.', 'woo-mp' ),
                'id'            => 'woo_mp_authorize_net_include_billing_details',
                'default'       => 'yes',
            ],
            [
                'type'          => 'checkbox',
                'checkboxgroup' => '',
                'desc'          => __( 'Send order shipping details to Authorize.net.', 'woo-mp' ),
                'id'            => 'woo_mp_authorize_net_include_shipping_details',
                'default'       => 'yes',
            ],
            [
                'type' => 'sectionend',
            ],
        ];
    }

}
