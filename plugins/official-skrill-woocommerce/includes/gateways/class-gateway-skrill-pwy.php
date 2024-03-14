<?php
/**
 * Skrill Przelewy24
 *
 * This gateway is used for Skrill Przelewy24.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_PWY
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_PWY
 */
class Gateway_Skrill_PWY extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_pwy';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'pwy.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'PWY';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'PWY';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'POL' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Poland';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Przelewy24', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_PWY();
