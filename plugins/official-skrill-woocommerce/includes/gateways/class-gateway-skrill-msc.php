<?php
/**
 * Skrill MasterCard
 *
 * This gateway is used for Skrill MasterCard.
 * Copyright (c) Skrill
 *
 * @class   Gateway_Skrill_MSC
 * @extends Skrill_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /includes/gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Gateway_Skrill_MSC
 */
class Gateway_Skrill_MSC extends Skrill_Payment_Gateway {


	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'skrill_msc';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = 'msc.png';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = 'MSC';

	/**
	 * Payment brand
	 *
	 * @var string
	 */
	public $payment_brand = 'MSC';

	/**
	 * True when payment method is one of the credit card list
	 *
	 * @var array
	 */
	protected $is_payment_method_in_credit_card_list = true;

	/**
	 * Constructor - Registers as subscribtion payment method.
	 */
	public function __construct() {
		parent::__construct();

		// Check if subscriptions are enabled and add support for them.
		$this->maybe_init_subscriptions();
	}

	/**
	 * Get payment title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'MasterCard', 'wc-skrill' );
	}

	/**
	 * Validate if payment methods is available.
	 * Override From class WC_Payment_Gateway
	 *
	 * @return bool
	 */
	public function is_available() {
		$is_available = parent::is_available();
		$acc_settings = get_option( 'woocommerce_skrill_acc_settings' );

		if ( $is_available ) {
			if ( 'yes' === $acc_settings['enabled'] && 'no' === $acc_settings['show_separately'] ) {
				return false;
			} elseif ( $this->is_enabled() && $this->is_country_allowed() || 'yes' === $acc_settings['show_separately'] ) {
				return true;
			}
		}

		return false;
	}
}

$obj = new Gateway_Skrill_MSC();
