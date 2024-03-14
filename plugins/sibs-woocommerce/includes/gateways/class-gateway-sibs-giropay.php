<?php
/**
 * Sibs Giropay
 *
 * The gateway is used for Giropay.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Giropay
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for Giropay.
 */
class Gateway_Sibs_Giropay extends Sibs_Payment_Gateway {
	/**
	 * Identifier giropay
	 *
	 * @var string $id
	 */
	public $id = 'sibs_giropay';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/giropay.png';
	}
}

$obj = new Gateway_Sibs_Giropay();
