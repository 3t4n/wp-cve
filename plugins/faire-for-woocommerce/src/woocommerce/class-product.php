<?php
/**
 * WooCommerce Products.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Woocommerce;

use Faire\Wc\Admin\Settings;
use WC_Product;

class Product extends WC_Product {

	/**
	 * Retrieves the WC products from items in a Faire order.
	 *
	 * Builds a list containing the product data and the order item data.
	 *
	 * @param array<object> $order_items The Faire order items.
	 *
	 * @return array<array{ product_obj: WC_Product|false, quantity: int, item: object }> List of products and order item data.
	 */
	public static function get_wc_products_in_faire_order( array $order_items ): array {
		$products = array();
		foreach ( $order_items as $item ) {
			// Get the product ID using the provided SKU.
			$product = self::get_wc_product_by_faire_product_id( $item->product_id, $item->variant_id );

			if ( ! $product ) {
				continue;
			}

			$products[] = array(
				'product_obj' => $product,
				'quantity'    => $item->quantity,
				'item'        => $item,
			);
		}

		return $products;
	}

	/**
	 * Retrieves a WC product (simple or variation) related to a Faire product.
	 *
	 * @param string $faire_product_id The Faire product ID.
	 * @param string $faire_variant_id The Faire variant ID.
	 *
	 * @return WC_Product|false The WC product or false if not found.
	 */
	public static function get_wc_product_by_faire_product_id(
		string $faire_product_id,
		string $faire_variant_id
	) {
		$settings = new Settings();

		// Retrieve WC product with the Faire product ID.
		$product = self::get_product_by_metadata(
			$settings->get_meta_faire_product_id(),
			$faire_product_id,
		);

		if ( ! $product ) {
			return false;
		}

		if ( 'variable' !== $product->get_type() ) {
			return $product;
		}

		// If the WC product is variable, retrieve the WC variation with the Faire product variation ID.
		return self::get_wc_variation_by_faire_variant_id( $faire_product_id, $faire_variant_id );
	}

	/**
	 * Retrieves a WC variation related to a given Faire variant.
	 *
	 * A WC variation can be related to several Faire variants.
	 * Therefore, meta field `_faire_product_variation_id` contains
	 * an array.
	 *
	 * @param string $parent_id        The ID of Faire the parent product.
	 * @param string $faire_variant_id The Faire variant ID.
	 *
	 * @return WC_Product|false The WC product or false if not found.
	 */
	public static function get_wc_variation_by_faire_variant_id( string $parent_id, string $faire_variant_id ) {
		$settings   = new Settings();
		$variations = get_posts(
			array(
				'post_parent'    => $parent_id,
				'post_type'      => 'product_variation',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => $settings->get_meta_faire_variant_id(),
						'value'   => sprintf( ':"%s";', $faire_variant_id ),
						'compare' => 'LIKE',
					),
				),
			)
		);

		return $variations ? wc_get_product( $variations[0] ) : false;
	}

	/**
	 * Retrieves a single product having a metadata field with a given value.
	 *
	 * @param string $meta_field The name of the meta field.
	 * @param string $meta_value The value of the meta field.
	 *
	 * @return WC_Product|false The product if found, false otherwise.
	 */
	public static function get_product_by_metadata( string $meta_field, string $meta_value ) {

		/**
		 * Array of WC_Product
		 *
		 * @var array<WC_Product>
		 */
		$products = wc_get_products(
			array(
				$meta_field => $meta_value,
				'limit'     => 1,
			)
		);

		return $products ? $products[0] : false;
	}

	/**
	 * Update stock values for products from an order attending to its status.
	 *
	 * @param array<array{ "product_obj": WC_Product|false, "quantity": int }> $order_products The list of products from the order.
	 * @param string        $order_status   The order status.
	 *
	 * @return array<int> List of products ID whose stock couldn't be updated.
	 */
	public static function update_inventory( array $order_products, string $order_status ): array {
		$increase = 'cancelled' === $order_status;

		$failed_products = array();
		foreach ( $order_products as $product ) {
			$result = wc_update_product_stock(
				$product['product_obj'],
				$product['quantity'],
				$increase ? 'increase' : 'decrease'
			);
			if ( false === $result ) {
				$failed_products[] = $product['product_obj']->get_id();
			}
		}

		return $failed_products;
	}

}
