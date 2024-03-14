<?php
/**
 * Skrill EPS (Netpay)
 *
 * This gateway is used for Skrill EPS (Netpay).
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_NPY
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_NPY
 */
class Gateway_Skrill_NPY extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_npy';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'npy.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'NPY';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'NPY';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'AUT' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Austria';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'EPS (Netpay)', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_NPY();
