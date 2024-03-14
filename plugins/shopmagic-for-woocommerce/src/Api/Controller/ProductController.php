<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

class ProductController {

	private const LIMIT = 30;

	public function index( string $include ): \WP_REST_Response {
		$products = array_map(
			[ $this, 'format_product_option' ],
			wc_get_products( [
				'include' => array_map( static function ( $id ) {
					return absint( $id );
				}, explode( ',', $include ) ),
			] )
		);

		return new \WP_REST_Response( $products );
	}

	public function search( string $s ): \WP_REST_Response {
		/** @var \WC_Product_Data_Store_CPT $data_store */
		$data_store = \WC_Data_Store::load( 'product' );
		$ids        = $data_store->search_products( $s, '', true, false, self::LIMIT );

		$products = [];

		foreach ( $ids as $id ) {
			$product_object = wc_get_product( $id );

			if ( ! wc_products_array_filter_readable( $product_object ) ) {
				continue;
			}

			$products[] = $this->format_product_option( $product_object );
		}

		return new \WP_REST_Response( $products );
	}

	private function format_product_option( \WC_Product $product ): array {
		$formatted_name = $product->get_formatted_name();

		return [
			'value' => $product->get_id(),
			'label' => rawurldecode( wp_strip_all_tags( $formatted_name ) ),
		];
	}

}
