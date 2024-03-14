<?php
/**
 * Courier Shipping Method
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Education\WooCommerce\Shipping\Methods\Courier;

use WC_Shipping_Method;

if ( ! class_exists( 'WC_Shipping_Method' ) ) {
	return;
}

/**
 * Class Courier
 */
class Courier extends WC_Shipping_Method {

	/**
	 * Method ID.
	 */
	const ID = 'shipping_nova_poshta_for_woocommerce_courier';

	/**
	 * Unique ID for the shipping method - must be set.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Shipping method title for the frontend.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Method title.
	 *
	 * @var string
	 */
	public $method_title;

	/**
	 * Method description.
	 *
	 * @var string
	 */
	public $method_description;

	/**
	 * Features this method supports. Possible features used by core:
	 * - shipping-zones Shipping zone functionality + instances
	 * - instance-settings Instance settings screens.
	 * - settings Non-instance settings screens. Enabled by default for BW compatibility with methods before instances existed.
	 * - instance-settings-modal Allows the instance settings to be loaded within a modal in the zones UI.
	 *
	 * @var array
	 */
	public $supports;

	/**
	 * Constructor for your shipping class
	 *
	 * @param int $instance_id Instance ID.
	 */
	public function __construct( $instance_id = 0 ) {

		$this->id                 = self::ID;
		$this->method_title       = esc_html__( 'Nova Poshta Courier Delivery (for Pro)', 'shipping-nova-poshta-for-woocommerce' );
		$this->method_description = esc_html__( 'Allow your customers to deliver your products right to their door. Your customer needs to choose a city and fill in the address. Also, you can gift customers free delivery if their cart more than any exact price.', 'shipping-nova-poshta-for-woocommerce' );
		$this->label              = esc_html__( 'Fill the delivery address', 'shipping-nova-poshta-for-woocommerce' );
		$this->enabled            = 'no';
		$this->supports           = [
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		];
		parent::__construct( $instance_id );
		$this->init();
	}

	/**
	 * Init your settings
	 */
	public function init() {

		$this->init_form_fields();
		$this->init_settings();

		$this->title = esc_html__( 'Nova Poshta Courier Delivery', 'shipping-nova-poshta-for-woocommerce' ) . '<span class="shipping-nova-poshta-for-woocommerce-pro"></span>';

		add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	/**
	 * Init form fields
	 */
	public function init_form_fields() {

		$this->instance_form_fields = [
			'title'                => [
				'title'    => esc_html__( 'Method header', 'shipping-nova-poshta-for-woocommerce' ),
				'type'     => 'text',
				'default'  => esc_html__( 'Nova Poshta delivery', 'shipping-nova-poshta-for-woocommerce' ),
				'disabled' => true,
			],
			'enable_free_shipping' => [
				'title'    => esc_html__( 'Enable free shipping', 'shipping-nova-poshta-for-woocommerce' ),
				'type'     => 'checkbox',
				'default'  => 'no',
				'disabled' => true,
			],
			'free_shipping_from'   => [
				'title'       => esc_html__( 'Minimum cart total for free shipping', 'shipping-nova-poshta-for-woocommerce' ),
				'description' => esc_html__( 'If the cart totals more than this number, then shipping is free.', 'shipping-nova-poshta-for-woocommerce' ),
				'desc_tip'    => true,
				'type'        => 'number',
				'default'     => 1000,
				'disabled'    => true,
			],
		];
	}

	/**
	 * Disable shipping method.
	 *
	 * @return false
	 */
	public function is_enabled(): bool {

		return false;
	}
}
