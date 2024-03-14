<?php
/**
 * Skrill Nordea Solo
 *
 * This gateway is used for Skrill Nordea Solo.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_EBT
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_EBT
 */
class Gateway_Skrill_EBT extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_ebt';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'ebt.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'EBT';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'EBT';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'SWE' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Sweden';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Nordea Solo', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_EBT();
