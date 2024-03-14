<?php
/**
 * WC_Product_Item
 *
 * Request WooCommerce product data
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Settings;
use WP_DataSync\App\Item;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Product_Item {

	/**
	 * @var WC_Product
	 */

	private $product;

	/**
	 * @var int
	 */

	private $product_id;

	/**
	 * @var WC_Product_Item
	 */

	public static $instance;

	/**
	 * WC_Product_Item constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * @return WC_Product_Item
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * WC Process.
	 *
	 * @param array $item_data
	 * @param int $product_id
	 *
	 * @return mixed
	 */

	public function wc_process( $item_data, $product_id ) {

		$this->product_id = $product_id;
		$this->product    = wc_get_product( $product_id );

		if ( ! Settings::is_data_type_excluded( 'gallery_images' ) ) {

			if ( $images = $this->gallery_images() ) {
				$item_data['gallery_images'] = $images;
			}
		}

		if ( ! Settings::is_data_type_excluded( 'attributes' ) ) {

			if ( $attributes = $this->product_attributes() ) {
				$item_data['attributes'] = $attributes;
			}

		}

		if ( ! Settings::is_data_type_excluded( 'variations' ) ) {

			if ( $this->product->is_type( 'variable' ) ) {

				if ( $variations = $this->product_variations() ) {
					$item_data['variations'] = $variations;
				}

			}

		}

		return $item_data;

	}

	/**
	 * Gallery images.
	 *
	 * @since 1.6.0
	 *
	 * @return array|bool
	 */

	public function gallery_images() {

		$image_ids  = $this->product->get_gallery_image_ids();
		$image_urls = [];
		$i          = 1;

		if ( empty ( $image_ids ) ) {
			return false;
		}

		foreach ( $image_ids as $image_id ) {

			$image_urls["image_$i"] = [
				'image_url'   => wp_get_attachment_image_url( $image_id, 'full' ),
				'title'       => get_the_title( $image_id ) ?: '',
				'description' => get_the_content( $image_id ) ?: '',
				'caption'     => get_the_excerpt( $image_id ) ?: '',
				'alt'         => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: ''
			];

			$i++;

		}

		return $image_urls;

	}

	/**
	 * Product attributes.
	 *
	 * @return array|bool
	 */

	public function product_attributes() {

		if ( $product_attributes = get_post_meta( $this->product_id, '_product_attributes', true ) ) {

			$attributes = [];

			foreach ( $product_attributes as $attribute ) {

				$slug = wc_attribute_taxonomy_slug( $attribute['name'] );

				$attributes[ $slug ] = $attribute;

				if ( $attribute['is_taxonomy'] ) {

					$attributes[ $slug ]['name']   = wc_attribute_label( $attribute['name'] );
					$value                         = $this->product->get_attribute( $attribute['name'] );
					$attributes[ $slug ]['values'] = $this->explode( $value );

				} else {
					$attributes[ $slug ]['values'] = $this->explode( $attribute['value'] );
				}

				unset( $attributes[ $slug ]['value'] );

			}

			return array_filter( $attributes );

		}

		return false;

	}

	/**
	 * Explode.
	 *
	 * @param $value
	 *
	 * @return array
	 */

	public function explode( $value ) {

		$replace = [ '\\,', '|' ];
		$value   = str_replace( $replace, ',', $value );

		return array_map( 'trim', explode( ',', $value ) );

	}

	/**
	 * Product variations.
	 *
	 * @return bool|array
	 */

	public function product_variations() {

		$variations    = [];
		$variation_ids = $this->product->get_children();

		if ( empty( $variation_ids ) ) {
			return false;
		}

		foreach ( $variation_ids as $variation_id ) {

			$item = new Item( $variation_id );

			$variations[] = $item->get();
		}

		return $variations;

	}

}