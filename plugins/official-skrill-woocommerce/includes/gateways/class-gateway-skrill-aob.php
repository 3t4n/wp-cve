<?php
/**
 * Skrill Manual Bank Transfer
 *
 * This gateway is used for Skrill Manual Bank Transfer.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_AOB
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_AOB
 */
class Gateway_Skrill_AOB extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_aob';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'AOB';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'AOB';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'aob.png';

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Brazil, Chile, China, Columbia';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'BRA', 'CHL', 'COL' );

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Manual Bank Transfer', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_AOB();
