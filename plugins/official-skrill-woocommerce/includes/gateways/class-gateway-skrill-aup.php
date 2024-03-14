<?php
/**
 * Skrill Unionpay
 *
 * This gateway is used for Skrill Unionpay.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_Unionpay
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_Unionpay
 */
class Gateway_Skrill_AUP extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_aup';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'union-pay.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'AUP';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'AUP';

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'CHN' );

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = 'China';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Unionpay', 'wc-skrill' );
	}
}

$obj = new Gateway_Skrill_AUP();
