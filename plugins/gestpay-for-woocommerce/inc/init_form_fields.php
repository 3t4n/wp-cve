<?php

/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2013-2016 Mauro Mascia (info@mauromascia.com)
 * Copyright: © 2017-2021 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$is_mybank = ! empty( $_GET['section'] ) && 'wc_gateway_gestpay_mybank' == $_GET['section'];
$is_consel = ! empty( $_GET['section'] ) && 'wc_gateway_gestpay_consel' == $_GET['section'];
$is_paypal = ! empty( $_GET['section'] ) && 'wc_gateway_gestpay_paypal' == $_GET['section'];
$is_paypal_bnpl = ! empty( $_GET['section'] ) && 'wc_gateway_gestpay_paypal_bnpl' == $_GET['section'];
$is_masterpass = ! empty( $_GET['section'] ) && 'wc_gateway_gestpay_masterpass' == $_GET['section'];
$is_compass = ! empty( $_GET['section'] ) && 'wc_gateway_gestpay_compass' == $_GET['section'];
$is_bancomatpay = ! empty( $_GET['section'] ) && 'wc_gateway_gestpay_bancomatpay' == $_GET['section'];

if ( ! empty( $_GET['section'] ) ) {
    $method_parts = explode( '_', $_GET['section'] );
    $method = end( $method_parts );
    $method = $method == 'gestpay' ? '' : strtoupper( $method );
}
else {
    $method = '';
}

$enable_label = "Abilita Gestpay " . $method . " se selezionato";

$base_stuff = array(
    'enabled' => array(
        'title' => $this->gw->strings['gateway_enabled'],
        'type' => 'checkbox',
        'label' => $enable_label,
        'default' => 'yes'
    ),
    'title' => array(
        'title' => $this->gw->strings['gateway_title'],
        'type' => 'text',
        'description' => $this->gw->strings['gateway_title_label'],
        'default' => "Procedi con il pagamento"
    ),
    'description' => array(
        'title' => $this->gw->strings['gateway_desc'],
        'type' => 'textarea',
        'description' => $this->gw->strings['gateway_desc_label'],
        'default' => "Paga in tutta sicurezza con GestPay."
    ),
);

if ( $is_mybank ) {
    unset( $base_stuff['title'], $base_stuff['description'] );
}

$gateway = array();

if ( $is_consel ) {
    $gateway['param_consel_id_merchant'] = array(
        'title' => $this->gw->strings['gateway_consel_id'],
        'type' => 'text',
        'label' => '',
    );
    $gateway['param_consel_merchant_pro'] = array(
        'title' => $this->gw->strings['gateway_consel_code'],
        'type' => 'text',
        'description' => $this->gw->strings['gateway_consel_merchant_pro'],
    );
}

if ( $is_mybank ) {

    $gateway['param_mybank_select_required_on_desktop'] = array(
        'title' => "Selezione banca obbligatoria",
        'type' => 'checkbox',
        'label' => "Se selezionato mostra e rende obbligatoria la selezione della banca dalla lista anche nella dispositivi desktop. Per i dispositivi mobile è sempre obbligatoria.",
        'default' => 'no'
    );

    $cards = array();
}
else {
    $cards = $this->get_cards_settings();
}

$gateway_params = array_merge( $base_stuff, $gateway, $cards );

return apply_filters( 'gestpay_gateway_parameters', $gateway_params );
