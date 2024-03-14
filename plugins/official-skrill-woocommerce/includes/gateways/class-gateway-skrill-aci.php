<?php
/**
 * Skrill Cash / Invoice
 *
 * This gateway is used for Skrill Cash / Invoice.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_ACI
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_ACI
 */
class Gateway_Skrill_ACI extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_aci';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'ACI';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'ACI';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'aci.png';

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Argentina, Brazil, Chile, China, Columbia, Mexico, Peru, Uruguay';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'ARG', 'BRA', 'CHL', 'COL', 'MEX', 'PER', 'URY' );

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Cash/invoice', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_ACI();
