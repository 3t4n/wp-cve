<?php
/**
 * Backend functionality for product variations.
 *
 * @package  Faire/Admin
 */

namespace Faire\Wc\Admin\Product;

use WC_Product_Factory;
use WP_Post;
use Faire\Wc\Admin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Backend functionality class for product variations.
 */
class Variation extends Product {

	/**
	 * The settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * Name of the Faire product ID meta field.
	 *
	 * @var string
	 */
	private string $meta_faire_product_id;

	/**
	 * Name of the Faire variant ID meta field.
	 *
	 * @var string
	 */
	private string $meta_faire_variant_id;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		$this->settings              = new Settings();
		$this->meta_faire_product_id = $this->settings->get_meta_faire_product_id();
		$this->meta_faire_variant_id = $this->settings->get_meta_faire_variant_id();

		// Saves product custom fields.
		add_action(
			'woocommerce_process_product_meta',
			array( $this, 'save_product_custom_fields' )
		);

		// Adds product variation custom fields in pricing tab.
		add_action(
			'woocommerce_variation_options_pricing',
			array( $this, 'variation_pricing_custom_fields' ),
			10,
			3
		);

		// Saves product variation custom fields.
		add_action(
			'woocommerce_save_product_variation',
			array( $this, 'save_product_variation' ),
			10,
			1
		);

	}

	/**
	 * Adds product variation custom fields in pricing tab.
	 *
	 * @param int     $loop The loop.
	 * @param array   $variation_data Variation data.
	 * @param WP_Post $variation The variation post object.
	 */
	public function variation_pricing_custom_fields( int $loop, array $variation_data, WP_Post $variation ) {

		if ( 'wholesale_percentage' === $this->settings->get_product_pricing_policy() ) {
			woocommerce_wp_text_input(
				array(
					'id'            => self::PRODUCT_FIELDS_PREFIX . 'variation_wholesale_price',
					'name'          => self::PRODUCT_FIELDS_PREFIX . sprintf( 'variation_wholesale_price[%d]', $variation->ID ),
					'class'         => 'short wc_input_price',
					'wrapper_class' => 'form-row form-row-first',
					// translators: %s currency.
					'label'         => sprintf( __( 'Wholesale price (%s) (Faire)', 'faire-for-woocommerce' ), get_woocommerce_currency_symbol() ),
					'placeholder'   => '',
					'type'          => 'text',
					'data_type'     => 'price',
					'value'         => get_post_meta( $variation->ID, self::PRODUCT_FIELDS_PREFIX . 'variation_wholesale_price', true ),
				)
			);
		} elseif ( 'wholesale_multiplier' === $this->settings->get_product_pricing_policy() ) {
			woocommerce_wp_text_input(
				array(
					'id'            => self::PRODUCT_FIELDS_PREFIX . 'variation_retail_price',
					'name'          => self::PRODUCT_FIELDS_PREFIX . sprintf( 'variation_retail_price[%d]', $variation->ID ),
					'class'         => 'short wc_input_price',
					'wrapper_class' => 'form-row form-row-first',
					// translators: %s currency.
					'label'         => sprintf( __( 'Retail price (%s) (Faire)', 'faire-for-woocommerce' ), get_woocommerce_currency_symbol() ),
					'placeholder'   => '',
					'type'          => 'text',
					'data_type'     => 'price',
					'value'         => get_post_meta( $variation->ID, self::PRODUCT_FIELDS_PREFIX . 'variation_retail_price', true ),
				)
			);
		}

		woocommerce_wp_text_input(
			array(
				'id'            => self::PRODUCT_FIELDS_PREFIX . 'variation_tariff_code',
				'name'          => self::PRODUCT_FIELDS_PREFIX . sprintf( 'variation_tariff_code[%d]', $variation->ID ),
				'class'         => 'short',
				'wrapper_class' => 'form-row form-row-last',
				'label'         => __( 'Tariff code (Faire)', 'faire-for-woocommerce' ),
				'placeholder'   => '',
				'type'          => 'text',
				'value'         => get_post_meta( $variation->ID, self::PRODUCT_FIELDS_PREFIX . 'variation_tariff_code', true ),
			)
		);
		woocommerce_wp_select(
			array(
				'id'            => self::PRODUCT_FIELDS_PREFIX . 'variation_lifecycle_state',
				'name'          => self::PRODUCT_FIELDS_PREFIX . sprintf( 'variation_lifecycle_state[%d]', $variation->ID ),
				'class'         => 'short',
				'wrapper_class' => 'form-row',
				'label'         => __( 'Lifecycle State (Faire)', 'faire-for-woocommerce' ),
				'default'       => 'UNPUBLISHED',
				'options'       => array(
					'UNPUBLISHED' => __( 'Unpublished', 'faire-for-woocommerce' ),
					'PUBLISHED'   => __( 'Published', 'faire-for-woocommerce' ),
					'DRAFT'       => __( 'Draft', 'faire-for-woocommerce' ),
				),
				'value'         => get_post_meta( $variation->ID, self::PRODUCT_FIELDS_PREFIX . 'variation_lifecycle_state', true ),
			)
		);
		$linking_error = get_post_meta( $variation->post_parent, '_faire_product_linking_error', true );
		if ( $linking_error === 'manual_link_variants' ) {
			$faire_variant_id   = get_post_meta( $variation->ID, $this->meta_faire_variant_id, true );
			$unmatched_variants = get_post_meta( $variation->post_parent, '_faire_product_unmatched_variants', true );
			if ( ! $faire_variant_id && $unmatched_variants ) {
				$unmatched_options = array_merge(
					array(
						'' => __( 'Select one', 'faire-for-woocommerce' ),
					),
					$unmatched_variants
				);
				woocommerce_wp_select(
					array(
						'id'            => self::PRODUCT_FIELDS_PREFIX . 'variation_linking_set',
						'name'          => self::PRODUCT_FIELDS_PREFIX . sprintf( 'variation_linking_set[%d]', $variation->ID ),
						'class'         => 'short',
						'wrapper_class' => 'form-row',
						'label'         => __( 'Variation Linking (Faire)', 'faire-for-woocommerce' ),
						'default'       => '',
						'options'       => $unmatched_options,
						'value'         => '',
					)
				);
			}
		}
	}

	/**
	 * Saves product variation custom fields.
	 *
	 * @param int $post_id The product variation post ID.
	 *
	 * @return void
	 */
	public function save_product_variation( $post_id ) {
		$fields = array(
			'variation_wholesale_price' => 'float',
			'variation_retail_price'    => 'float',
			'variation_tariff_code'     => 'string',
			'variation_lifecycle_state' => 'string',
			'variation_linking_set'     => 'string',
		);

		$this->save_product_fields(
			$post_id,
			$fields,
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$_POST,
			fn( $data, $field, $index ) => esc_attr(
				sanitize_text_field( $data[ $field ][ $index ] )
			)
		);

		// If linking unmatched variant.
		if ( isset( $_POST['woocommerce_faire_product_variation_linking_set'][ $post_id ] ) && $_POST['woocommerce_faire_product_variation_linking_set'][ $post_id ] ) {
			$unmatched_variant_id = sanitize_text_field( $_POST['woocommerce_faire_product_variation_linking_set'][ $post_id ] );

			// Link faire variant id to variation.
			$faire_variant_ids = array(
				sanitize_text_field( $unmatched_variant_id ),
			);
			update_post_meta( $post_id, $this->meta_faire_variant_id, $faire_variant_ids );

			// Remove from unmatched.
			$parent_id = wp_get_post_parent_id( $post_id );
			if ( false === $parent_id ) {
				return;
			}

			$unmatched_variants = get_post_meta( $parent_id, '_faire_product_unmatched_variants', true );
			unset( $unmatched_variants[ $unmatched_variant_id ] );
			update_post_meta( $parent_id, '_faire_product_unmatched_variants', $unmatched_variants );
			// Maybe cleanup linking error.
			if ( empty( $unmatched_variants ) ) {
				$linking_error = delete_post_meta( $parent_id, '_faire_product_linking_error' );
			}
		}
	}

	/**
	 * Saves variable product custom fields.
	 *
	 * @param int $product_id The product post ID.
	 */
	public function save_product_custom_fields( int $product_id ) {
		$product_type = WC_Product_Factory::get_product_type( $product_id );
		if ( ! $product_type || 'variable' !== $product_type ) {
			return;
		}

		$fields = array(
			'allow_preorder'                   => 'string',
			'order_by_date'                    => 'string',
			'keep_active_past_order_by_date'   => 'bool',
			'expected_ship_date'               => 'date',
			'expected_ship_window_date'        => 'date',
			'taxonomy_type'                    => 'string',
			'lifecycle_state'                  => 'string',
			'unit_multiplier'                  => 'int',
			'minimum_order_quantity'           => 'int',
			'per_style_minimum_order_quantity' => 'int',
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
