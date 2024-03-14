<?php
/**
 * Skrill Paysafecard
 *
 * This gateway is used for Skrill Paysafecard.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_BLK
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_BLK
 */
class Gateway_Skrill_EWLTID extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_ewltid';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'ewltid.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'RER';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'RER';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'IDN' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Indonesia';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'E-wallet Indonesia', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_EWLTID();
