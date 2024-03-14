<?php
/**
 * PeachPay Plugin helpers.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Gets the PeachPay merchant id.
 */
function peachpay_plugin_merchant_id() {
	return get_option( 'peachpay_merchant_id', '' );
}
