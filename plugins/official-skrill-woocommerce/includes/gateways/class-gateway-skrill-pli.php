<?php
/**
 * Skrill POLi
 *
 * This gateway is used for Skrill POLi.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_PLI
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_PLI
 */
class Gateway_Skrill_PLI extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_pli';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'pli.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'PLI';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'PLI';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'AUS' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Australia';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'POLi', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_PLI();
