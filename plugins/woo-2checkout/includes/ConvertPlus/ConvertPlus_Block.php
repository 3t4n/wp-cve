<?php
/**
 * Checkout Blocks class
 *
 * @package    StorePress\TwoCheckoutPaymentGateway
 * @since      1.0.0
 */

namespace StorePress\TwoCheckoutPaymentGateway\ConvertPlus;

defined( 'ABSPATH' ) || die( 'Keep Silent' );

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Automattic\WooCommerce\StoreApi\Payments\PaymentContext;
use Automattic\WooCommerce\StoreApi\Payments\PaymentResult;

/**
 *  ConvertPlus_Block Class.
 */
class ConvertPlus_Block extends AbstractPaymentMethodType {

	/**
	 * Payment method name defined by payment methods extending this class.
	 *
	 * @var string
	 */
	protected $name = 'woo-2checkout';

	/**
	 * Selected Gateway
	 *
	 * @var ConvertPlus_Gateway|ConvertPlus_Gateway_Pro Convert plus gateway class.
	 */
	protected $gateway;

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$option         = sprintf( 'woocommerce_%s_settings', $this->get_name() );
		$this->settings = get_option( $option, array() );
		$gateways       = WC()->payment_gateways->payment_gateways();
		$this->gateway  = $gateways[ $this->get_name() ];
	}

	/**
	 * Get gateway instance.
	 */
	public function get_gateway() {
		return $this->gateway;
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active(): bool {
		return $this->get_gateway()->is_available();
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data(): array {
		$checkout_style = sanitize_text_field( $this->get_setting( 'checkout_type', 'standard' ) );

		return array(
			'is_demo'           => wc_string_to_bool( $this->get_setting( 'demo', 'yes' ) ),
			// 'icon_width'        => $this->get_setting( 'icon_width', '50' ),
			'icon_uri'          => $this->get_gateway()->get_icon_url(),
			'order_button_text' => $this->get_setting( 'order_button_text', esc_html__( 'Proceed to 2Checkout', 'woo-2checkout' ) ),
			'title'             => $this->get_setting( 'title', esc_html__( '2Checkout', 'woo-2checkout' ) ),
			'description'       => $this->get_setting( 'description', esc_html__( 'Pay via 2Checkout. Accept Credit Cards, PayPal and Debit Cards.', 'woo-2checkout' ) ),
			'checkout_style'    => empty( $checkout_style ) ? 'standard' : $checkout_style,
			'supports'          => $this->get_supported_features(),
		);
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features(): array {
		return $this->get_gateway()->supports;
	}

	/**
	 * Returns an array of script handles to enqueue for this payment method in
	 * the frontend context
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles(): array {
		$script_asset_path = woo_2checkout()->build_path() . '/convert-plus-block.asset.php';

		$script_asset = file_exists( $script_asset_path ) ? require $script_asset_path : array(
			'dependencies' => array(),
			'version'      => woo_2checkout()->version(),
		);

		$script_url = woo_2checkout()->build_url() . '/convert-plus-block.js';

		wp_register_script( 'woo-2checkout-convert-plus-payment-block', $script_url, $script_asset['dependencies'], $script_asset['version'], true );

		wp_set_script_translations( 'woo-2checkout-convert-plus-payment-block', 'woo-2checkout', woo_2checkout()->plugin_path() . '/languages' );

		return array( 'woo-2checkout-convert-plus-payment-block' );
	}
}
