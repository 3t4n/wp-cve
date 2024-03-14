<?php
/**
 * Sibs PaypPal
 *
 * The gateway is used for PaypPal.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Paypal
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for PaypPal.
 */
class Gateway_Sibs_Paypal extends Sibs_Payment_Gateway {
	/**
	 * Identifier paypal
	 *
	 * @var string $id
	 */
	public $id = 'sibs_paypal';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/paypal.png';
	}
}

$obj = new Gateway_Sibs_Paypal();
