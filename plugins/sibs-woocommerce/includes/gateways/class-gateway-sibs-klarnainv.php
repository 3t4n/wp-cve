<?php
/**
 * Sibs Klarna Invoice
 *
 * The gateway is used for Klarna Invoice.
 * Copyright (c) SIBS
 *
 * @class      Gateway_Sibs_Klarnainv
 * @package    Sibs/Gateway
 * @extends    Sibs_Payment_Gateway
 * @located at /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * The gateway is used for Klarna Invoice.
 */
class Gateway_Sibs_Klarnainv extends Sibs_Payment_Gateway {
	/**
	 * Identifier klarnainv
	 *
	 * @var string $id
	 */
	public $id = 'sibs_klarnainv';

	/**
	 * Get Payment Logo( s ) to get_icon()
	 *
	 * @return string
	 */
	public function sibs_get_payment_logo() {
		if ( 'de' === $this->language ) {
			$logo = $this->plugins_url . '/assets/images/klarnainv_de.png';
		} else {
			$logo = $this->plugins_url . '/assets/images/klarnainv_en.png';
		}

		return $logo;
	}
}

$obj = new Gateway_Sibs_Klarnainv();
