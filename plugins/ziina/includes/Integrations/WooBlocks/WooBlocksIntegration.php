<?php
/**
 * Woocommerce checkout block integration
 *
 * @package ZiinaPayment\Integrations\WooBlocks
 */

namespace ZiinaPayment\Integrations\WooBlocks;

use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Automattic\WooCommerce\Blocks\Registry\Container;
use Exception;
use ZiinaPayment\Gateway;

defined( 'ABSPATH' ) || exit;

/**
 * Class WooBlocksIntegration
 *
 * @package ZiinaPayment\Integrations\WooBlocks
 */
class WooBlocksIntegration {
	/**
	 * WooBlocksIntegration constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_blocks_payment_method_type_registration', array( $this, 'register_payment_method_integrations' ) );
	}

	/**
	 * Register payment method integrations bundled with blocks.
	 *
	 * @param PaymentMethodRegistry $payment_method_registry Payment method registry instance.
	 */
	public function register_payment_method_integrations( PaymentMethodRegistry $payment_method_registry ) {
		$payment_method = ziina_payment()->gateway()->id;

		Package::container()->register(
			$payment_method,
			function ( Container $container ) use ( $payment_method ) {
				return new WooBlocksPaymentMethod( $payment_method );
			}
		);

		try {
			$payment_method_registry->register(
				Package::container()->get( $payment_method )
			);
		} catch ( Exception $e ) { //phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
		}
	}
}
