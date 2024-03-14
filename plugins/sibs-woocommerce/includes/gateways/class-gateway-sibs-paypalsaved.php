<?php
/**
 * Sibs PaypPal Recurring
 *
 * The gateway is used for PaypPal Recurring.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_PaypalSaved
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for PaypPal Recurring.
 */
class Gateway_Sibs_PaypalSaved extends Sibs_Payment_Gateway {
	/**
	 * Identifier paypal
	 *
	 * @var string $id
	 */
	public $id = 'sibs_paypalsaved';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/paypal.png';
	}
}

$obj = new Gateway_Sibs_PaypalSaved();
