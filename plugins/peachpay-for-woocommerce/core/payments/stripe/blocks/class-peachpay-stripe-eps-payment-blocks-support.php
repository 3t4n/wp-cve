<?php
/**
 * PeachPay Stripe EPS WooCommerce Blocks support file.
 *
 * @package PeachPay
 */

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * .
 */
final class PeachPay_Stripe_Eps_Payment_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * The gateway instance.
	 *
	 * @var PeachPay_Stripe_Eps_Gateway
	 */
	private $gateway;

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'peachpay_stripe_eps';

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
		// Gateway Script
		PeachPay::register_webpack_script(
			$this->name . '-blocks',
			'wordpress/' . $this->name . '-blocks',
			array(),
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
			'connect_id'                 => PeachPay_Stripe_Integration::connect_id(),
			'currency_fallback_required' => $this->gateway->get_fallback_currency(),
			'description'                => $this->gateway->get_description(),
			'icon_url'                   => $this->gateway->get_icon_url(),
			'public_key'                 => PeachPay_Stripe_Integration::public_key(),
			'setup_intent_nonce'         => wp_create_nonce( 'peachpay-stripe-setup-intent' ),
			'setup_intent_url'           => WC_AJAX::get_endpoint( 'pp-stripe-setup-intent' ),
			'supports'                   => array_filter( $this->gateway->supports, array( $this->gateway, 'supports' ) ),
			'title'                      => $this->gateway->get_title(),
		);
	}
}
