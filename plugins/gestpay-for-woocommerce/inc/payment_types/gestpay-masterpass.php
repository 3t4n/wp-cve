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

class WC_Gateway_Gestpay_MASTERPASS extends WC_Gateway_Gestpay {
    public function __construct() {
        $this->set_this_gateway_params( 'Gestpay Masterpass' );
        $this->paymentType = 'MASTERPASS';
        $this->Helper->init_gateway( $this );
        $this->set_this_gateway();
        $this->add_actions();
    }
}