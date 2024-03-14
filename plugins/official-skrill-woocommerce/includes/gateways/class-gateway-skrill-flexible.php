<?php
/**
 * Skrill Flexible
 *
 * This gateway is used for Flexible.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_Flexible
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_Flexible
 */
class Gateway_Skrill_Flexible extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_flexible';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'flexible.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'FLEXIBLE';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'FLEXIBLE';

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		if ( is_admin() ) {
			return __( 'All Cards and Alternative Payment Methods', 'wc-skrill' );
		}
		return __( 'Pay By Skrill', 'wc-skrill' );
	}

	/**
	 * Check if payment method show separately at backend configuration
	 * Force Return true, because payment method flexible does not have show separately configuration
	 *
	 * @return bool
	 */
	public function is_show_separately() {
		return true;
	}

}

$obj = new Gateway_Skrill_Flexible();
