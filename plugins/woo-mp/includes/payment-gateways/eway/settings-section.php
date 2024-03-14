<?php

namespace Woo_MP\Payment_Gateways\Eway;

defined( 'ABSPATH' ) || die;

/**
 * The Eway settings section.
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
                'title'    => __( 'API Key', 'woo-mp' ),
                'type'     => 'text',
                'id'       => 'woo_mp_eway_api_key',
                'css'      => $text_style,
                'required' => true,
            ],
            [
                'title'    => __( 'API Password', 'woo-mp' ),
                'type'     => 'text',
                'id'       => 'woo_mp_eway_api_password',
                'css'      => $text_style,
                'required' => true,
            ],
            [
                'title' => __( 'Sandbox Mode', 'woo-mp' ),
                'type'  => 'checkbox',
                'desc'  => __( 'Enable sandbox mode.', 'woo-mp' ),
                'id'    => 'woo_mp_eway_sandbox_mode',
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
                'id'       => 'woo_mp_eway_title',
                'default'  => 'Credit Card (Eway)',
                'desc_tip' => true,
                'css'      => $text_style,
            ],
            [
                'title'         => __( 'Include Order Details', 'woo-mp' ),
                'type'          => 'checkbox',
                'checkboxgroup' => 'start',
                'desc'          => __( 'Send order billing details to Eway.', 'woo-mp' ),
                'id'            => 'woo_mp_eway_include_billing_details',
                'default'       => 'yes',
            ],
            [
                'type'          => 'checkbox',
                'checkboxgroup' => 'end',
                'desc'          => __( 'Send order shipping details to Eway.', 'woo-mp' ),
                'id'            => 'woo_mp_eway_include_shipping_details',
                'default'       => 'yes',
            ],
            [
                'type' => 'sectionend',
            ],
        ];
    }

}
