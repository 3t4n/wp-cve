<?php
/**
 * PeachPay Square GooglePay gateway block.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * .
 */
final class PeachPay_Square_GooglePay_Gateway_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * The gateway instance.
	 *
	 * @var PeachPay_Square_GooglePay_Gateway
	 */
	private $gateway;

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'peachpay_square_googlepay';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$gateways       = WC()->payment_gateways->payment_gateways();
		$this->gateway  = $gateways[ $this->name ];
		$this->settings = get_option( 'peachpay_' . $this->name . '_settings', array() );
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return $this->gateway->is_available( true );
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		// Register Square Web SDK script.
		PeachPay::register_external_script(
			'peachpay-square-js',
			pp_square_script_src()
		);

		// Gateway Script
		PeachPay::register_webpack_script(
			$this->name . '-blocks',
			'wordpress/' . $this->name . '-blocks',
			array( 'peachpay-square-js' ),
			true
		);

		return array( $this->name . '-blocks' );
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return array(
			'square'            => array(
				'application_id' => peachpay_square_application_id(),
				'location_id'    => peachpay_square_location_id(),
				'country_code'   => peachpay_square_country(),
			),
			'icon'              => $this->gateway->get_icon_url(),
			'title'             => $this->gateway->get_title(),
			'description'       => $this->gateway->get_description(),
			'fallback_currency' => $this->gateway->get_fallback_currency(),
			'supports'          => array_filter( $this->gateway->supports, array( $this->gateway, 'supports' ) ),
		);
	}
}
