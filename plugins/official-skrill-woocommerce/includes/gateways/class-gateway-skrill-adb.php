<?php
/**
 * Skrill Direct Bank Transfer
 *
 * This gateway is used for Skrill Direct Bank Transfer.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_ADB
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_ADB
 */
class Gateway_Skrill_ADB extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_adb';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'ADB';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'ADB';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'adb.png';

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Argentina, Brazil';


	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'ARG', 'BRA' );

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Direct Bank Transfer', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_ADB();
