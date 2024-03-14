<?php
/**
 * CryptoWoo Checkout Blocks Class File
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage Checkout
 * @author     CryptoWoo AS
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\StoreApi;

/**
 * CryptoWoo integration into WooCommerce Checkout Blocks
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage Checkout
 */
final class CW_Checkout_Blocks extends AbstractPaymentMethodType {
	/**
	 * CryptoWoo Payment Gateway object
	 *
	 * @var WC_CryptoWoo
	 */
	private WC_CryptoWoo $gateway;
	/**
	 * CryptoWoo Payment Gateway name
	 *
	 * @var string
	 */
	protected $name = 'cryptowoo';

	/**
	 * Initialize
	 *
	 * @return void
	 */
	public function initialize() {
		$this->settings = cw_get_options();
		$this->gateway  = new WC_CryptoWoo();
		$this->extend_store_api();
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() : bool {
		return $this->gateway->is_available();
	}

	/**
	 * Returns an array of script handles to enqueue for this payment method in
	 * the frontend context
	 *
	 * @return string[]
	 */
	public function get_payment_method_script_handles() : array {

		wp_register_script(
			'wc-cryptowoo-blocks-integration',
			CWOO_PLUGIN_PATH . 'assets/js/checkout-blocks.js',
			array(
				'wc-blocks-registry',
				'wc-settings',
				'wp-element',
				'wp-html-entities',
				'wp-i18n',
				'wc-blocks-data-store',
			),
			CWOO_VERSION,
			true
		);
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations(
				'wc-cryptowoo-blocks-integration',
				'cryptowoo',
				CWOO_PLUGIN_PATH . 'lang/'
			);

		}

		return array( 'wc-cryptowoo-blocks-integration' );
	}

	/**
	 * An array of key, value pairs of data made available to payment methods client side.
	 *
	 * @return array
	 */
	public function get_payment_method_data() : array {
		return array(
			'title'          => $this->gateway->title,
			'description'    => $this->gateway->description,
			'icons'          => $this->gateway->get_icon(),
			'payment_fields' => $this->gateway->get_payment_fields_html(),
		);
	}

	/**
	 * Add schema Store API to support posted data.
	 */
	public function extend_store_api() {
		try {
			$extend = StoreApi::container()->get( ExtendSchema::class );
		} catch ( Exception $e ) {
			CW_AdminMain::cryptowoo_log_data( 0, __FUNCTION__, $e->getMessage(), 'emergency' );

			return;
		}

		$extend->register_endpoint_data(
			array(
				'endpoint'        => CheckoutSchema::IDENTIFIER,
				'namespace'       => $this->get_name(),
				'schema_callback' => function() {
					return array(
						'cw_payment_currency' => array(
							'description' => __( 'Payment Currency.', 'cryptowoo' ),
							'type'        => 'text',
							'context'     => array(),
							'arg_options' => array(
								'validate_callback' => function( $value ) {
									return $this->validate_payment_currency( $value );
								},
							),
						),
						'refund_address'      => array(
							'description' => __( 'Refund Address.', 'cryptowoo' ),
							'type'        => 'text',
							'context'     => array(),
							'arg_options' => array(
								'validate_callback' => function( $value ) {
									return $this->validate_refund_address( $value );
								},
							),
						),
					);
				},
			)
		);

		// Update order based on extended data.
		add_action(
			'woocommerce_store_api_checkout_update_order_from_request',
			array( $this, 'update_order_from_checkout_request' ),
			10,
			2
		);
	}

	/**
	 * Validate payment currency submitted in the checkout.
	 *
	 * @param string|mixed $payment_currency Payment currency.
	 *
	 * @return true|WP_Error
	 */
	private function validate_payment_currency( $payment_currency ) {
		return $this->validate_form_value(
			$payment_currency,
			'You must select a payment currency.',
			__FUNCTION__
		);
	}

	/**
	 * Validate refund address submitted in the checkout.
	 *
	 * @param string|mixed $refund_address Refund address.
	 *
	 * @return true|WP_Error
	 */
	private function validate_refund_address( $refund_address ) {
		if ( ! empty( $refund_address ) || 'required' === cw_get_option( 'collect_refund_address' ) ) {
			return $this->validate_form_value(
				$refund_address,
				'You must enter a refund address.',
				__FUNCTION__
			);
		}

		return true;
	}

	/**
	 * Validate a value submitted in the checkout.
	 *
	 * @param string|mixed $value          Value from the checkout form.
	 * @param string       $error_if_empty Error message to return if the value is empty.
	 * @param string       $function       Function name of the callback that is validating the form value.
	 *
	 * @return true|WP_Error
	 */
	private function validate_form_value( $value, string $error_if_empty, string $function ) {
		if ( empty( $value ) ) {
			return new \WP_Error( 'api-error', $error_if_empty );
		}
		if ( ! is_string( $value ) ) {
			$type              = gettype( $value );
			$log_error_message = "value of type $type was posted to $function";
			CW_AdminMain::cryptowoo_log_data( 0, __FUNCTION__, $log_error_message, 'emergency' );

			return new \WP_Error( 'api-error', 'An error occurred while attempting to place the order.' );
		}
		return true;
	}

	/**
	 * Fires when the Checkout Block/Store API updates an order's from the API request data.
	 *
	 * Update order based on the data in the request.
	 * Used in conjunction with the ExtendSchema class to post custom data and then process it.
	 *
	 * @param WC_Order        $order   Order object.
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function update_order_from_checkout_request( WC_Order $order, WP_REST_Request $request ) {
		$extensions = $request->get_param( 'extensions' );
		$params     = $extensions['cryptowoo'] ?? array();

		if ( empty( $params ) ) {
			return;
		}

		if ( ! empty( $params['cw_payment_currency'] ) ) {
			$order->update_meta_data( 'payment_currency', sanitize_text_field( $params['cw_payment_currency'] ) );
		}

		if ( ! empty( $params['refund_address'] ) ) {
			$order->update_meta_data( 'refund_address', sanitize_text_field( $params['refund_address'] ) );
		}

		// Store in SESSION for usage later in POST for backwards compatibility to legacy checkout. TODO: refactor.
		session_start();
		$_SESSION['cryptowoo'] = $params;
	}
}
