<?php
/**
 * Sibs Ideal
 *
 * The gateway is used for Ideal.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Ideal
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for Ideal.
 */
class Gateway_Sibs_Ideal extends Sibs_Payment_Gateway {
	/**
	 * Identifier ideal
	 *
	 * @var string $id
	 */
	public $id = 'sibs_ideal';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/ideal.png';
	}
}

$obj = new Gateway_Sibs_Ideal();
