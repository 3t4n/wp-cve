<?php
/**
 * Skrill Sofort
 *
 * This gateway is used for Skrill Sofort
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_SFT
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_SFT
 */
class Gateway_Skrill_SFT extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_sft';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'sft.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'SFT';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'SFT';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'DEU', 'AUT', 'BEL', 'NLD', 'FRA', 'HUN', 'SVK', 'CZE', 'GBR' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = ' Germany, Austria, Belgium, Netherlands, France, Hungary, Slovakia, Czech Republic, United Kingdom';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Sofort', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_SFT();
