<?php
/**
 * Sibs Masterpass
 *
 * The gateway is used for Masterpass.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Masterpass
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for Masterpass.
 */
class Gateway_Sibs_Masterpass extends Sibs_Payment_Gateway {
	/**
	 * Identifier masterpass
	 *
	 * @var string $id
	 */
	public $id = 'sibs_masterpass';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/masterpass.png';
	}
}

$obj = new Gateway_Sibs_Masterpass();
