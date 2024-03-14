<?php

namespace Woo_MP\Payment_Gateways\Stripe;

defined( 'ABSPATH' ) || die;

/**
 * The Stripe settings section.
 */
class Settings_Section implements \Woo_MP\Payment_Gateway\Settings_Section {

    public function get_settings() {
        $text_style = 'width: 400px;';

        $moto_link = 'https://support.stripe.com/questions/mail-order-telephone-order-moto-transactions-when-to-categorize-transactions-as-moto';

        $moto_description = sprintf(
            /* translators: %s: Link to Stripe support page */
            __( 'You will need to contact Stripe to get this feature enabled for your account. Read more about it %s.', 'woo-mp' ),
            sprintf( '<a href="%s" target="_blank">%s</a>', $moto_link, __( 'here', 'woo-mp' ) )
        );

        return [
            [
                'title' => __( 'API Keys', 'woo-mp' ),
                'type'  => 'title',
                'desc'  => WOO_MP_CONFIG_HELP,
            ],
            [
                'title'    => __( 'Secret Key', 'woo-mp' ),
                'type'     => 'text',
                'desc_tip' => __( 'You may also enter a restricted key.', 'woo-mp' ),
                'id'       => 'woo_mp_stripe_secret_key',
                'css'      => $text_style,
                'required' => true,
            ],
            [
                'title'    => __( 'Publishable Key', 'woo-mp' ),
                'type'     => 'text',
                'id'       => 'woo_mp_stripe_publishable_key',
                'css'      => $text_style,
                'required' => true,
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
                'id'       => 'woo_mp_stripe_title',
                'default'  => 'Credit Card (Stripe)',
                'desc_tip' => true,
                'css'      => $text_style,
            ],
            [
                'title'    => __( 'Mark Payments as MOTO', 'woo-mp' ),
                'type'     => 'checkbox',
                'desc'     => __( 'Enable Mail Order / Telephone Order (MOTO) SCA exemption.', 'woo-mp' ),
                'desc_tip' => $moto_description,
                'id'       => 'woo_mp_stripe_moto_enabled',
                'default'  => 'no',
            ],
            [
                'type' => 'sectionend',
            ],
        ];
    }

}
