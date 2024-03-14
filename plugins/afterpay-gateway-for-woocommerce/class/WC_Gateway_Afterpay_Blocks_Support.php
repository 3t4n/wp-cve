<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Afterpay payment method integration
 *
 * @since 3.4.0
 */
final class WC_Gateway_Afterpay_Blocks_Support extends AbstractPaymentMethodType {
	/**
	 * Name of the payment method.
	 *
	 * @var string
	 */
	protected $name = 'afterpay';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_afterpay_settings', [] );
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$asset_path   = WC_GATEWAY_AFTERPAY_PATH . '/build/afterpay-blocks/index.asset.php';
		$version      = Afterpay_Plugin::$version;
		$dependencies = [];
		if ( file_exists( $asset_path ) ) {
			$asset        = require $asset_path;
			$version      = is_array( $asset ) && isset( $asset['version'] )
				? $asset['version']
				: $version;
			$dependencies = is_array( $asset ) && isset( $asset['dependencies'] )
				? $asset['dependencies']
				: $dependencies;
		}
		wp_register_script(
			'wc-afterpay-blocks-integration',
			WC_GATEWAY_AFTERPAY_URL . '/build/afterpay-blocks/index.js',
			$dependencies,
			$version,
			true
		);
		return [ 'wc-afterpay-blocks-integration', 'afterpay_express' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		$instance = WC_Gateway_Afterpay::getInstance();
		wp_enqueue_style( 'afterpay_css' );
		return [
			'mpid' => $instance->get_mpid(),
			'currency' => get_woocommerce_currency(),
			'min' => $instance->getOrderLimitMin(),
			'max' => $instance->getOrderLimitMax(),
			'logo_url' => $instance->get_static_url() . 'integration/checkout/logo-afterpay-colour-120x25.png',
			'testmode' => $this->get_setting('testmode'),
			'locale' => $instance->get_js_locale(),
			'supports' => $this->get_supported_features(),
			'ec_enabled' => $instance->express_is_enabled(),
			'ec_button' => $instance->get_express_checkout_button_for_block(),
			'frontend_is_ready' => $instance->frontend_is_ready(),
			'cart_placement_attributes' => $instance->get_cart_placement_attributes('WooCommerce/Blocks'),
		];
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features() {
		$features = [];
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		if (array_key_exists('afterpay', $payment_gateways)) {
			$features = $payment_gateways['afterpay']->supports;
		}
		return $features;
	}
}
