<?php
/**
 * Contains code for product util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

/**
 * Product util class.
 *
 * Helper to manage consistency between woocommerce versions product getters and setters.
 */
class Product_Util {
	/**
	 * Get product weight from product id.
	 *
	 * @param integer $product_id woocommerce product id.
	 * @return mixed $weight
	 */
	public static function get_product_weight( $product_id ) {
		if ( isset( $product_id ) && ! empty( $product_id ) ) {
			$product = self::get_product( $product_id );
			if ( $product->get_weight() && $product->get_weight() !== '' ) {
				$weight = wc_format_decimal( wc_get_weight( $product->get_weight(), 'kg' ), false );
			} else {
				return false;
			}
			return $weight;
		}
	}

	/**
	 * Get WC product price from product id.
	 *
	 * @param integer $product_id woocommerce product id.
	 * @return float|false
	 */
	public static function get_product_price( $product_id ) {
		if ( isset( $product_id ) && ! empty( $product_id ) ) {
			$product = self::get_product( $product_id );
			return self::get_price( $product );
		}
		return false;
	}

	/**
	 * Get product virtual.
	 *
	 * @param integer $product_id woocommerce product id.
	 * @return boolean
	 */
	public static function is_product_virtual( $product_id ) {
		if ( isset( $product_id ) && ! empty( $product_id ) ) {
			$product = self::get_product( $product_id );
			return self::is_virtual( $product );
		}
		return false;
	}

	/**
	 * Get product description.
	 *
	 * @param WC_Order_Item $item woocommerce order item.
	 * @return string $description
	 */
	public static function get_product_description( $item ) {
		$variation_id = $item['variation_id'];
		$check_id     = ( '0' === $variation_id || 0 === $variation_id ) ? $item['product_id'] : $variation_id;
		$product      = self::get_product( $check_id );
		$description  = '';

		if ( false !== $product ) {
			$description = self::get_name( $product );
			// add attributes to title for variations.
			$product_type = self::get_product_type( $product );
			if ( 'variation' === $product_type ) {
				$parent_id      = self::get_parent_id( $product );
				$parent_product = self::get_product( $parent_id );
				if ( false !== $parent_product ) {
					$parent_product_type = self::get_product_type( $parent_product );
					if ( 'variation' === $parent_product_type ) {
						foreach ( $parent_product->get_available_variations() as $variation ) {
							if ( $variation['variation_id'] === (int) $check_id ) {
								foreach ( $variation['attributes'] as $attributes ) {
									$description .= ' ' . $attributes;
								}
							}
						}
					}
				}
			}
		}

		return $description;
	}

	/**
	 * Get product type. Fix for WC > 3.1.
	 *
	 * @param WC_Product_Simple $product woocommerce product.
	 * @return string $product_type
	 */
	private static function get_product_type( $product ) {
		if ( method_exists( $product, 'get_type' ) ) {
			return $product->get_type();
		} else {
			return $product->product_type;
		}
	}

	/**
	 * Get WC product from product id. Fix for WC > 2.5.
	 *
	 * @param integer $product_id woocommerce product id.
	 * @return mixed $product
	 */
	public static function get_product( $product_id ) {
		if ( function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $product_id );
		} else {
			// fix for WC < 2.5.
			$product = WC()->product_factory->get_product( $product_id );
		}
		return $product;
	}

	/**
	 * Get WC product price.
	 *
	 * @param WC_Product_Simple $product woocommerce product.
	 * @return float
	 */
	public static function get_price( $product ) {
		if ( method_exists( $product, 'get_price' ) ) {
			return (float) $product->get_price();
		}
		return (float) $product->price;
	}

	/**
	 * Get product id.
	 *
	 * @param WC_Product_Simple $product woocommerce product.
	 * @return string $id
	 */
	public static function get_id( $product ) {
		if ( method_exists( $product, 'get_id' ) ) {
			return $product->get_id();
		}
		return $product->id;
	}

	/**
	 * Get product name.
	 *
	 * @param WC_Product_Simple $product woocommerce product.
	 * @return string $name
	 */
	public static function get_name( $product ) {
		if ( method_exists( $product, 'get_name' ) ) {
			return $product->get_name();
		}
		return $product->name;
	}

	/**
	 * Is product virtual.
	 *
	 * @param WC_Product_Simple $product woocommerce product.
	 * @return boolean is virtual
	 */
	public static function is_virtual( $product ) {
		if ( method_exists( $product, 'get_virtual' ) ) {
			return $product->get_virtual();
		}
		return $product->name;
	}

	/**
	 * Get WC product variation parent id.
	 *
	 * @param WC variation $variation woocommerce product variation.
	 * @return string $id
	 */
	public static function get_parent_id( $variation ) {
		if ( method_exists( $variation, 'get_parent_id' ) ) {
			return $variation->get_parent_id();
		}
		return $variation->parent->id;
	}
}
