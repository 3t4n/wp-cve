<?php
/**
 * Product backend functionality.
 *
 * @package  Faire/Admin
 */

namespace Faire\Wc\Admin\Product;

use WC_Product_Factory;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product backend functionality class.
 */
class Simple extends Product {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Adds simple product custom fields in general tab.
		add_action(
			'woocommerce_product_options_general_product_data',
			array( $this, 'product_general_custom_fields' )
		);

		// Saves product custom fields.
		add_action(
			'woocommerce_process_product_meta',
			array( $this, 'save_product_custom_fields' )
		);
	}

	/**
	 * Adds product custom fields in general tab.
	 */
	public function product_general_custom_fields() {
		echo '<div class="options_group show_if_simple">';

		if ( 'wholesale_percentage' === $this->settings->get_product_pricing_policy() ) {
			woocommerce_wp_text_input(
				array(
					'id'          => self::PRODUCT_FIELDS_PREFIX . 'wholesale_price',
					// translators: %s currency.
					'label'       => sprintf( __( 'Wholesale price (%s) (Faire)', 'faire-for-woocommerce' ), get_woocommerce_currency_symbol() ),
					'placeholder' => '',
					'type'        => 'text',
					'data_type'   => 'price',
					'class'       => 'short wc_input_price',
				)
			);
		} elseif ( 'wholesale_multiplier' === $this->settings->get_product_pricing_policy() ) {
			woocommerce_wp_text_input(
				array(
					'id'          => self::PRODUCT_FIELDS_PREFIX . 'retail_price',
					// translators: %s currency.
					'label'       => sprintf( __( 'Retail price (%s) (Faire)', 'faire-for-woocommerce' ), get_woocommerce_currency_symbol() ),
					'placeholder' => '',
					'type'        => 'text',
					'data_type'   => 'price',
					'class'       => 'short wc_input_price',
				)
			);
		}

		woocommerce_wp_text_input(
			array(
				'id'          => self::PRODUCT_FIELDS_PREFIX . 'tariff_code',
				'label'       => __( 'Tariff code (Faire)', 'faire-for-woocommerce' ),
				'placeholder' => '',
				'type'        => 'text',
			)
		);
		echo '</div>';
		echo '<div class="options_group show_if_simple show_if_variable">';
		woocommerce_wp_select(
			array(
				'id'      => self::PRODUCT_FIELDS_PREFIX . 'lifecycle_state',
				'label'   => __( 'Lifecycle State (Faire)', 'faire-for-woocommerce' ),
				'default' => 'UNPUBLISHED',
				'options' => array(
					'UNPUBLISHED' => __( 'Unpublished', 'faire-for-woocommerce' ),
					'PUBLISHED'   => __( 'Published', 'faire-for-woocommerce' ),
					'DRAFT'       => __( 'Draft', 'faire-for-woocommerce' ),
				),
			)
		);
		echo '</div>';

	}

	/**
	 * Saves product custom fields.
	 *
	 * @param int $product_id The product post ID.
	 */
	public function save_product_custom_fields( int $product_id ) {
		$product_type = WC_Product_Factory::get_product_type( $product_id );
		if ( ! $product_type || 'variable' === $product_type ) {
			return;
		}

		$fields = array(
			'wholesale_price'                  => 'float',
			'retail_price'                     => 'float',
			'tariff_code'                      => 'string',
			'lifecycle_state'                  => 'string',
			'allow_preorder'                   => 'string',
			'order_by_date'                    => 'string',
			'keep_active_past_order_by_date'   => 'bool',
			'expected_ship_date'               => 'date',
			'expected_ship_window_date'        => 'date',
			'taxonomy_type'                    => 'string',
			'unit_multiplier'                  => 'string',
			'minimum_order_quantity'           => 'string',
			'per_style_minimum_order_quantity' => 'string',
		);

		$this->save_product_fields(
			$product_id,
			$fields,
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$_POST,
			fn( $data, $field ) => esc_attr(
				sanitize_text_field( $data[ $field ] )
			)
		);
	}

}
