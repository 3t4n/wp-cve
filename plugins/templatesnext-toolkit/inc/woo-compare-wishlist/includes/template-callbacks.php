<?php
/**
 * Define callback functions for templater
 *
 * @package   TX WooCommerce Package
 * @author    TemplateMonster
 * @license   GPL-2.0+
 * @link      http://www.templatemonster.com/
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {

	header( 'HTTP/1.0 404 Not Found', true, 404 );

	exit;
}

/**
 * Callbcks for team shortcode templater
 *
 * @since  1.0.0
 */
class TX_WC_Compare_Wishlist_Template_Callbacks {

	/**
	 * Get product title.
	 *
	 * @since 1.0.2
	 */
	public function get_product_title() {

		global $product;

		if ( empty( $product ) ) {

			return;
		}
		$link  = $product->get_permalink();
		$title = $product->get_title();
		$html  = sprintf( '<div class="tm-woocomerce-list__product-title"><a href="%s">%s</a></div>', $link, $title );

		return apply_filters( 'tx_woocommerce_list_product_title', $html, $link, $title, $product );
	}

	/**
	 * Get the product image.
	 *
	 * @since  1.0.2
	 * @param  array $attr Image attributes.
	 * @return string
	 */
	public function get_product_image( $attr = array() ) {

		global $product;

		if ( empty( $product ) ) {

			return;
		}
		if ( isset( $attr['placeholder'] ) ) {

			$attr['placeholder'] = json_decode( $attr['placeholder'] );
		}
		$default_attr = array(
			'size'        => 'shop_thumbnail',
			'attr'        => array(),
			'placeholder' => true
		);
		$attr  = wp_parse_args( $attr, $default_attr );
		$link  = $product->get_permalink();
		$image = $product->get_image( $attr['size'], $attr['attr'], $attr['placeholder'] );
		$html  = sprintf( '<div class="tm-woocomerce-list__product-image"><a href="%s">%s</a></div>', $link, $image );

		return apply_filters( 'tx_woocommerce_list_product_image', $html, $link, $image );
	}

	/**
	 * Get product price.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_product_price() {

		if ( ! function_exists( 'woocommerce_template_loop_price' ) ) {
			return;
		}

		ob_start();

			woocommerce_template_loop_price();

			$price = ob_get_contents();

		ob_end_clean();

		return $price;
	}

	/**
	 * Get product add to cart button.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_product_add_to_cart_button() {

		if ( ! function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			return;
		}

		ob_start();

			woocommerce_template_loop_add_to_cart();

			$add_to_cart_button = ob_get_contents();

		ob_end_clean();

		return $add_to_cart_button;
	}

	/**
	 * Get product attributes.
	 *
	 * @since 1.0.0
	 * @param  array $atts Selected attributes.
	 * @return array
	 */
	public function get_product_attributes( $atts = array() ) {

		global $product;

		if ( empty( $product ) ) {

			return;
		}

		$pid = method_exists( $product, 'get_id' ) ? $product->get_id() : get_the_id();

		if ( isset( $atts['atts'] ) ) {

			$selected_attributes = array_filter( array_map( 'trim', explode( ',', strtolower( $atts['atts'] ) ) ) );
		}
		$attributes         = $product->get_attributes();
		$product_attributes = array();

		if ( ! empty( $attributes ) ) {

			foreach ( $attributes as $attribute_id => $attribute ) {

				if ( empty( $selected_attributes ) || in_array( strtolower( wc_attribute_label( $attribute['name'] ) ), $selected_attributes ) ) {

					$attr = $attribute['is_taxonomy'] ? wc_get_product_terms( $pid, $attribute['name'], array( 'fields' => 'names' ) )
					: array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );

					$product_attributes[wc_attribute_label( $attribute['name'] )] = apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $attr ) ) ), $attribute, $attr );
				}
			}
		}
		return $product_attributes;
	}

	/**
	 * Get product stock status.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_product_stock_status() {

		global $product;

		if ( empty( $product ) ) {

			return;
		}
		$availability = $product->get_availability();

		return empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . wp_kses_post( $availability['availability'] ) . '</p>';
	}
}
