<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Razorpay Payment Links Blocks integration
 *
 * @since 1.2.0
 */
final class RZP_WC_Payment_Gateway_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'wc-razorpay';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( "woocommerce_{$this->name}_settings", [] );
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'];
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$script_path       = 'includes/blocks/assets/blocks.js';
		$script_asset_path = RZPWC_PATH . 'includes/blocks/assets/blocks.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require( $script_asset_path )
			: array(
				'dependencies' => array(),
				'version'      => RZPWC_VERSION
			);
		$script_url        = RZPWC_URL . $script_path;

		wp_register_script(
			'rzpwc-payment-blocks',
			$script_url,
			$script_asset[ 'dependencies' ],
			$script_asset[ 'version' ],
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'rzpwc-payment-blocks', 'rzp-woocommerce', RZPWC_PATH . 'languages/' );
		}

		return [ 'rzpwc-payment-blocks' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return [
			'title'       => $this->get_setting( 'title' ),
			'description' => $this->get_setting( 'description' ),
			'supports'    => $this->get_supported_features(),
			'button_text' => apply_filters( 'upiwc_order_button_text', __( 'Proceed to Payment', 'rzp-woocommerce' ) ),
		];
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features() {
		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		
		if ( isset( $gateways[ $this->name ] ) ) {
			$gateway = $gateways[ $this->name ];

			return array_filter( $gateway->supports, [ $gateway, 'supports' ] );
		}

		return [];
	}
}
