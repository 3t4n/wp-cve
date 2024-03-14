<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Payments Blocks integration
 */
final class WC_Payever_Payments_Blocks extends AbstractPaymentMethodType {


	/**
	 * The gateway instance.
	 *
	 * @var WC_Payever_Gateway[]
	 */
	private $gateways = array();

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'payever_gateway';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$gateways = WC()->payment_gateways->payment_gateways();

		$data = array();
		foreach ( $gateways as $gateway ) {
			if ( ! $gateway instanceof WC_Payever_Gateway ) {
				continue;
			}
			if ( WC_Payever_Helper::instance()->is_payever_method( $gateway->id ) ) {
				$data[] = $gateway;
			}
		}

		$this->gateways = $data;
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return true;
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$path = trailingslashit( WP_PLUGIN_DIR ) . 'payever-woocommerce-gateway';
		$url  = plugin_dir_url( $path . '/assets/js/frontend/.' ) . 'checkout.js';

		wp_register_script(
			'wc-payever-payment-blocks',
			$url,
			array(
				'wc-blocks-registry',
				'wc-settings',
				'wp-element',
				'wp-html-entities',
				'wp-i18n',
			),
			null,
			true
		);
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations(
				'wc-payever-payment-blocks',
				'payever-woocommerce-gateway',
				$path . 'languages/'
			);
		}

		return array( 'wc-payever-payment-blocks' );
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		$data = array();
		foreach ( $this->gateways as $gateway ) {
			$data[] = array(
				'id'          => $gateway->id,
				'title'       => $gateway->get_title(),
				'description' => $gateway->get_description(),
				'icon'        => $gateway->get_icon( true ),
				'supports'    => array_filter( $gateway->supports, array( $gateway, 'supports' ) ),
			);
		}

		return $data;
	}
}
