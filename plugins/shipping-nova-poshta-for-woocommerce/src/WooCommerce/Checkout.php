<?php
/**
 * Checkout
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\WooCommerce;

use NovaPoshta\Main;
use NovaPoshta\Settings\Settings;
use NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta\NovaPoshta;

/**
 * Class Checkout
 *
 * @package NovaPoshta\WooCommerce
 */
class Checkout {


	/**
	 * Settings
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Checkout constructor.
	 *
	 * @param Settings $settings Settings.
	 */
	public function __construct( Settings $settings ) {

		$this->settings = $settings;
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'woocommerce_checkout_process', [ $this, 'validate' ] );
	}

	/**
	 * Validate fields
	 */
	public function validate() {

		if ( empty( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] ), Main::PLUGIN_SLUG . '-shipping' ) ) {
			return;
		}

		// Billing fields have a default validate by WooCommerce.
		if ( 'billing' === $this->settings->place_for_fields() ) {
			return;
		}

		$chosen_methods = wc_get_chosen_shipping_method_ids();

		if ( in_array( NovaPoshta::ID, $chosen_methods, true ) ) {
			$this->validate_shipping_method();
		}
	}

	/**
	 * Validate shipping method
	 */
	private function validate_shipping_method() {

		$required = in_array( NovaPoshta::ID, wc_get_chosen_shipping_method_ids(), true );
		$required = $required ? apply_filters( 'shipping_nova_poshta_for_woocommerce_is_required_field', true, NovaPoshta::ID ) : false;

		if ( $required && isset( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) && empty( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) ) { // phpcs:ignore
			wc_add_notice( __( 'Select delivery city', 'shipping-nova-poshta-for-woocommerce' ), 'error' );
		}

		if ( $required && isset( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) && empty( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) ) { // phpcs:ignore
			wc_add_notice( __( 'Choose branch', 'shipping-nova-poshta-for-woocommerce' ), 'error' );
		}
	}
}
