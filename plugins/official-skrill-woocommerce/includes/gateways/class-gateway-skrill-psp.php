<?php
/**
 * Skrill PostePay by Visa
 *
 * This gateway is used for Skrill PostePay by Visa.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_PSP
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_PSP
 */
class Gateway_Skrill_PSP extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_psp';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'psp.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'PSP';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'PSP';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'ITA' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Italy';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'PostePay by Visa', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_PSP();
