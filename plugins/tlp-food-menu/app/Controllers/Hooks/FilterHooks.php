<?php
/**
 * Filter Hook Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Hooks;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Filter Hook  Class.
 */
class FilterHooks {
	use \RT\FoodMenu\Traits\SingletonTrait;

	protected function init() {
		\add_filter( 'fmp_image_size', [ $this, 'get_image_sizes' ] );
		\add_filter( 'wp_kses_allowed_html', [ $this, 'custom_post_tags' ], 10, 2 );
		\add_filter( 'rtfm_add_to_cart_btn', [ $this, 'cartBtn' ], 10, 7 );
	}

	public function get_image_sizes( $imgSize ) {
		$imgSize['full'] = esc_html__( 'Full Size', 'tlp-food-menu' );

		return $imgSize;
	}

	/**
	 * Add script to allowed wp_kses_post tags
	 *
	 * @param array  $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
	 *
	 * @return array
	 */
	public function custom_post_tags( $tags, $context ) {

		if ( 'post' === $context ) {
			$tags['style'] = [
				'src' => true,
			];

			$tags['input'] = [
				'type'        => true,
				'class'       => true,
				'name'        => true,
				'step'        => true,
				'min'         => true,
				'title'       => true,
				'size'        => true,
				'pattern'     => true,
				'inputmode'   => true,
				'value'       => true,
				'id'          => true,
				'placeholder' => true,
			];

			$tags['iframe'] = [
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			];
		}

		return $tags;
	}

	public function cartBtn( $content, $link, $id, $type, $text, $items, $popup = false ) {
		if ( TLPFoodMenu()->has_pro() || 'variable' === $type ) {
			return $content;
		}

		$text = esc_html__( 'Add to Cart', 'tlp-food-menu' );

		$add_to_cart_button = sprintf(
			'<a href="?add-to-cart=%2$d" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart fmp-wc-add-to-cart-btn" data-product_id="%2$d" data-type="%3$s">%4$s<span></span></a>',
			esc_url( $link ),
			absint( $id ),
			esc_html( $type ),
			esc_html( $text )
		);

		$content = sprintf(
			'<div class="fmp-wc-add-to-cart-wrap">%s</div>',
			$add_to_cart_button
		);

		return $content;
	}
}
