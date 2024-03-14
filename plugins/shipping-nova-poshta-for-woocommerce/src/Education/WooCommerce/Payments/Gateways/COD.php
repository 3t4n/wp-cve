<?php
/**
 * Nova Poshta COD Gateway
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Education\WooCommerce\Payments\Gateways;

use WC_Gateway_COD;
use NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta\NovaPoshta;

if ( ! class_exists( 'WC_Gateway_COD' ) ) {
	return;
}

/**
 * Class COD
 */
class COD extends WC_Gateway_COD {

	/**
	 * Gateway ID.
	 */
	const ID = 'shipping_nova_poshta_for_woocommerce_cod';

	/**
	 * Instructions on thank you page and email.
	 *
	 * @var string
	 */
	public $instructions;

	/**
	 * Description of method.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Enable for methods.
	 *
	 * @var array
	 */
	public $enable_for_methods;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {

		parent::__construct();
		$this->enable_for_methods = [ NovaPoshta::ID, NovaPoshta::ID . '_courier' ];
	}

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties() {

		$this->plugin_id    = 'shipping_nova_poshta_for_woocommerce';
		$this->id           = self::ID;
		$this->method_title = esc_html__( 'Nova Poshta cash on delivery', 'shipping-nova-poshta-for-woocommerce' );
		if ( ! $this->is_settings_page() ) {
			$this->method_title .= '<span class="shipping-nova-poshta-for-woocommerce-pro"></span>';
		}
		$this->method_description = esc_html__( 'Have your customers pay with cash (or by other means) upon delivery.', 'shipping-nova-poshta-for-woocommerce' );
		$this->has_fields         = false;
	}

	/**
	 * Is the settings page for the payment method.
	 *
	 * @return bool
	 */
	private function is_settings_page(): bool {

		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['section'] ) || empty( $_GET['tab'] ) ) {
			return false;
		}

		return 'shipping_nova_poshta_for_woocommerce_cod' === sanitize_key( $_GET['section'] ) && 'checkout' === sanitize_key( $_GET['tab'] );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {

		$this->form_fields = [
			'enabled'      => [
				'title'       => esc_html__( 'Enable/Disable', 'shipping-nova-poshta-for-woocommerce' ),
				'label'       => esc_html__( 'Enable cash on delivery', 'shipping-nova-poshta-for-woocommerce' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
				'disabled'    => true,
			],
			'title'        => [
				'title'       => esc_html__( 'Title', 'shipping-nova-poshta-for-woocommerce' ),
				'type'        => 'text',
				'description' => esc_html__( 'Payment method description that the customer will see on your checkout.', 'shipping-nova-poshta-for-woocommerce' ),
				'default'     => esc_html__( 'Cash on delivery', 'shipping-nova-poshta-for-woocommerce' ),
				'desc_tip'    => true,
				'disabled'    => true,
			],
			'prepayment'   => [
				'title'       => esc_html__( 'Prepayment', 'shipping-nova-poshta-for-woocommerce' ),
				'type'        => 'text',
				'description' => esc_html__( 'Formula cost calculation. The numbers are indicated in current currency. You can use the [qty] shortcode to indicate the number of products. Leave a empty if you work without prepayment.', 'shipping-nova-poshta-for-woocommerce' ),
				'default'     => '100 + ( [qty] - 1 ) * 20',
				'desc_tip'    => true,
				'disabled'    => true,
			],
			'description'  => [
				'title'       => esc_html__( 'Description', 'shipping-nova-poshta-for-woocommerce' ),
				'type'        => 'textarea',
				'description' => esc_html__( 'Description of the payment method that the customer will see on the checkout page. You can use the shortcode [prepayment] to write the price of prepayment and [rest] shortcode to write the rest of the amount.', 'shipping-nova-poshta-for-woocommerce' ),
				'default'     => esc_html__( 'Pay [prepayment] prepayment and [rest] with cash upon delivery.', 'shipping-nova-poshta-for-woocommerce' ),
				'desc_tip'    => true,
				'disabled'    => true,
			],
			'instructions' => [
				'title'       => esc_html__( 'Instructions', 'shipping-nova-poshta-for-woocommerce' ),
				'type'        => 'textarea',
				'description' => esc_html__( 'Instructions that will be added to the thank you page. You can use the shortcode [prepayment] to write the price of prepayment and [rest] shortcode to write the rest of the amount.', 'shipping-nova-poshta-for-woocommerce' ),
				'default'     => esc_html__( 'Pay [prepayment] prepayment and [rest] with cash upon delivery.', 'shipping-nova-poshta-for-woocommerce' ),
				'desc_tip'    => true,
				'disabled'    => true,
			],
		];
	}

	/**
	 * Check If The Gateway Is Available For Use.
	 *
	 * @return bool
	 */
	public function is_available() {

		return false;
	}
}
