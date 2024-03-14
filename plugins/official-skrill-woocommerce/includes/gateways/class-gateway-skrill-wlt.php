<?php
/**
 * Skrill Wallet
 *
 * This gateway is used for Skrill Wallet.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_WLT
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_WLT
 */
class Gateway_Skrill_WLT extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_wlt';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'wlt.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'WLT';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'WLT';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Skrill Wallet', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_WLT();
