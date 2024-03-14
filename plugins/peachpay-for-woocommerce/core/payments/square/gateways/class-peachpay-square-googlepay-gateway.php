<?php
/**
 * PeachPay Square GooglePay gateway.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

/**
 * .
 */
class PeachPay_Square_GooglePay_Gateway extends PeachPay_Square_Payment_Gateway {

	/**
	 * .
	 */
	public function __construct() {
		$this->id                    = 'peachpay_square_googlepay';
		$this->icons                 = array(
			'full'  => array(
				'white' => PeachPay::get_asset_url( 'img/marks/googlepay-full.svg' ),
			),
			'small' => array(
				'white' => PeachPay::get_asset_url( 'img/marks/googlepay-small-white.svg' ),
			),
		);
		$this->settings_priority     = 2;
		$this->payment_method_family = __( 'Digital wallet', 'peachpay-for-woocommerce' );

		// Customer facing title and description.
		$this->title       = 'Google Pay';
		$this->description = __( 'After placing the order you will be prompted to complete your payment.', 'peachpay-for-woocommerce' );

		parent::__construct();
	}
}
