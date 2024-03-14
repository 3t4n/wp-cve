<?php

namespace WcGetnet\WooCommerce;

use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;
use WcGetnet\WooCommerce\GateWays\WcGetnet_CreditCard;
use WcGetnet\WooCommerce\GateWays\WcGetnet_Billet;
use WcGetnet\WooCommerce\GateWays\WcGetnet_Pix;

/**
 * Register Gateway.
 */
class WcGetnetProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		// Nothing to register.
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		add_filter( 'woocommerce_payment_gateways', [$this, 'wcgetnet_gateway_getnet'] );
		add_action( 'before_woocommerce_init', [$this, 'wcgetnet_woocommerce_hpos_compatibility'] );
	}

	/**
	 * Register Gateway Getnet.
	 *
	 * @return void
	 */
	public function wcgetnet_gateway_getnet( $gateways ) {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}

		$gateways[] = WcGetnet_CreditCard::class;
		$gateways[] = WcGetnet_Billet::class;
		$gateways[] = WcGetnet_Pix::class;

		return $gateways;
	}

	/**
	 * Declare Plugin HPOS Compatibility.
	 */
	public function wcgetnet_woocommerce_hpos_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'wc-checkout-getnet/wc-checkout-getnet.php', true );
		}
	}
}
