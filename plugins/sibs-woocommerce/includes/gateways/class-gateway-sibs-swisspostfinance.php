<?php
/**
 * Sibs Swisspostfinance
 *
 * The gateway is used for Swisspostfinance.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Swisspostfinance
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for Swisspostfinance.
 */
class Gateway_Sibs_Swisspostfinance extends Sibs_Payment_Gateway {
	/**
	 * Identifier swisspostfinance
	 *
	 * @var string $id
	 */
	public $id = 'sibs_swisspostfinance';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/swisspostfinance.png';
	}
}

$obj = new Gateway_Sibs_Swisspostfinance();
