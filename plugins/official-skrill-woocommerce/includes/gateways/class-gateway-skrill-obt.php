<?php
/**
 * Skrill Rapid Transfer
 *
 * This gateway is used for Skrill Rapid Transfer.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_OBT
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_OBT
 */
class Gateway_Skrill_OBT extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_obt';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'obt.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'OBT';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'OBT';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'AUT', 'BEL', 'DNK', 'EST', 'FIN', 'FRA', 'DEU', 'IRL', 'ITA', 'LVA', 'LTU', 'NLD', 'POL', 'PRT', 'ROU', 'ESP', 'GBR' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Austria, Belgium, Denmark, Estonia, Finland, France, Germany, Ireland, Italy, Latvia, Lithuania, Netherlands, Poland, Portugal, Romania, Spain, United Kingdom';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Rapid Transfer', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_OBT();
