<?php

namespace AppBuilder\AdvancedRestApi;

defined( 'ABSPATH' ) || exit;

use WC_REST_Products_Controller;

class Product extends WC_REST_Products_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3-advanced';

	/**
	 * Get attribute options.
	 *
	 * @param int   $product_id Product ID.
	 * @param array $attribute  Attribute data.
	 *
	 * @return array
	 */
	protected function get_attribute_options( $product_id, $attribute ) {
		if ( isset( $attribute['is_taxonomy'] ) && $attribute['is_taxonomy'] ) {
			return wc_get_product_terms(
				$product_id,
				$attribute['name'],
				array(
					'fields' => 'all',
				)
			);
		} elseif ( isset( $attribute['value'] ) ) {
			return array_map( 'trim', explode( '|', $attribute['value'] ) );
		}

		return array();
	}
}
