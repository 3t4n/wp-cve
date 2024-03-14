<?php
/**
 * Sibs PayDirekt
 *
 * The gateway is used for PayDirekt.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_PayDirekt
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for PayDirekt.
 */
class Gateway_Sibs_Paydirekt extends Sibs_Payment_Gateway {
	/**
	 * Identifier paydirekt
	 *
	 * @var string $id
	 */
	public $id = 'sibs_paydirekt';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/paydirekt.png';
	}
}

$obj = new Gateway_Sibs_Paydirekt();
