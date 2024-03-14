<?php
/**
 * Gateway wrapper for woocommerce checkout block integration
 *
 * @package ZiinaPayment\Integrations\WooBlocks
 */

namespace ZiinaPayment\Integrations\WooBlocks;

defined( 'ABSPATH' ) || exit();

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Exception;
use WC_Payment_Gateway;
use WC_Payment_Tokens;

/**
 * ZiinaPayment payment methods integration
 */
final class WooBlocksPaymentMethod extends AbstractPaymentMethodType {
	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Wrapped gateway
	 *
	 * @var WC_Payment_Gateway
	 */
	protected $gateway;

	/**
	 * Array gateways supporting card payment
	 *
	 * @var string[]
	 */
	protected $support_cards = array();

	/**
	 * Constructor
	 *
	 * @param string $name Payment method name/id/slug.
	 *
	 * @throws Exception If gateway not exist.
	 */
	public function __construct( string $name ) {
		$this->name    = $name;
		$this->gateway = ziina_payment()->gateway();

		if ( is_null( $this->gateway ) ) {
			throw new Exception( "Gateway '$this->name' not found" );
		}
	}

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( "woocommerce_{$this->name}_settings", array() );
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active(): bool {
		return filter_var( $this->get_setting( 'enabled', false ), FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles(): array {
		$handle = "wc-ziina-blocks-payment-method-$this->name";

		/**
		 * Filters the list of script dependencies.
		 *
		 * @param array  $dependencies The list of script dependencies.
		 * @param string $handle       The script's handle.
		 *
		 * @return array
		 */
		$script_dependencies = apply_filters( 'woocommerce_blocks_register_script_dependencies', array(), $handle );

		wp_register_script(
			$handle,
			ziina_payment()->assets_url . 'js/woo-blocks.js',
			$script_dependencies,
			ziina_payment()->version,
			true
		);

		return array( $handle );
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features(): array {
		$gateway = ziina_payment()->gateway();

		if ( ! empty( $gateway ) ) {
			$features = $gateway->supports;
		} else {
			$features = array( 'products' );
		}

		if ( in_array( $this->name, $this->support_cards, true ) ) {
			$features[] = 'cards';
		}

		return $features;
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data(): array {
		$data = array(
			'title'       => $this->get_setting( 'title' ),
			'description' => $this->get_setting( 'description' ),
			'supports'    => $this->get_supported_features(),
		);

		$data = apply_filters( 'ziina_blocks_payment_method_data', $data, $this );

		return apply_filters( 'ziina_blocks_payment_method_data_' . $this->name, $data, $this );
	}
}
