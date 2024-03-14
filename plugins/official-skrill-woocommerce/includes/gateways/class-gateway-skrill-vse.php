<?php
/**
 * Skrill Visa Electron
 *
 * This gateway is used for Skrill Visa Electron.
 * Copyright (c) Skrill
 *
 * @class       Gateway_Skrill_VSE
 * @extends     Skrill_Payment_Gateway
 * @package     Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_VSE
 */
class Gateway_Skrill_VSE extends Skrill_Payment_Gateway {

	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_vse';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'vse.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'VSE';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'VSE';

	/**
	 * Excepted countries
	 *
	 * @var array
	 */
	protected $excepted_countries = array( 'USA' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'All Countries (excluding United States Of America)';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Visa Electron', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_VSE();
