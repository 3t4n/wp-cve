<?php
/**
 * Skrill iDEAL
 *
 * This gateway is used for Skrill iDEAL.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_IDL
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_IDL
 */
class Gateway_Skrill_IDL extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_idl';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'idl.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'IDL';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'IDL';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'NLD' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Netherlands';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'iDEAL', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_IDL();
