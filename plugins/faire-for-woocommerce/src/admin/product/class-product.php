<?php
/**
 * Product backend common functionality.
 *
 * @package  Faire/Admin
 */

namespace Faire\Wc\Admin\Product;

use Faire\Wc\Admin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product backend common functionality class.
 */
class Product {

	/**
	 * The settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * Prefix for product custom fields.
	 *
	 * @var string
	 */
	public const PRODUCT_FIELDS_PREFIX = 'woocommerce_faire_product_';

	/**
	 * Class constructor.
	 */
	public function __construct() {

		$this->settings = new Settings();

		// Adds simple product custom fields in general tab.
		add_action(
			'woocommerce_product_options_general_product_data',
			array( $this, 'product_common_custom_fields' ),
			11
		);

		// Adds product custom fields in inventory tab.
		add_action(
			'woocommerce_product_options_inventory_product_data',
			array( $this, 'add_product_inventory_custom_fields' )
		);
	}

	/**
	 * Adds product custom fields in general tab.
	 */
	public function product_common_custom_fields() {
		$types         = get_option( 'faire_taxonomy_types', array() );
		$types_options = array(
			'' => __( 'Select one', 'faire-for-woocommerce' ),
		);
		if ( $types ) {
			$types_options = array_merge( $types_options, $types );
		}
		echo '<div class="options_group show_if_simple show_if_variable">';
		woocommerce_wp_select(
			array(
				'id'      => self::PRODUCT_FIELDS_PREFIX . 'taxonomy_type',
				'label'   => __( 'Taxonomy Type (Faire)', 'faire-for-woocommerce' ),
				'options' => $types_options,
				'class'   => 'wc-enhanced-select',
			)
		);
		echo '</div>';
	}

	/**
	 * Adds custom fields into product inventory tab.
	 */
	public function add_product_inventory_custom_fields() {
		echo '<div class="options_group show_if_simple show_if_variable">';
		woocommerce_wp_select(
			array(
				'id'      => self::PRODUCT_FIELDS_PREFIX . 'allow_preorder',
				'label'   => __( 'Allow pre-order? (Faire)', 'faire-for-woocommerce' ),
				'options' => array(
					'do_not_allow' => __( 'Do not allow', 'faire-for-woocommerce' ),
					'allow'        => __( 'Allow', 'faire-for-woocommerce' ),
				),
			)
		);
		echo sprintf( '<div id="%s">', esc_attr( static::PRODUCT_FIELDS_PREFIX ) . 'preorder_dates' );
		woocommerce_wp_text_input(
			array(
				'id'          => self::PRODUCT_FIELDS_PREFIX . 'order_by_date',
				'label'       => __( 'Order by date', 'faire-for-woocommerce' ),
				'type'        => 'date',
				'placeholder' => 'YYYY-MM-DD',
				'data_type'   => 'date',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'          => self::PRODUCT_FIELDS_PREFIX . 'keep_active_past_order_by_date',
				'label'       => '',
				'description' => __( 'Keep active past order by date', 'faire-for-woocommerce' ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => self::PRODUCT_FIELDS_PREFIX . 'expected_ship_date',
				'label'       => __( 'Expected ship date', 'faire-for-woocommerce' ),
				'type'        => 'date',
				'placeholder' => 'YYYY-MM-DD',
				'data_type'   => 'date',
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => self::PRODUCT_FIELDS_PREFIX . 'expected_ship_window_date',
				'label'       => __( 'Expected ship window date', 'faire-for-woocommerce' ),
				'type'        => 'date',
				'placeholder' => 'YYYY-MM-DD',
				'data_type'   => 'date',
			)
		);
		echo '</div>';
		echo '</div>';
		echo '<div class="options_group show_if_simple show_if_variable">';
		woocommerce_wp_text_input(
			array(
				'id'                => self::PRODUCT_FIELDS_PREFIX . 'unit_multiplier',
				'label'             => __( 'Case quantity (Faire)', 'faire-for-woocommerce' ),
				'type'              => 'number',
				'default'           => '1',
				'custom_attributes' => array(
					'min'  => '1',
					'step' => '1',
				),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'                => self::PRODUCT_FIELDS_PREFIX . 'minimum_order_quantity',
				'label'             => __( 'Minimum order quantity (Faire)', 'faire-for-woocommerce' ),
				'type'              => 'number',
				'custom_attributes' => array(
					'min'  => '0',
					'step' => '1',
				),
			)
		);
		echo '<div class="show_if_variable">';
		woocommerce_wp_text_input(
			array(
				'id'                => self::PRODUCT_FIELDS_PREFIX . 'per_style_minimum_order_quantity',
				'label'             => __( 'Per style minimum order quantity (Faire)', 'faire-for-woocommerce' ),
				'type'              => 'number',
				'custom_attributes' => array(
					'min'  => '0',
					'step' => '1',
				),
				'desc_tip'          => true,
				'description'       => __( 'Setting this field will set "Case quantity" to 1 and "Minimum order quantity" to 0', 'faire-for-woocommerce' ),
			)
		);
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Save product custom fields.
	 *
	 * @param int      $post_id The product post ID.
	 * @param array    $fields A list of custom fields names and their types.
	 * @param array    $posted_data The product custom fields posted values.
	 * @param callable $sanitizer A sanitizer function.
	 */
	protected function save_product_fields(
		int $post_id,
		array $fields,
		array $posted_data,
		callable $sanitizer
	) {
		// Logic to reset values of mutually exclusive fields.
		if (
			isset( $posted_data[ self::PRODUCT_FIELDS_PREFIX . 'per_style_minimum_order_quantity' ] ) &&
			0 < (int) $posted_data[ self::PRODUCT_FIELDS_PREFIX . 'per_style_minimum_order_quantity' ]
		) {
			$posted_data[ self::PRODUCT_FIELDS_PREFIX . 'unit_multiplier' ]        = 1;
			$posted_data[ self::PRODUCT_FIELDS_PREFIX . 'minimum_order_quantity' ] = 0;
		}

		foreach ( $fields as $field_name => $field_type ) {
			$field = self::PRODUCT_FIELDS_PREFIX . $field_name;
			if ( ! isset( $_POST[ $field ] ) ) {
				continue;
			}
			$value = $sanitizer( $posted_data, $field, $post_id );

			if ( 'bool' === $field_type && empty( $value ) ) {
				$value = 'no';
			}
			if ( ! isset( $value ) ) {
				continue;
			}

			switch ( $field_type ) {
				case 'int':
					$value = (int) $value;
					break;

				case 'float':
					if ( ! empty( $value ) ) {
						$value = (float) str_replace( wc_get_price_decimal_separator(), '.', $value );
					}
					break;
			}

			update_post_meta( $post_id, $field, $value );
		}
	}

}
