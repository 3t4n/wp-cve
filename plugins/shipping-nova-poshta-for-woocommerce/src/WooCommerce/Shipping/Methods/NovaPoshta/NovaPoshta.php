<?php
/**
 * Nova Poshta Shipping Method
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta;

use WC_Eval_Math;
use WC_Shipping_Method;

if ( ! class_exists( 'WC_Shipping_Method' ) ) {
	return;
}

/**
 * Class Nova_Poshta_Shipping_Method
 */
class NovaPoshta extends WC_Shipping_Method {

	/**
	 * Shipping method name
	 *
	 * @var string
	 */
	const ID = 'shipping_nova_poshta_for_woocommerce';

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

		$this->id                 = 'shipping_nova_poshta_for_woocommerce';
		$this->method_title       = esc_html__( 'Nova Poshta delivery', 'shipping-nova-poshta-for-woocommerce' );
		$this->method_description = esc_html__( 'Allow your customers to deliver your products right to the warehouse near their homes. Your customer needs to choose a city and warehouse from the smart select fields. Also, you can gift customers free delivery if their cart more than any exact price.', 'shipping-nova-poshta-for-woocommerce' );
		$this->enabled            = 'yes';
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

		$this->title = $this->get_option( 'title' );

		add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	/**
	 * Init form fields
	 */
	public function init_form_fields() {

		$this->instance_form_fields = [
			'title'                => [
				'title'   => esc_html__( 'Method header', 'shipping-nova-poshta-for-woocommerce' ),
				'type'    => 'text',
				'default' => esc_html__( 'Nova Poshta delivery', 'shipping-nova-poshta-for-woocommerce' ),
			],
			'cost'                 => [
				'title'             => esc_html__( 'Cost', 'shipping-nova-poshta-for-woocommerce' ),
				'type'              => 'text',
				'placeholder'       => '',
				'description'       => __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'woocommerce' ) . '<br/><br/>' . __( 'Use <code>[qty]</code> for the number of items, <br/><code>[cost]</code> for the total cost of items, and <code>[fee percent="10" min_fee="20" max_fee=""]</code> for percentage based fees.', 'woocommerce' ),
				'default'           => '0',
				'desc_tip'          => true,
				'sanitize_callback' => [ $this, 'sanitize_cost' ],
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
	 * Calculate shipping method.
	 *
	 * Important method!
	 *
	 * @access public
	 *
	 * @param array $package Packages.
	 *
	 * @return void
	 */
	public function calculate_shipping( $package = [] ) {

		$args = [
			'id'       => $this->id,
			'label'    => $this->title,
			'calc_tax' => 'per_item',
			'cost'     => 0,
		];

		$cost = $this->get_option( 'cost' );

		if ( '' !== $cost ) {
			$args['cost'] = $this->evaluate_cost(
				$cost,
				[
					'qty'  => $this->get_package_item_qty( $package ),
					'cost' => $package['contents_cost'],
				]
			);
		}

		$this->add_rate( $args );
	}

	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param  string $sum Sum of shipping.
	 * @param  array  $args Args, must contain `cost` and `qty` keys. Having `array()` as default is for back compat reasons.
	 * @return string
	 */
	private function evaluate_cost( $sum, $args = [] ) {

		// Add warning for subclasses.
		if ( ! is_array( $args ) || ! array_key_exists( 'qty', $args ) || ! array_key_exists( 'cost', $args ) ) {
			wc_doing_it_wrong( __FUNCTION__, '$args must contain `cost` and `qty` keys.', '4.0.1' );
		}

		include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

		// Allow 3rd parties to process shipping cost arguments.
		$args           = (array) apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = [
			wc_get_price_decimal_separator(),
			$locale['decimal_point'],
			$locale['mon_decimal_point'],
			',',
		];
		$this->fee_cost = $args['cost'];

		// Expand shortcodes.
		add_shortcode( 'fee', [ $this, 'fee' ] );

		$sum = do_shortcode(
			str_replace(
				[
					'[qty]',
					'[cost]',
				],
				[
					$args['qty'],
					$args['cost'],
				],
				$sum
			)
		);

		remove_shortcode( 'fee', [ $this, 'fee' ] );

		// Remove whitespace from string.
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string.
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters.
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math.
		return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
	}

	/**
	 * Get items in package.
	 *
	 * @param array $package Package of items from cart.
	 *
	 * @return int
	 */
	private function get_package_item_qty( array $package ): int {

		$total_quantity = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}

		return $total_quantity;
	}

	/**
	 * Work out fee (shortcode).
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function fee( $atts ) {

		$atts = shortcode_atts(
			[
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
			],
			$atts,
			'fee'
		);

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}

		return $calculated_fee;
	}

}
