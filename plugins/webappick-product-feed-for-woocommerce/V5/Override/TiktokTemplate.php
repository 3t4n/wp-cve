<?php
/**
 * This class is responsible for all override TiktokTemplate template.
 *
 * @package CTXFeed\V5\Override
 */

namespace CTXFeed\V5\Override;

/**
 * Class Tiktok
 *
 * @package    CTXFeed\V5\Override
 */
class TiktokTemplate {

	/**
	 * TiktokTemplate class constructor
	 */
	public function __construct() {
		add_filter(
            'woo_feed_get_tiktok_color_attribute',
            array(
				$this,
				'woo_feed_get_tiktok_color_size_attribute_callback',
			)
            );
		add_filter(
            'woo_feed_get_tiktok_size_attribute',
            array(
				$this,
				'woo_feed_get_tiktok_color_size_attribute_callback',
			)
            );
		add_filter(
            'woo_feed_get_tiktok_shipping_weight_attribute',
            array(
				$this,
				'woo_feed_get_tiktok_shipping_weight_attribute_callback',
			),
			10,
			4
            );
	}

	/**
	 * @param string $output Product description.
     * @return string
	 */
	public function woo_feed_get_tiktok_color_size_attribute_callback( $output ) {
		return str_replace( array( ' ' ), array( '' ), $output );
	}

	/**
	 * @param string $output Shipping weight.
	 * @param object $product Product object.
	 * @param object $config Feed config.
	 * @param string $product_attribute Product attribute.
     * @return string
	 */
	public function woo_feed_get_tiktok_shipping_weight_attribute_callback( $output, $product, $config, $product_attribute ) { // phpcs:ignore

		$wc_unit    = ' ' . get_option( 'woocommerce_weight_unit' );
		$attributes = false;

		if ( $config->attributes ) {
			$attributes = $config->attributes;
		}

		if ( ! $attributes ) {
			return $output;
		}

		$key = array_search( $product_attribute, $attributes, true );

		// ! empty( $key ) this condition is removed because it is not working for 0 index.
		if ( isset( $config->suffix ) && array_key_exists( $key, $config->suffix ) ) {
			$unit = $config->suffix[ $key ];

			if ( ! empty( $unit ) && ! empty( $output ) ) {
				$output .= ' ' . $unit;
			} elseif ( ! empty( $wc_unit ) && ! empty( $output ) ) {
				$output .= $wc_unit;
			}
		}

		return $output;
	}

}
