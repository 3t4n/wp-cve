<?php

class Quadpay_WC_Mfpp
{
	const NAME = 'Merchant Fee for Payment Plan';

	const CODE = 'quadpay_mfpp';

	/**
	 * @var Quadpay_WC_Settings
	 */
	private $settings;

	/**
	 * Quadpay_WC_Mfpp constructor
	 */
	public function __construct() {
		$this->settings = Quadpay_WC_Settings::instance();
	}

	/**
	 * Init hooks
	 */
	public function init() {
		if ( ! $this->settings->is_enabled() || ! $this->is_mfpp_enabled() ) {
			return;
		}

		add_action( 'woocommerce_after_calculate_totals', array( $this, 'after_calculate_totals' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	/**
	 * @return bool
	 */
	public function is_mfpp_enabled() {
		return
			$this->settings->get_option_bool( 'mfpp' ) &&
			$this->settings->get_option( 'merchant_id' );
	}

	/**
	 * @param WC_Cart $cart
	 */
	public function after_calculate_totals( $cart ) {

		if (is_admin()) {
			return;
		}

		if( WC()->session->get( 'chosen_payment_method' ) !== 'quadpay' ) {
			return;
		}

		$country  = WC()->customer->get_shipping_country();
		$state    = WC()->customer->get_shipping_state();
		$currency = get_woocommerce_currency();

		$total = (float)$cart->get_total( 'edit' );
		$mfpp = $this->cache_request_mfpp( $country, $state, $currency, $total );

		if ($mfpp < 0.01) {
			return;
		}

		$cart->fees_api()->add_fee(array(
			'id' => self::CODE,
			'name'      => __( self::NAME, 'woo_quadpay' ),
			'taxable'   => false,
			'tax_class' => '',
			'amount' => $mfpp,
			'total' => $mfpp
		));

		$cart->set_fee_total( $cart->get_fee_total() + $mfpp);
		$cart->set_total( $total + $mfpp );
	}

	/**
	 * Cached MFPP API request
	 *
	 * @param $country_code
	 * @param $region_code
	 * @param $currency
	 * @param $amount
	 * @return float|null
	 */
	public function cache_request_mfpp($country_code, $region_code, $currency, $amount) {
		$cache_key = 'quadpay_mfpp_' . hash( 'md5', json_encode(func_get_args()) );
		$response  = get_transient( $cache_key );

		if ( false === $response ) {
			$response = $this->request_mfpp( $country_code, $region_code, $currency, $amount );
			if ($response !== null) {
				set_transient( $cache_key, $response, 10*60 );
			}
		} else {
			$response = (float) $response;
		}

		return $response;
	}

	/**
	 * MFPP API request
	 *
	 * @param string $country_code
	 * @param string $region_code
	 * @param string $currency
	 * @param float $amount
	 * @return float|null
	 */
	public function request_mfpp($country_code, $region_code, $currency, $amount) {

		$url = $this->settings->get_api_url_v2('/orders/calculate-merchant-fees');

		$request = array(
			'merchantId' => $this->settings->get_option('merchant_id'),
			'customerCountry' => $country_code,
			'customerState' => $region_code,
			'currency' => $currency,
			'amount' => round($amount, 2)
		);

		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'QP-Territory' => $this->settings->get_option('territory', 'US')
			),
			'body' => json_encode( $request ),
		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return null;
		}
		$result = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! isset($result['merchantFeeForPaymentPlan'] ) ) {
			return null;
		}

		return $result['merchantFeeForPaymentPlan'];
	}

	/**
	 * Enqueue script to reload total on payment change
	 */
	function wp_enqueue_scripts() {

		if ( is_checkout() && $this->settings->get_option_bool( 'mfpp' ) ) {
			wp_enqueue_script(
				'quadpay_checkout',
				QUADPAY_WC_PLUGIN_URL . 'assets/js/checkout.js',
				array( 'jquery', 'wc-checkout' ),
				QUADPAY_WC_VERSION,
				true
			);
		}

	}

}
