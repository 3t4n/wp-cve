<?php
/**
 * Skrill Maestro
 *
 * This gateway is used for Skrill Maestro.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_MAE
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_MAE
 */
class Gateway_Skrill_MAE extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_mae';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'mae.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'MAE';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'MAE';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'GBR', 'ESP', 'IRL', 'AUT' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'United Kingdom, Spain, Ireland and Austria';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Maestro', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_MAE();
