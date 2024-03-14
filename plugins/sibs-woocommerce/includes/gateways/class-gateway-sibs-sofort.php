<?php
/**
 * Sibs Sofort Banking
 *
 * The gateway is used for Sofort Banking.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Sofort
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for Sofort Banking.
 */
class Gateway_Sibs_Sofort extends Sibs_Payment_Gateway {
	/**
	 * Identifier sofort
	 *
	 * @var string $id
	 */
	public $id = 'sibs_sofort';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		if ( 'de' === $this->language ) {
			$logo = $this->plugins_url . '/assets/images/sofort-uberweisung.png';
		} else {
			$logo = $this->plugins_url . '/assets/images/sofort-banking.png';
		}

		return $logo;
	}
}

$obj = new Gateway_Sibs_Sofort();
