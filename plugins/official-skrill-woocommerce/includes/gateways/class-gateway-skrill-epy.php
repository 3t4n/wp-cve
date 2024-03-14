<?php
/**
 * Skrill ePay.bg
 *
 * This gateway is used for Skrill ePay.bg.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_EPY
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_EPY
 */
class Gateway_Skrill_EPY extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_epy';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'epy.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'EPY';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'EPY';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'BGR' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Bulgaria';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'ePay.bg', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_EPY();
