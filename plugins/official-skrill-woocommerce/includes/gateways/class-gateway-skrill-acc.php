<?php
/**
 * Skrill Credit Cards
 *
 * This gateway is used for Skrill Credit Cards.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_ACC
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_ACC
 */
class Gateway_Skrill_ACC extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_acc';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'acc.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'ACC';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'VSA,MSC';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Credit Cards', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_ACC();
