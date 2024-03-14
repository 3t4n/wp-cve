<?php

/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2017-2021 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Gateway_Gestpay_CONSEL extends WC_Gateway_Gestpay {
    public function __construct() {
        $this->set_this_gateway_params( 'Gestpay Consel' );
        $this->paymentType = 'CONSEL';
        $this->Helper->init_gateway( $this );

        $this->set_this_gateway();
        $this->add_actions();

        add_filter( 'gestpay_encrypt_parameters', array( $this, 'add_consel_encrypt_parameters' ), 10, 2 );
    }

    /**
     * Add parameters for CONSEL if enabled.
     * @see http://api.gestpay.it/#encrypt-example-consel
     * @see http://docs.gestpay.it/oth/consel-rate-in-rete.html
     */
    public function add_consel_encrypt_parameters( $params, $order ) {
        if ( $this->enabled == 'yes'
            && !empty( $params->paymentTypes['paymentType'] ) && $params->paymentTypes['paymentType'] == $this->paymentType
        ) {
            $params->IdMerchant = $this->get_option( 'param_consel_id_merchant' );
            $params->Consel_MerchantPro = $this->get_option( 'param_consel_merchant_pro' );

            $params->Consel_CustomerInfo = array(
                'Surname' => substr( $order->get_billing_last_name(), 0, 30 ),
                'Name' => substr( $order->get_billing_first_name(), 0, 30 ),
                'TaxationCode' => '', // this info does not exists
                'Address' => substr( $order->get_billing_address_1(), 0, 30 ),
                'City' => substr( $order->get_billing_city(), 0, 30 ),
                'StateCode' => substr( $order->get_billing_state(), 0, 30 ),
                'DateAddress' => '', // this info does not exists
                'Phone' => substr( $order->get_billing_phone(), 0, 30 ),
                'MobilePhone' => '', // this info does not exists (but can be the same of the billing phone)
            );
        }

        return $params;
    }
}