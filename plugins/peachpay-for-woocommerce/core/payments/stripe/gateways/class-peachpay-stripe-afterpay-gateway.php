<?php
/**
 * PeachPay Stripe Afterpay / Clearpay gateway.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

/**
 * .
 */
class PeachPay_Stripe_Afterpay_Gateway extends PeachPay_Stripe_Payment_Gateway {
	const COUNTRIES_CURRENCIES_DICT = array(
		'AU' => 'AUD',
		'CA' => 'CAD',
		'NZ' => 'NZD',
		'GB' => 'GBP',
		'US' => 'USD',
	);

	const COUNTRIES_MAX_AMOUNT_DICT = array(
		'AU' => 2000,
		'CA' => 2000,
		'NZ' => 2000,
		'GB' => 1200,
		'US' => 4000,
	);

	/**
	 * .
	 */
	public function __construct() {
		$connect_country = PeachPay_Stripe_Integration::connect_country();

		$this->id    = 'peachpay_stripe_afterpay';
		$this->title = 'GB' === $connect_country ? __( 'Clearpay', 'peachpay-for-woocommerce' ) : __( 'Afterpay', 'peachpay-for-woocommerce' );

		$this->stripe_payment_method_type            = 'afterpay_clearpay';
		$this->stripe_payment_method_capability_type = 'afterpay_clearpay';
		$this->settings_priority                     = 5;

		$this->description = __( 'After placing the order you will be redirected to complete your payment.', 'peachpay-for-woocommerce' );

		$this->currencies = array();
		$this->countries  = array();
		if ( isset( self::COUNTRIES_CURRENCIES_DICT[ $connect_country ] ) ) {
			$this->countries  = array( $connect_country );
			$this->currencies = array( self::COUNTRIES_CURRENCIES_DICT[ $connect_country ] );
		}

		$this->payment_method_family = __( 'Buy now, Pay later', 'peachpay-for-woocommerce' );
		$this->min_amount            = 1;
		$this->max_amount            = isset( self::COUNTRIES_MAX_AMOUNT_DICT[ $connect_country ] ) ? self::COUNTRIES_MAX_AMOUNT_DICT[ $connect_country ] : 0;

		$this->form_fields = self::capture_method_setting( $this->form_fields );

		parent::__construct();
	}

	/**
	 * Setup future settings for payment intent.
	 */
	protected function setup_future_usage() {
		return null;
	}

	/**
	 * AfterPay does not support virtual product purchases.
	 *
	 * @param bool $skip_cart_check If true, the cart availability check will be skipped.
	 */
	public function is_available( $skip_cart_check = false ) {
		$is_available = parent::is_available( $skip_cart_check );

		// Availability for cart/checkout page
		if ( ! $skip_cart_check && WC()->cart && ! WC()->cart->needs_shipping() ) {
				$is_available = false;
		} elseif ( $is_available && is_wc_endpoint_url( 'order-pay' ) ) {
			$order_id = absint( get_query_var( 'order-pay' ) );
			$order    = wc_get_order( $order_id );

			if ( ! $order instanceof WC_Order || ! $order->has_shipping_address() ) {
				$is_available = false;
			}
		}

		return $is_available;
	}

	/**
	 * Override get_icon_url method to return afterpay/clearpay icon url depending on customer billing.
	 * If no customer, will default to store base currency
	 *
	 * @param string $size       of the icon.
	 * @param string $background of the icon.
	 */
	public function get_icon_url( $size = 'full', $background = 'color' ) {
		$connect_country      = PeachPay_Stripe_Integration::connect_country();
		$afterpay_or_clearpay = 'GB' === $connect_country ? 'clearpay' : 'afterpay';

		$this->icons = array(
			'full'  => array(
				'color' => PeachPay::get_asset_url( "img/marks/stripe/$afterpay_or_clearpay-full-color.svg" ),
			),
			'small' => array(
				'color' => PeachPay::get_asset_url( "img/marks/stripe/$afterpay_or_clearpay-small-color.svg" ),
				'white' => PeachPay::get_asset_url( "img/marks/stripe/$afterpay_or_clearpay-small-white.svg" ),
			),
		);

		return parent::get_icon_url( $size, $background );
	}
}
