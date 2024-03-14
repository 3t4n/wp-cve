<?php
/**
 * PeachPay Square ACHBank Gateway
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

/**
 * .
 */
class PeachPay_Square_ACH_Gateway extends PeachPay_Square_Payment_Gateway {

	/**
	 * .
	 */
	public function __construct() {
		$this->id                    = 'peachpay_square_ach';
		$this->icons                 = array(
			'full'  => array(
				'clear' => PeachPay::get_asset_url( 'img/marks/us_banks-full.svg' ),
			),
			'small' => array(
				'clear' => PeachPay::get_asset_url( 'img/marks/us_banks-small.svg' ),
			),
		);
		$this->settings_priority     = 3;
		$this->payment_method_family = __( 'Bank debit', 'peachpay-for-woocommerce' );

		// Customer facing title and description.
		$this->title       = 'US Bank Account';
		$this->description = __( 'After placing the order you will be prompted to complete your payment.', 'peachpay-for-woocommerce' );
		$this->countries   = array( 'US' );
		$this->currencies  = array( 'USD' );

		parent::__construct();
	}
}
