<?php
/**
 * Skrill Giropay
 *
 * This gateway is used for Skrill Giropay.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_GIR
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_GIR
 */
class Gateway_Skrill_GIR extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_gir';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'gir.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'GIR';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'GIR';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'DEU' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Germany';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Giropay', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_GIR();
