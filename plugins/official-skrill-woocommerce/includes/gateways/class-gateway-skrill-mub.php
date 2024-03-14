<?php
/**
 * Skrill Paysafecard
 *
 * This gateway is used for Skrill Paysafecard.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_BLK
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_BLK
 */
class Gateway_Skrill_MUB extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_mub';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'mub.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'MUB';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'MUB';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'PRT' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'Portugal';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Multibanco', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_MUB();
