<?php
/**
 * Sibs Klarna Installments
 *
 * The gateway is used for Klarna Installments.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Klarnains
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for Klarna Installments.
 */
class Gateway_Sibs_Klarnains extends Sibs_Payment_Gateway {
	/**
	 * Identifier klarnains
	 *
	 * @var string $id
	 */
	public $id = 'sibs_klarnains';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		if ( 'de' === $this->language ) {
			$logo = $this->plugins_url . '/assets/images/klarnains_de.png';
		} else {
			$logo = $this->plugins_url . '/assets/images/klarnains_en.png';
		}

		return $logo;
	}
}

$obj = new Gateway_Sibs_Klarnains();
