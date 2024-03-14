<?php
/**
 * PeachPay Stripe SEPA Direct Debit WooCommerce Blocks Support file.
 *
 * @package PeachPay
 */

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * PeachPay Stripe Direct Debit WooCommerc Blocks Support class.
 */
final class PeachPay_Stripe_SepaDebit_Gateway_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * The gateway instance.
	 *
	 * @var PeachPay_Stripe_SepaDebit_Gateway
	 */
	private $gateway;

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'peachpay_stripe_sepadebit';

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
		// translators: %s The merchant's store name.
		$conditions = __(
			'By providing your IBAN and confirming this payment, you are authorizing %s and Stripe,
			our payment service provider, to send instructions to your bank to debit your account in accordance with those instructions.
			You are entitled to a refund from your bank under the terms and conditions of your agreement with your bank.
			A refund must be claimed within 8 weeks starting from the date on which your account was debited.',
			'peachpay-for-woocommerce'
		);

		return array(
			'title'                      => $this->gateway->get_title(),
			'icon_url'                   => $this->gateway->get_icon_url(),
			'conditions'                 => str_replace( '%s', esc_html( get_bloginfo( 'name' ) ), esc_html( $conditions ) ),
			'supports'                   => array_filter( $this->gateway->supports, array( $this->gateway, 'supports' ) ),
			'connect_id'                 => PeachPay_Stripe_Integration::connect_id(),
			'public_key'                 => PeachPay_Stripe_Integration::public_key(),
			'setup_intent_url'           => WC_AJAX::get_endpoint( 'pp-stripe-setup-intent' ),
			'setup_intent_nonce'         => wp_create_nonce( 'peachpay-stripe-setup-intent' ),
			'currency_fallback_required' => $this->gateway->get_fallback_currency(),
		);
	}
}
